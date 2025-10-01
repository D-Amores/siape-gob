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
    <button id="btnPersonnelApi"> Probar API</button>
</body>
<script>
    const csrfToken = '{{ csrf_token() }}';
    var urlTest = window.location.origin + '/personnel';
</script>
<script src="{{ asset('test/test.js') }}"></script>
</html>
