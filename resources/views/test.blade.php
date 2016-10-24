<!DOCTYPE html>
<html>
<head>
    <title>Laravel - test</title>
</head>
<style>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
     html, body {
         height: 100%;
     }

    body {
        margin: 0;
        padding: 0;
        width: 100%;
        font-weight: 100;
        font-family: 'Lato';
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .content {
    }
</style>
<body>
<div class="container">
    <div class="content">
        <div class="title">test 5</div>

        <form method="POST" action="/staff">
            {!! csrf_field() !!}

            <div>
                <label for="id">ID: </label>
                <input type="text" name="id" value="25">
            </div>

            <div>
                <button type="submit">Submit</button>
            </div>
        </form>

    </div>
</div>
</body>
</html>
