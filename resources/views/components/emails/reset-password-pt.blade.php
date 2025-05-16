<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <img src="{{ config('app.url') }}/storage/main_logo.png" alt="Ciano Logo" style="max-width: 100px; margin-bottom: 20px;">
        
        <h2 style="color: #0693e3;">Recuperação de Senha</h2>
        
        <p>Olá,</p>
        
        <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
        
        <p>Para redefinir sua senha, clique no botão abaixo:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('auth.password.reset', ['token' => $token, 'language' => 'pt']) }}" 
               style="background-color: #0693e3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Redefinir Senha
            </a>
        </div>
        
        <p>Se você não solicitou a redefinição de senha, você pode ignorar este e-mail.</p>
        
        <p>Este link de redefinição expirará em 60 minutos.</p>
        
        <p>Obrigado,<br>Equipe Ciano</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        
        <p style="color: #666; font-size: 12px;">
            © 2025 CIANO. Todos os direitos reservados.
        </p>
    </div>
</body>
</html> 