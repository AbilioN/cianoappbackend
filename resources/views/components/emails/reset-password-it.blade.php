<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupero Password</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        {{-- <img src="{{ config('app.url') }}/main_logo.png" alt="Ciano Logo" style="max-width: 200px; margin-bottom: 20px;"> --}}
        <img src="{{ asset('main_logo.png') }}" alt="Ciano Logo" style="max-width: 100px; margin-bottom: 20px;">
        
        <h2 style="color: #0693e3;">Recupero Password</h2>
        
        <p>Ciao,</p>
        
        <p>Abbiamo ricevuto una richiesta di reimpostazione della password per il tuo account.</p>
        
        <p>Per reimpostare la tua password, clicca sul pulsante qui sotto:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('auth.password.reset', ['token' => $token, 'language' => 'it']) }}" 
               style="background-color: #0693e3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Reimposta Password
            </a>
        </div>
        
        <p>Se non hai richiesto il recupero della password, puoi ignorare questa email.</p>
        
        <p>Questo link di reimpostazione della password scadrà tra 60 minuti.</p>
        
        <p>Grazie,<br>Il team Ciano</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        
        <p style="color: #666; font-size: 12px;">
            © 2025 CIANO. Tutti i diritti riservati.
        </p>
    </div>
</body>
</html> 