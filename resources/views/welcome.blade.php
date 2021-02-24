<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Video Chat</title>
        <meta charset="UTF-8" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="bg-gray-100">
        <div id="app">
            <video-chat></video-chat>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
