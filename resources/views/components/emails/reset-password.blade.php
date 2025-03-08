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
            <a href="{{ url('/reset-password/'.$token) }}">Redefinir Senha</a>
            
            <div style="margin: 10px auto; padding: 15px 25px; background-color:rgba(100, 100, 100, 0.5); color: grey;">
                <h2>{{$token}}</h2>
                {{-- <a href="{{url()}}" target="_blank" style="text-decoration: none; color:grey; font-size: 2em; font-weight: bold;">Recuperar senha</a> --}}
            </div>
            <p>Se você não solicitou, ignore este e-mail.</p>
        </div>
    </div>
</body>