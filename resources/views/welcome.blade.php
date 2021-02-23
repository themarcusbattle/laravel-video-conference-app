<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel Video Chat</title>
        <!-- <link href="https://fonts.googleapis.com/css2?family=Oxygen&display=swap" rel="stylesheet"> -->
        <!-- <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet"> -->

        <style>
            * {
                font-family: 'Oxygen', sans-serif;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <video-chat></video-chat>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
