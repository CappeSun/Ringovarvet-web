<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ny Produkt - Ringövarvet</title>
    @vite(['resources/js/app.js'])
    <script type="text/javascript">
        const csrf_token = '{{ csrf_token() }}';
    </script>
</head>
<body>
    <div class="app" id="app">
        <create-product-mobile/>
    </div>
</body>
</html>