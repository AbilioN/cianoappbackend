<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Ciano - Passwort zurücksetzen</title>

</head>
<body>
    @if (session('success'))
        <div class="bg-green-100 text-green-700 text-center p-4 mb-4 container mx-auto absolute left-0 right-0 top-5">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="w-full min-h-[100vh] flex align-items-center justify-center">
        <div class="w-96 py-7 my-auto mx-auto text-[#0693e3] bg-cyan-200 border border-gray shadow-md rounded-md flex flex-col text-center justify-content-center">
            <img src="{{ asset('main_logo.png') }}" alt="logo" class="max-w-[70%] self-center">
            
            <h2 class="text-lg mt-6">Passwort zurücksetzen</h2>
            <form action="{{ route('password.update') }}" method="POST" class="mx-auto">
                @csrf
                
                <input type="hidden" required name="token" value="{{ $token }}">
                <input type="hidden" name="language" value="de">

                <div class="mt-5">
                    <small class="block text-start pl-1">Passwort: @if ($errors->has('password'))<span class="text-red-500 text-xs mt-1">*{{ $errors->first('password') }}</span>@endif</small>
                    <input type="password" required name="password" class="rounded-[4px] w-full shadow-sm border border-gray-300 focus:outline-[#0693e3]">
                </div>
                
                <div class="mt-5">
                    <small class="block text-start pl-1">Passwort bestätigen:</small>
                    <input type="password" required name="password_confirmation" class="rounded-[4px] w-full shadow-sm border border-gray-300 ">
                </div>

                <button type="submit" class="bg-gray-200 rounded-2xl mt-5 px-5 py-1 border border-gray-500 shadow-md">bestätigen</button>

            </form>
            @if($errors->has('error'))
                <small class="text-red-400 mt-3">{{ $errors->first('error') }}</small>
            @endif
            
            <span class="text-wrap text-xs max-w-full mt-7">© 2025 CIANO. Alle Rechte vorbehalten.</span>
        </div>
    </div>
</body>
</html> 