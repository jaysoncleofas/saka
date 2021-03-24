<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('yourconfig.resort')->name }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}"  type='image/x-icon'>

    {{-- Libraries --}}
    {{-- <link href="{{ asset('select2/dist/css/select2.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('stisla/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('stisla/assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fullcalendar-5.5.1/lib/main.css') }}" rel="stylesheet">
    <style>
        .is-size-15 {
            line-height: 2;
        }
        @media screen and (max-width: 479px) {
            .main-content {
                padding-left: 0px;
                padding-right: 0px;
            }
            #msform fieldset .form-card{
                padding: 0px !important;
                margin: 0px !important;
            }
            .main-wrapper-1 .section .section-header {
                margin-left: 0px;
                margin-right: 0px;
            }
        }
    </style>
    @yield('style')
</head>
<body>
    <div id="app">
        