<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_NAME', 'Application')}}</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}"/>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <noscript>
        <p>JavaScript is required for this app.</p>
    </noscript>

    <div id="root"></div>

    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>