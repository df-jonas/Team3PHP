<!doctype html>
<html>
<head>
    <!-- my head section goes here -->
    <!-- my css and js goes here -->

    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">

    <title>@yield('title')</title>

</head>
<body>
    <header> @include('layouts.header') </header>
    <div class="contents"> @yield('content') </div>
    <footer> @include('layouts.footer') </footer>
</body>
</html>
