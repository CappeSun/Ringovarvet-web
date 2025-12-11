<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logga In - Ringövarvets Lager</title>
</head>
<body>

<form action="/login" method="POST">
    <h3>Namn</h3>
    <input type="text" name="name">
    <h3>Lösenord</h3>
    <input type="text" name="password">
    <button>Logga in</button>
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