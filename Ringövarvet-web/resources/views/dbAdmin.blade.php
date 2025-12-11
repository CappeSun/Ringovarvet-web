<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Databas - Ringövarvets Lager</title>
	@vite(['resources/js/app.js'])
	<script type="text/javascript">
		const csrf_token = '{{ csrf_token() }}';
	</script>
</head>
<body>
	<div class="app" id="app">
		<div class="SideSelect">
			<side-select/>
		</div>
		<div class="AdminMenu">
			<admin-menu/>
		</div>
		<div class="Qr">
			<qr/>
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
		flex: 0 0 200px;
	}
	.AdminMenu{
		flex-grow: 1;
	}
	.Qr{
		display: none;
	}
	@media print{
		.SideSelect, .AdminMenu{
			display: none;
		}
		.Qr{
			display: block;
		}
	}
</style>
</html>