<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulario CSS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('assets/password/css/estilos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/password/css/normalize.css')}}">
</head>

<body>

    <div class="contenedor-formulario contenedor">
        <div class="imagen-formulario">
            
        </div>
        
        @yield('content')
    </div>

</body>

</html>