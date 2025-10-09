<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Cuatrimestre;
use App\Models\Carrera;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function listaGrupos(Request $request){
        $query = Grupo::with(['carrera', 'cuatrimestre']);

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                ->orWhereHas('carrera', function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%$search%")
                        ->orWhere('abreviatura', 'like', "%$search%");
                })
                ->orWhereHas('cuatrimestre', function ($q2) use ($search) {
                    $q2->where('num_cuatri', 'like', "%$search%");
                })
                ->orWhere('fk_cuatrimestre', 'like', "%$search%")
                ->orWhere('año', 'like', "%$search%");
            });
        }

        // Filtro por select cuatrimestre
        if ($request->filled('cuatrimestre')) {
            $query->where('fk_cuatrimestre', $request->input('cuatrimestre'));
        }

        // Filtro por select carrera
        if ($request->filled('carrera')) {
            $query->where('fk_carrera', $request->input('carrera'));
        }

        $grupos = $query->paginate(10);
        $cuatrimestres = Cuatrimestre::all();
        $carreras = Carrera::all();

        if ($request->ajax()) {
            return view('partials.tabla_grupos', compact('grupos', 'cuatrimestres', 'carreras'))->render();
        }

        return view('coordinacion.lista-grupos', compact('grupos', 'cuatrimestres', 'carreras'));
    }

    public function listaGruposDeshabilitados(Request $request){
        $query = Grupo::onlyTrashed()->with(['carrera', 'cuatrimestre']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                ->orWhereHas('carrera', function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%$search%");
                })
                ->orWhereHas('cuatrimestre', function ($q2) use ($search) {
                    $q2->where('num_cuatri', 'like', "%$search%");
                })
                ->orWhere('año', 'like', "%$search%");
            });
        }

        if ($request->filled('cuatrimestre')) {
            $query->where('fk_cuatrimestre', $request->input('cuatrimestre'));
        }

        if ($request->filled('carrera')) {
            $query->where('fk_carrera', $request->input('carrera'));
        }

        $grupos = $query->paginate(10);
        $cuatrimestres = Cuatrimestre::all();
        $carreras = Carrera::all();

        if ($request->ajax()) {
            return view('partials.tabla_grupos', compact('grupos', 'cuatrimestres', 'carreras'))->render();
        }

        return view('coordinacion.lista-grupos', compact('grupos', 'cuatrimestres', 'carreras'));
    }

    public function guardarGrupo(Request $request){
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:1',
                'fk_carrera' => 'required|string|max:2',
                'fk_cuatrimestre' => 'required|string|max:2',
                'año' => 'required|digits:4',
            ], [
                'nombre.required' => 'El nombre del grupo es obligatorio.',
                'nombre.string' => 'El nombre del grupo debe ser un texto válido.',
                'nombre.max' => 'Ocurrió un error. ¡Contacta con el equipo de soporte!',

                'fk_carrera.required' => 'Debe seleccionar una carrera.',
                'fk_carrera.string' => 'El valor de la carrera no es válido.',
                'fk_carrera.max' => 'Ocurrió un error. ¡Contacta con el equipo de soporte!',

                'fk_cuatrimestre.required' => 'Debe seleccionar un cuatrimestre.',
                'fk_cuatrimestre.string' => 'El valor del cuatrimestre no es válido.',
                'fk_cuatrimestre.max' => 'Ocurrió un error. ¡Contacta con el equipo de soporte!',

                'año.required' => 'El año es obligatorio.',
                'año.digits' => 'El año debe contener exactamente 4 números.',
            ]);

            $grupo = Grupo::create([
                'nombre' => $validated['nombre'],
                'fk_carrera' => $validated['fk_carrera'],
                'fk_cuatrimestre' => $validated['fk_cuatrimestre'],
                'año' => $validated['año']
            ]);

            DB::commit();

            return response()->json([
                'mensaje' => 'Grupo registrado correctamente.',
                'ruta' => route('coordinacion.lista-grupos'),
                'class' => 'success'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación.',
                'errores' => $e->errors(),
                'class' => 'error'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'mensaje' => 'Ocurrió un error al registrar el grupo.',
                'detalle' => $e->getMessage(),
                'class' => 'error'
            ], 500);
        }
    }

    public function eliminarGrupo($id){
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();

        return redirect()->route('coordinacion.lista-grupos')->with('success', 'Grupo eliminado correctamente.');
    }

    public function restaurarGrupo($id){
        $grupo = Grupo::withTrashed()->findOrFail($id);
        $grupo->restore();

        return redirect()->route('coordinacion.lista-grupos-deshabilitados')->with('success', 'Grupo restaurado correctamente.');
    }
}
