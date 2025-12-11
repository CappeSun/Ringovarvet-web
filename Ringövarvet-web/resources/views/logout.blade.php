<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logga Ut - Ringövarvets Lager</title>
</head>
<body>

<form action="/logout" method="POST">
    <button>Logga ut</button>
    <input type="hidden" name="_token" value="{{csrf_token()}}">
</form>
</body>
<style type="text/css">
    button{
        margin: 25px 0 0;
        display: block;
    }
</style>
</html>