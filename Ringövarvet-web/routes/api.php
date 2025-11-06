<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Property;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryProperty;
use App\Models\ProductProperty;
/*
Route::post('/chatConnect/{channel}', function (Request $request, $channel){
	//file_put_contents('/Users/cappe/drclog.txt', $request);
	$user = $request->user();
	// query channel data for user
	$userBan = 0;       // Temp
	$userPoints = 0;    //

	return ['id' => $user['id'], 'name' => $user['name'], 'color' => $user['color'], 'badges' => $user['badges'], 'ban' => $userBan, 'points' => $userPoints];
})->middleware('auth:sanctum');

Route::post('/streamStart', function (Request $request){
	//file_put_contents('/Users/cappe/drclog.txt', $request);
	if ($request->user()->tokenCan('STREAM'))
		return ['name' => $request->user()['name']];
	
	return response(403);
})->middleware('auth:sanctum');
*/

// ##### CREATE #####

Route::post('/admin/create/product', function (Request $request) {
	$request->user();       // Auth later
	return response(null, 501);
});

Route::post('/admin/create/unit', function (Request $request) {
	$request->user();       // Auth later
	new Unit([
		'name' => $request->post('name')
	])->save();
	return response('Unit ['.$request->post('name').'] was created', 200);
});

Route::post('/admin/create/property', function (Request $request) {
	$request->user();       // Auth later
	new Property([
		'name' => $request->post('name'),
		'unitId' => $request->post('unitId')
	])->save();
	return response('Property ['.$request->post('name').'] with unit ['.Unit::where('id',$request->post('unitId'))->first()->name.'] was created', 200);
});

Route::post('/admin/create/category', function (Request $request) {
	$request->user();       // Auth later
	new Category([
		'name' => $request->post('name')
	])->save();
	return response('Category ['.$request->post('name').'] was created', 200);
});

Route::post('/admin/create/subcategory', function (Request $request) {
	$request->user();       // Auth later

	foreach ($request->post('propertyIds') as $value)
		if (!Property::where('id', $value)->first())
			return response('Property with ID ['.$value.'] does not exist', 406);

	$subcategory = new Subcategory([
		'name' => $request->post('name'),
		'categoryId' => $request->post('categoryId')
	]);
	$subcategory->save();

	foreach ($request->post('propertyIds') as $value) {
		new SubcategoryProperty([
			'subcategoryId' => $subcategory->id,
			'propertyId' => $value
		])->save();
	}
	return response('Subcategory ['.$request->post('name').'] was created', 200);
});

// ##### READ #####

Route::post('/admin/read/product', function (Request $request) {
	return response(null, 501);
	$request->user();       // Auth later
	return Product::all('id', 'name');
});

Route::post('/admin/read/unit', function (Request $request) {
	$request->user();       // Auth later
	return Unit::all('id', 'name');
});

Route::post('/admin/read/property', function (Request $request) {
	$request->user();       // Auth later
	return [
		'properties' => Property::all('id', 'name', 'unitId'),
		'units' => Unit::all('id', 'name')
	];
});

Route::post('/admin/read/category', function (Request $request) {
	$request->user();       // Auth later
	return Category::all('id', 'name');
});

Route::post('/admin/read/subcategory', function (Request $request) {
	$request->user();       // Auth later
	return [
		'subcategories' => Subcategory::all('id', 'name', 'categoryId'),
		'categories' => Category::all('id', 'name'),
		'properties' => Property::all('id', 'name', 'unitId'),
		'units' => Unit::all('id', 'name')
	];
});

// ##### UPDATE #####

Route::post('/admin/update/product', function (Request $request) {
	return response(null, 501);
	$request->user();       // Auth later
	return response(200);
});

Route::post('/admin/update/unit', function (Request $request) {
	$request->user();       // Auth later
	Unit::where('id', $request->post('id'))->update(['name' => $request->post('name')]);
	return response('Unit with ID ['.$request->post('id').'] updated to name ['.$request->post('name').']', 200);
});

Route::post('/admin/update/property', function (Request $request) {
	$request->user();       // Auth later
	Property::where('id', $request->post('id'))->update(['name' => $request->post('name'), 'unitId' => $request->post('unitId')]);
	return response('Property with ID ['.$request->post('id').'] updated to name ['.$request->post('name').'] and unit ['.Unit::where('id', $request->post('unitId'))->first()->name.']', 200);
});

Route::post('/admin/update/category', function (Request $request) {
	$request->user();       // Auth later
	Category::where('id', $request->post('id'))->update(['name' => $request->post('name')]);
	return response('Category with ID ['.$request->post('id').'] updated to name ['.$request->post('name').']', 200);
});

Route::post('/admin/update/subcategory', function (Request $request) {
	return response(null, 501);
	$request->user();       // Auth later
	Subcategory::where('id', $request->post('id'))->update(['name' => $request->post('name'), 'categoryId' => $request->post('categoryId')]);
	return response(200);
});

// ##### DELETE #####

Route::post('/admin/delete/product', function (Request $request) {
	$request->user();       // Auth later
	$product = Product::where('id', $request->post('id'))->first();
	$product->delete();
	return response('Product ['.$product->name.'] was deleted', 200);
});

Route::post('/admin/delete/unit', function (Request $request) {
	$request->user();       // Auth later
	$unit = Unit::where('id', $request->post('id'))->first();
	$unit->delete();
	return response('Unit ['.$unit->name.'] was deleted', 200);
});

Route::post('/admin/delete/property', function (Request $request) {
	$request->user();       // Auth later
	$property = Property::where('id', $request->post('id'))->first();
	$property->delete();
	return response('Property ['.$property->name.'] was deleted', 200);
});

Route::post('/admin/delete/category', function (Request $request) {
	$request->user();       // Auth later
	$category = Category::where('id', $request->post('id'))->first();
	$category->delete();
	return response('Category ['.$category->name.'] was deleted', 200);
});

Route::post('/admin/delete/subcategory', function (Request $request) {
	$request->user();       // Auth later
	$subcategory = Subcategory::where('id', $request->post('id'))->first();
	$subcategory->delete();
	return response('Subcategory ['.$subcategory->name.'] was deleted', 200);
});