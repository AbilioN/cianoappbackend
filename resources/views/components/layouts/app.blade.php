<!DOCTYPE html>
<html lang="en">

<head>
<!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MC3SX8XM');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ciano</title>
    <link rel="stylesheet" href="{{ URL::asset('build/assets/app-DVq7K88X.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <link rel="shortcut icon" sizes="114x114" href="favicon.ico">
    <style>

label[title="Next"] { right: 0; }

input:checked + li > #img-inner {
    opacity: 1;

    transform: scale(1);
    -moz-transform: scale(1);
    -webkit-transform: scale(1);

    transition: opacity 1s ease-in-out;
    -moz-transition: opacity 1s ease-in-out;
    -webkit-transition: opacity 1s ease-in-out;
}

input:checked + li > label { display: block; } */

    </style>



<script type="text/javascript">
    var _tip = _tip || [];
    (function(d,s,id){
        var js, tjs = d.getElementsByTagName(s)[0];
        if(d.getElementById(id)) { return; }
        js = d.createElement(s); js.id = id;
        js.async = true;
        js.src = d.location.protocol + '//app.truconversion.com/ti-js/35497/2156e.js';
        tjs.parentNode.insertBefore(js, tjs);
    }(document, 'script', 'ti-js'));
</script>

</head>

<body class="flex flex-col h-screen">
    <div class="flex-grow">
        @if (Auth::user() !== null)

            {{-- @php
                dd(Auth::user()->role()->first()->name , Route::currentRouteName());
            @endphp --}}
            @if (Auth::user()->role()->first()->name !== 'Admin' )
                @if (Route::current()->uri !== 'login')

                    @include('components.layouts.header')
                        <div>
                            {{ $slot }}
                        </div>
                    @include('components.layouts.footer')

                @else
                    <div>
                        {{ $slot }}
                    </div>
                @endif
                @else

                    <div >
                        @if (Route::current()->uri !== 'login')
                            {{-- @include('components.admin.sidebarlayout') --}}
                            @include('components.admin.sidebarlayout', ['slot' => $slot])
                        @endif
                        {{-- <div class="flex-grow ml-64 px-12">
                            {{ $slot }}
                        </div> --}}
                    </div>
                @endif
            @else
                @if (Route::current()->uri !== 'login')
                @include('components.layouts.header')
                    <div>
                        {{ $slot }}
                    </div>
                @include('components.layouts.footer')
                @else
                    <div>
                        {{ $slot }}
                    </div>
                @endif
            @endif

        @livewireScripts
    </div>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MC3SX8XM"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>


</html>
