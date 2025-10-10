<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SolicitudRecuperacion extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario; // Variable pública disponible en la vista

    /**
     * Crear una nueva instancia del Mailable
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Construir el mensaje
     */
    public function build()
    {
        $url = route('recuperar.restablecer', $this->usuario->pk_usuario); // enlace de restablecimiento

        return $this->subject('Solicitud de recuperación de contraseña')
                    ->view('emails.solicitud_recuperacion') // vista del correo
                    ->with([
                        'usuarioNombre' => $this->usuario->nombres . ' ' . $this->usuario->ap_paterno,
                        'usuarioMatricula' => $this->usuario->matricula,
                        'urlRestablecer' => $url,
                    ]);
    }
}
