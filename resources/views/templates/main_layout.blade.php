<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css')}}">
</head>
<body>
    @yield('content')

    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js')}}"></script>
</body>
</html>