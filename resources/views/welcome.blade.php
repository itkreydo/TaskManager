<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TaskManager APP">
    <meta name="apple-mobile-web-app-title" content="TaskManager">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#444" />

    <title>TaskManager</title>

    <link rel="shortcut icon" href="{{asset('img/icons/favicon-32x32.ico')}}">
    <link rel="apple-touch-icon" href="{{asset('img/icons/apple-touch-icon.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('img/icons/apple-touch-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('img/icons/apple-touch-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/icons/apple-touch-icon-180x180.png')}}">
    <link href="{{asset('img/icons/splashscreen/splash-screen-iphone-11.jpg')}}" rel="apple-touch-startup-image" />

    <link rel="manifest" href="{{asset('manifest.json')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/main.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Styles -->
    <style>

    </style>
</head>
<body>
<div id="app">
    <App></App>
</div>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
