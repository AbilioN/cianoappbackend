<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha - Ciano</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #0693e3;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 30px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://app.ciano.pt/main_logo.png" alt="Ciano Logo" class="logo">
        </div>
        <div class="content">
            <h2>Redefinição de Senha</h2>
            <p>Olá,</p>
            <p>Recebemos uma solicitação para redefinir a senha da sua conta Ciano. Se você fez esta solicitação, clique no botão abaixo para criar uma nova senha:</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/reset/'.$token) }}" class="button">Redefinir Senha</a>
            </div>

            <p>Este link é válido por 60 minutos.</p>
            
            <p>Se você não solicitou a redefinição de senha, por favor ignore este e-mail ou entre em contato com nosso suporte se tiver alguma dúvida.</p>
            
            <p>Atenciosamente,<br>Equipe Ciano</p>
        </div>
        <div class="footer">
            <p>Este é um e-mail automático, por favor não responda.</p>
            <p>&copy; {{ date('Y') }} Ciano. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>