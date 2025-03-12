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
            background-color: rgb(60, 27, 245);
            position: relative;
            text-decoration: none;
            color: #333333;
            border-radius: 30px;

        }
    </style>

    <div style="width: 100%; min-height: 200px; text-align: center;">
        <div class="flex ">
            <h1>Ciano</h1>
            <h3>Redefinição de senha</h3>

            <p>Você solicitou a redefinição de senha. Clique no link abaixo para redefinir:</p>
            <div class="divContainer">
                <a href="{{ url('/reset/'.$token) }}" class="link">Redefinir Senha</a>
            </div>
 
            <p>Se você não solicitou, ignore este e-mail.</p>
        </div>
    </div>
</body>