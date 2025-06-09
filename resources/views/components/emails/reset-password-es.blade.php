<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <img src="{{ asset('main_logo.png') }}" alt="Ciano Logo" style="max-width: 100px; margin-bottom: 20px;">
        
        <h2 style="color: #0693e3;">Recuperación de Contraseña</h2>
        
        <p style="color: #000000;">Hola,</p>
        
        <p style="color: #000000;">Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
        
        <p style="color: #000000;">Para restablecer tu contraseña, haz clic en el botón de abajo:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('auth.password.reset', ['token' => $token, 'language' => 'es']) }}" 
               style="background-color: #0693e3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Restablecer Contraseña
            </a>
        </div>
        
        <p style="color: #000000;">Si no solicitaste restablecer tu contraseña, puedes ignorar este correo electrónico.</p>
        
        <p style="color: #000000;">Este enlace de restablecimiento expirará en 60 minutos.</p>
        
        <p style="color: #000000;">Gracias,<br>El equipo de Ciano</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        
        <p style="color: #666; font-size: 12px;">
            © 2025 CIANO. Todos los derechos reservados.
        </p>
    </div>
</body>
</html> 