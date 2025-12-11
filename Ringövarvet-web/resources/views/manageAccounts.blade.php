<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hantera Konton - Ringövarvets Lager</title>
    @vite(['resources/js/app.js'])
    <script type="text/javascript">
        const csrf_token = '{{ csrf_token() }}';
    </script>
</head>
<body>
    <div class="app" id="app">
        <account-manager/>
    </div>
</body>
</html>