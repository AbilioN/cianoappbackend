<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <img src="https://app.ciano.pt/main_logo.png" alt="Ciano Logo" style="max-width: 100px; margin-bottom: 20px;">

        <h2 style="color: #0693e3;">Passwort zurücksetzen</h2>
        
        <p>Hallo,</p>
        
        <p>Wir haben eine Anfrage zur Zurücksetzung Ihres Passworts erhalten.</p>
        
        <p>Um Ihr Passwort zurückzusetzen, klicken Sie bitte auf die Schaltfläche unten:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('auth.password.reset', ['token' => $token, 'language' => 'de']) }}" 
               style="background-color: #0693e3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Passwort zurücksetzen
            </a>
        </div>
        
        <p>Wenn Sie keine Passwortzurücksetzung angefordert haben, können Sie diese E-Mail ignorieren.</p>
        
        <p>Dieser Link zur Passwortzurücksetzung läuft in 60 Minuten ab.</p>
        
        <p>Vielen Dank,<br>Ihr Ciano-Team</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        
        <p style="color: #666; font-size: 12px;">
            © 2025 CIANO. Alle Rechte vorbehalten.
        </p>
    </div>
</body>
</html> 