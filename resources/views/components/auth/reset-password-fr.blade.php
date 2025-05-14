<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Ciano - Réinitialisation du mot de passe</title>

</head>
<body>
    {{-- @if (session('success'))
        <div class="bg-green-100 text-green-700 text-center p-4 mb-4 container mx-auto absolute left-0 right-0 top-5">
            {{ session('success') }}
        </div>
    @endif --}}
    
    @if (session('success') || session('error') || $errors->any())
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-[90%] max-w-md">
            <div class="flex items-center justify-between px-4 py-3 rounded-md shadow-md
                @if(session('success')) bg-green-100 text-green-800 border border-green-300 @else bg-red-100 text-red-800 border border-red-300 @endif">
                
                <span class="text-sm font-medium">
                    {{ session('success') ?? session('error') ?? $errors->first() }}
                </span>

                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-xl leading-none">&times;</button>
            </div>
        </div>
    @endif

    <div class="w-full min-h-[100vh] flex align-items-center justify-center">
        <div class="w-96 py-7 my-auto mx-auto text-[#0693e3] bg-cyan-200 border border-gray shadow-md rounded-md flex flex-col text-center justify-content-center">
            <img src="{{ asset('main_logo.png') }}" alt="logo" class="max-w-[55%] self-center">
            
            <h2 class="text-lg mt-6">Réinitialisation du mot de passe</h2>
            <form action="{{ route('auth.password.update') }}" method="POST" class="mx-auto">
                @csrf
                
                <input type="hidden" required name="token" value="{{ $token }}">
                <input type="hidden" name="language" value="fr">

                <div class="mt-5">
                    <small class="block text-start pl-1">mot de passe: @if ($errors->has('password'))<span class="text-red-500 text-xs mt-1">*{{ $errors->first('password') }}</span>@endif</small>
                    <input type="password" required name="password" class="rounded-[4px] w-full shadow-sm border border-gray-300 focus:outline-[#0693e3]">
                </div>
                
                <div class="mt-5">
                    <small class="block text-start pl-1">confirmer le mot de passe:</small>
                    <input type="password" required name="password_confirmation" class="rounded-[4px] w-full shadow-sm border border-gray-300 ">
                </div>

                <button type="submit" class="bg-gray-200 rounded-2xl mt-5 px-5 py-1 border border-gray-500 shadow-md">confirmer</button>

            </form>
            @if($errors->has('error'))
                <small class="text-red-400 mt-3">{{ $errors->first('error') }}</small>
            @endif
            
            <span class="text-wrap text-xs max-w-full mt-7">© 2025 CIANO. Tous droits réservés.</span>
        </div>
    </div>

    <div id="loader" class="fixed inset-0 bg-white bg-opacity-70 flex items-center justify-center hidden z-50">
        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const loader = document.getElementById('loader');
            const button = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function () {
                loader.classList.remove('hidden');
                button.disabled = true;
                button.textContent = 'Envoi...';
            });
        });
        
        setTimeout(() => {
            const flash = document.querySelector('[role="alert"]') || document.querySelector('.fixed.top-4');
            if (flash) flash.remove();
        }, 5000); // 5 segundos
    </script>

</body>
</html> 