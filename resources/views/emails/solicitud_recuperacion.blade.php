<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <title>Solicitud de Restauración de Contraseña</title>
  </head>
  <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
      <tr>
        <td>
          <table align="center" width="100%" max-width="600px" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: auto;">
            <tr>
              <td style="padding: 30px; text-align: center; background-color: #2E887B;">
                <h2 style="color: #ffffff; margin: 0;">Language Coordination</h2>
              </td>
            </tr>
            <tr>
              <td style="padding: 30px;">
                <p style="font-size: 16px; color: #333333;">Hola, Coordinador/a</p>
                <p style="font-size: 16px; color: #333333;">El siguiente usuario ha solicitado una restauración de contraseña:</p>

                <ul style="font-size: 16px; color: #333333; margin-top:7px; padding-left: 20px; margin-bottom: 30px;">
                  <li><strong>Matrícula:</strong> {{ $usuarioMatricula }}</li>
                  <li><strong>Nombre:</strong> {{ $usuarioNombre }}</li>
                  @if(isset($usuarioCarrera))
                    <li><strong>Carrera:</strong> {{ $usuarioCarrera }}</li>
                  @endif
                  @if(isset($usuarioGrupo))
                    <li><strong>Grupo:</strong> {{ $usuarioGrupo }}</li>
                  @endif
                </ul>

                <p style="font-size: 16px; color: #333333;">Para autorizar esta solicitud, por favor haga clic en el siguiente botón:</p>

                <p style="text-align: center; margin: 30px 0;">
                  <a
                    href="{{ $urlRestablecer }}"
                    style="padding: 12px 24px; background-color: #2E887B; color: #ffffff;
                    text-decoration: none; font-weight: bold; border-radius: 5px;"
                  >
                    Autorizar Restauración
                  </a>
                </p>

                <p style="font-size: 14px; color: #555555;">Si no reconoce esta solicitud, puede ignorar este mensaje. No se realizarán cambios sin su autorización.</p>

              </td>
            </tr>
            <tr>
              <td style="text-align: center; font-size: 12px; color: #aaaaaa; padding: 20px; background-color: #f9fafb;">
                Este es un mensaje automático. Por favor, no responda a este correo.
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
