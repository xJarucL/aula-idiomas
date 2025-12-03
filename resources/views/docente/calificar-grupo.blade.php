@extends('components.menu')

@section('title', 'Calificar Grupo | Aula de Idiomas')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #snackbar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(100%);
            z-index: 9999;
            min-width: 280px;
            max-width: 90%;
            text-align: center;
            padding: 14px 24px;
            border-radius: 12px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            opacity: 0;
            pointer-events: none;
        }

        .sb-success {
            background: linear-gradient(135deg, #00897B, #086960ff);
        }

        .sb-error {
            background: linear-gradient(135deg, #dc2626, #b61313ff);
        }

        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }

            to {
                transform: translateX(-50%) translateY(100%);
                opacity: 0;
            }
        }
    </style>


    <div class="">
        <x-msj-alert />
        <div id="snackbar"></div>

        <div class="mb-6">
            <h2 class="text-4xl sm:text-5xl font-bold text-teal-700 ">
                {{ $grupo->fk_cuatrimestre }}{{ $grupo->nombre }}{{ $grupo->carrera->abreviatura }} {{ $grupo->año }}
            </h2>
            <p class="text-gray-500 text-lg">
                Lista de alumnos del grupo
            </p>
        </div>

        @if ($alumnos->isEmpty())
            <div class="p-6 bg-white shadow rounded-xl text-center text-gray-500 italic">
                No hay alumnos registrados en este grupo.
            </div>
        @endif

        @if (!$alumnos->isEmpty())
            <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
                <div class="h-150 sm:max-h-screen overflow-auto pr-1 sm:pr-0">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-teal-50 sticky top-0">
                            <tr class="hidden sm:table-row">
                                <th class="px-4 py-3 text-left font-semibold text-teal-700 uppercase tracking-wider">
                                    Alumno
                                </th>
                                <th class="px-4 py-3 text-left font-semibold text-teal-700 uppercase tracking-wider">
                                    Matrícula
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-semibold text-teal-700 uppercase tracking-wider hidden sm:table-cell">
                                    Calificación
                                </th>
                                <th class="px-4 py-3 text-center font-semibold text-teal-700 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($alumnos as $alumnoGrupo)
                                @php
                                    $alumno = $alumnoGrupo->alumno;
                                    $usuario = $alumno->usuario;
                                    $cal = $calificaciones->firstWhere('fk_alumno', $alumno->pk_alumno);
                                @endphp
                                <tr
                                    class="flex flex-col sm:table-row border sm:border-none border-gray-300 rounded-lg sm:rounded-none shadow sm:shadow-none mt-2 sm:mt-0 mb-4 sm:mb-0 ml-2 sm:ml-0 p-2 sm:p-0  hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <h3 class="text-lg sm:text-base text-teal-700 sm:text-black">{{ $usuario->nombres }}
                                            {{ $usuario->ap_paterno }} {{ $usuario->ap_materno ?? '' }}</h3>
                                        <span class="sm:hidden">
                                            <p class="font-medium">Matricula: <span
                                                    class="text-gray-600 sm:text-black">{{ $usuario->matricula }}</span></p>
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-gray-700 hidden sm:table-cell">
                                        {{ $usuario->matricula }}
                                    </td>

                                    <td class="px-4 py-3 hidden sm:table-cell">
                                        <input type="number" min="0" max="10" step="0.1"
                                            data-alumno="{{ $alumno->pk_alumno }}"
                                            data-materia="{{ $materia->pk_materia }}"
                                            value="{{ $cal->calificacion ?? '' }}"
                                            class="cal-input w-40 text-lg font-semibold border-2 border-teal-400 bg-teal-50 rounded-xl shadow-sm px-3 py-2"
                                            placeholder="0" />
                                    </td>
                                    <td colspan="4" class="px-4 pb-4 sm:hidden">
                                        <label class="text-sm font-medium text-gray-700">Calificación:</label>
                                        <input type="number" min="0" max="10" step="0.1"
                                            value="{{ $cal->calificacion ?? '' }}" data-alumno="{{ $alumno->pk_alumno }}"
                                            data-materia="{{ $materia->pk_materia }}"
                                            class="cal-input mt-1 w-full text-lg font-semibold border-2 border-teal-400 bg-teal-50
                                        rounded-xl shadow-sm px-3 py-2
                                        focus:ring-2 focus:ring-teal-600 focus:border-teal-600 transition"
                                            placeholder="Ej. 8.5" />
                                    </td>

                                    <td class="fle px-4 py-3 text-center">
                                        <a href="{{ route('docente.alumno-actividades', ['alumno' => $alumno->pk_alumno, 'grupo' => $grupo->pk_grupo]) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-semibold
                                           bg-teal-600 text-white rounded-lg shadow
                                           hover:bg-teal-700 transition">
                                            Ver actividades
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <script>
        function showSnackbar(message, type = "success") {
            const sb = document.getElementById("snackbar");

            sb.className = "";
            sb.innerText = message;

            if (type === "success") {
                sb.classList.add("sb-success");
            } else {
                sb.classList.add("sb-error");
            }

            sb.style.animation = "slideUp 0.5s forwards";

            setTimeout(() => {
                sb.style.animation = "slideDown 0.5s forwards";
            }, 2500);
        }

        let debounceTimers = {};

        document.querySelectorAll('.cal-input').forEach(input => {

            input.addEventListener('input', function() {

                let alumno = this.dataset.alumno;
                let materia = this.dataset.materia;
                let valor = parseFloat(this.value);

                if (valor < 0) this.value = 0;
                if (valor > 10) this.value = 10;

                clearTimeout(debounceTimers[alumno]);

                debounceTimers[alumno] = setTimeout(() => {

                    fetch("{{ route('docente.guardar.calificacion') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                fk_alumno: alumno,
                                fk_materia: materia,
                                calificacion: this.value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            showSnackbar(data.message, data.status === "success" ? "success" :
                                "error");

                            input.classList.add('bg-green-100', 'border-green-400');
                            setTimeout(() => {
                                input.classList.remove('bg-green-100',
                                    'border-green-400');
                            }, 800);
                        })
                        .catch(err => {
                            console.error(err);
                            showSnackbar("Error al guardar", "error");
                        });

                }, 600);
            });
        });
    </script>


@endsection
