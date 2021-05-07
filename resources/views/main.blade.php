<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


        <!-- Styles -->
        <link href="{{ mix('assets/css/app.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app"></div>

        <script src="{{ mix('assets/js/manifest.js') }}"></script>
        <script src="{{ mix('assets/js/vendor.js') }}"></script>
        <script src="{{ mix('assets/js/app.js') }}"></script>
    </body>
</html>
