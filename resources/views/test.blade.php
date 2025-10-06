<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <button id="btnStore"> Crear</button>
    <button id="btnUpdate"> Actualizar</button>
    <button id="btnDelete"> Eliminar</button>
    <button id="btnApi"> Probar API</button>
    <button id="btnLogin"> Probar Iniciar Sesión</button>
    <button id="btnLogOut"> Probar Cerrar Sesión</button>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>

</body>
<script>
    const csrfToken = '{{ csrf_token() }}';
    var urlTest = window.location.origin + '/personnel';
    var vURI = 'http://localhost/';
</script>
<script src="{{ asset('test/test.js') }}"></script>
</html>
