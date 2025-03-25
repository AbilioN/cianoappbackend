<body>

    <style>
        .flex {
            display: flex
            flex-direction: column;
            
        }
        .divContainer {
            padding: 10px;
        }
        .link {
            padding: 10px 25px;
            margin: 10px auto;
            background-color: #0693e3;
            position: relative;
            text-decoration: none;
            color: #333333;
            border-radius: 30px;

        }
        img {
            max-width: 80%;
            margin: 15px auto;
        }
    </style>

    <div style="width: 100%; min-height: 200px; text-align: center;">
        <div class="flex">
            <img src="https://app.ciano.pt/main_logo.png" alt="">
            <h3>Redefinição de senha</h3>

            <p>Você solicitou a redefinição de senha. Clique no botão abaixo para redefinir:</p>
            <div class="divContainer">
                <a href="{{ url('/reset/'.$token) }}" class="link">Redefinir Senha</a>
            </div>
 
            <p style="margin-top: 20px;">Se você não solicitou, ignore este e-mail.</p>
        </div>
    </div>
</body>