<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <base href="{{ config('app.url') }}">
        <title>{{ config('app.name') }}</title>
        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
        ])
    </head>
    <body>
        <div id="app" class="container">
            <router-view></router-view>
        </div>
    </body>
</html>
