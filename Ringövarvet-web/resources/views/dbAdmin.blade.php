<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Databas - Ringövarvet</title>
    @vite(['resources/js/dbAdmin.js'])
</head>
<body>
    <div class="app" id="app">
        <div class="SideSelect">
            <side-select/>
        </div>
        <div class="AdminMenu">
            <admin-menu/>
        </div>
    </div>
</body>
<style type="text/css">
    .app{
        display: flex;
        flex-direction: row;
    }
    .SideSelect{
        margin: 0 20px 0 0;
    }
</style>
</html>