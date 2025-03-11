<body>

    <style>
        .flex {
            display: flex
            flex-direction: column;
            

        }
        .btn {
            border: 1px solid gray;
            box-shadow: 5px 5px 10px gray;
            background-color: white;
            padding: 5px 10px;
            text-decoration: none;
            text-align: center
        }
    </style>

    <div style="width: 100%; min-height: 200px; text-align: center;">
        <div class="flex ">
            <h1>Ciano</h1>
            <h2>Redefinição de senha</h2>

            <p>Você solicitou a redefinição de senha. Clique no link abaixo para redefinir:</p>
            <a href="{{ url('/reset/'.$token) }}">Redefinir Senha</a>
 
            <p>Se você não solicitou, ignore este e-mail.</p>
        </div>
    </div>
</body>