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
	<title>Artikel - Ringövarvets Lager</title>
	<link rel="stylesheet" type="text/css" href="/css/productPage.css">
    <script type="text/javascript">
        const csrf_token = '{{ csrf_token() }}';
        <?= $product ? 'const id = '.$product->id : null; ?>;
    </script>
</head>
<body>
	<?php if (!$product) { ?>
		<h2>Artikeln hittades inte</h2>
	<?php } else { ?>
		<div class="productCont">
			<div class="infoCont">
				<h3 class="subcategory"><?= Subcategory::where('id', $product->subcategoryId)->first()->name; ?></h3>
				<div>
					 <?php foreach (ProductProperty::where('productId', $product->id)->get() as $value) {
					 	$property = Property::where('id', $value->propertyId)->first();
					 	?>
					 	<p><span><?= $property->name; ?></span><span><?= $value['value'].' '.($property->unitId == 1 ? '' : Unit::where('id', $property->unitId)->first()->name); ?></span></p>
					 <?php } ?>
				</div>
				<h4 class="condition"><?= ['Måste repareras', 'Väldigt skadat', 'Något skadat', 'Använt', 'Nyskick'][$product->condition - 1]; ?></h4>
				<h4 class="count"><?= $product->count; ?> i lager</h4>
				<h4 class="cost"><?= $product->cost; ?> kr</h4>
			</div>
			<div class="imageCont">
				<?php foreach (array_slice(scandir('productImages/'.$with['id']), 2) as $value) { ?>
					<img src="/productImages/<?= $with['id'].'/'.$value; ?>">
				<?php } ?>
			</div>
		</div>

		<?php if ($with['user']) { ?>
			<div class="adminMenu">
				<button id="adminMenuRemove">Ta bort</button>
				<button id="adminMenuRemoveMinus">-</button>
				<input id="adminMenuRemoveCount" type="text" inputmode="numeric" value="0">
				<button id="adminMenuRemovePlus">+</button>
				<span id="adminMenuInStorage">/ <?= $product->count ?></span>
			</div>
			<div class="adminMenuSpacer"></div>
			<script src="/js/productPage.js"></script>
		<?php } ?>
	<?php } ?>
</body>
</html>