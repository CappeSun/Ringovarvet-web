<?php

use App\Models\Product;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Subcategory;
use App\Models\ProductProperty;

$product = Product::where('id', $with['id'])->first();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Artikel - Ringövarvet</title>
	@vite(['resources/js/app.js'])
	<link rel="stylesheet" type="text/css" href="/css/productPage.css">
	<link rel="script" type="text/javascript" href="/js/productPage.js">
</head>
<body>
	<?php if (!$product) { ?>
		<h2>Artikeln finns inte</h2>
	<?php } else { ?>
		<h3>Subkategori</h3>
		<p><?= Subcategory::where('id', $product->subcategoryId)->first()->name; ?></p>
		<h3>Egenskaper</h3>
		<div>
			 <?php foreach (ProductProperty::where('productId', $product->id)->get() as $value) {
			 	$property = Property::where('id', $value->propertyId)->first();
			 	?>
			 	<p><?= $property->name; ?>: <?= $value['value'].' '.($property->unitId == 1 ? '' : Unit::where('id', $property->unitId)->first()->name); ?></p>
			 <?php } ?>
		</div>
		<h3>Bilder</h3>
		<div class="imageCont">
			<?php foreach (array_slice(scandir('productImages/'.$with['id']), 2) as $value) { ?>
				<img src="/productImages/<?= $with['id'].'/'.$value; ?>">
			<?php } ?>
		</div>

		<?php if ($with['user']) { ?>
			<div class="adminMenu">
				<button id="adminMenuRemove">Ta bort</button>
				<button id="adminMenuRemoveMinus">-</button>
				<span id="adminMenuRemoveCount">1</span>
				<button id="adminMenuRemovePlus">+</button>
				<span id="adminMenuInStorage">/ <?= $product->count ?></span>
			</div>
			<div class="adminMenuSpacer"></div>
		<?php } ?>
	<!-- TEMP FOR RELOAD -->
	<div class="app" id="app">
	</div>
	<?php } ?>
</body>
</html>