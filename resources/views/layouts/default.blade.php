<!doctype html>
<html>
<head>
    <!-- my head section goes here -->
    <!-- my css and js goes here -->
    <title>@yield('title')</title>
    <style>

        body {
            margin: 0;
            padding : 0;

        }

        div.container {

            height: 100vh;
            display: flex;
            flex-direction: column;


        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;

        }

        li {
            float: left;
        }

        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover {
            background-color: #111;
        }
        header, footer {
            padding: 1em;
            color: white;
            background-color: black;
            clear: left;
            text-align: center;

        }


        .contents{


            border-left: 1px solid gray;
            padding: 1em;
            overflow: hidden;
            flex:1;
        }

        h2{
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <header> @include('layouts.header') </header>
    <div class="contents"> @yield('content') </div>
    <footer> @include('layouts.footer') </footer>
</div>
</body>
</html>
