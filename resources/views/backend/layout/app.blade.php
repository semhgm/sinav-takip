<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Otika - Admin Dashboard Template</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{asset('otika/assets/css/app.min.css')}}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('otika/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('otika/assets/css/components.css')}}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{asset('otika/assets/css/custom.css')}}">
    <link rel='shortcut icon' type='image/x-icon' href='{{asset('otika/assets/img/favicon.ico')}}' />
</head>

<body>
<div class="loader"></div>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        @include('backend.inc.header')
        @include('backend.inc.sidebar')
        <!-- Main Content -->
        @yield('content')
        @include('backend.inc.footer')
    </div>
</div>
@include('backend.partials.scripts')
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>
