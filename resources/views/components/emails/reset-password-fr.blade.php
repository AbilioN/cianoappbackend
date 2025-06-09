<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <img src="{{ asset('main_logo.png') }}" alt="Ciano Logo" style="max-width: 200px; margin-bottom: 20px;">
        
        <h2 style="color: #0693e3;">Réinitialisation du mot de passe</h2>
        
        <p style="color: #000000;">Bonjour,</p>
        
        <p style="color: #000000;">Nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.</p>
        
        <p style="color: #000000;">Pour réinitialiser votre mot de passe, cliquez sur le bouton ci-dessous :</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('auth.password.reset', ['token' => $token, 'language' => 'fr']) }}" 
               style="background-color: #0693e3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Réinitialiser le mot de passe
            </a>
        </div>
        
        <p style="color: #000000;">Si vous n'avez pas demandé la réinitialisation du mot de passe, vous pouvez ignorer cet e-mail.</p>
        
        <p style="color: #000000;">Ce lien de réinitialisation expirera dans 60 minutes.</p>
        
        <p style="color: #000000;">Merci,<br>L'équipe Ciano</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        
        <p style="color: #666; font-size: 12px;">
            © 2025 CIANO. Tous droits réservés.
        </p>
    </div>
</body>
</html> 