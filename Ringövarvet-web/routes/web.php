<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Property;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryProperty;
use App\Models\ProductProperty;
use App\Models\Location;

use App\Http\Controllers\UserController;

// LOGIN

Route::get('/login', function (Request $request) {
    if ($request->user()) return redirect('/logout');
    return view('login');
})->name('login');

Route::get('/logout', function (Request $request) {
    if (!$request->user()) return redirect('/login');
    return view('logout');
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// PAGES

Route::get('/', function () {
    return view('index');
});

Route::get('/database_admin', function () {
    return view('dbAdmin');
})->middleware('auth');

Route::get('/create_product', function () {
    return view('createProductMobile');
})->middleware('auth');

Route::get('/manage_accounts', function (Request $request) {
    if (!$request->user() || $request->user()->access != 7) return redirect('/logout');
    return view('manageAccounts');
})->middleware('auth');

// API

// ##### CREATE #####       // Rename admin to database?

Route::post('/admin/create/product', function (Request $request) {
    if (!$request->user() || $request->user()->access < 1) return response('Insufficient access', 403);

    foreach ($request->post('properties') as $property)
        if (!Property::where('id', $property['propertyId'])->first())
            return response('Property with ID ['.$property['propertyId'].'] does not exist', 406);

    $product = new Product([
        'subcategoryId' => $request->post('subcategoryId'),
        'count' => $request->post('count'),
        'cost' => $request->post('cost'),
        'locationId' => $request->post('locationId')
    ]);
    $product->save();

    foreach ($request->post('properties') as $property) {
        new ProductProperty([
            'productId' => $product->id,
            'propertyId' => $property['propertyId'],
            'value' => $property['value']
        ])->save();
    }

    return response('Product with ID ['.$product->id.'] was created', 200);
});

Route::post('/admin/create/unit', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);

    new Unit([
        'name' => $request->post('name')
    ])->save();
    return response('Unit ['.$request->post('name').'] was created', 200);
});

Route::post('/admin/create/property', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);

    new Property([
        'name' => $request->post('name'),
        'unitId' => $request->post('unitId')
    ])->save();

    return response('Property ['.$request->post('name').'] with unit ['.Unit::where('id',$request->post('unitId'))->first()->name.'] was created', 200);
});

Route::post('/admin/create/section', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    new Section([
        'name' => $request->post('name')
    ])->save();

    return response('Section ['.$request->post('name').'] was created', 200);
});

Route::post('/admin/create/category', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $category = new Category([
        'name' => $request->post('name'),
        'sectionId' => $request->post('sectionId')
    ]);
    $category->save();

    return response('Category ['.$request->post('name').'] was created', 200);
});

Route::post('/admin/create/subcategory', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
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

Route::post('/admin/create/location', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    new Location([
        'name' => $request->post('name')
    ])->save();
    return response('Location ['.$request->post('name').'] was created', 200);
});

// ##### READ #####

Route::post('/admin/read/product', function (Request $request) {
    return [
        'products' => Product::all('id', 'subcategoryId', 'cost', 'count'),
        'productProperties' => ProductProperty::all('id', 'productId', 'propertyId', 'value'),
        'subcategoryProperties' => SubcategoryProperty::all('id', 'subcategoryId', 'propertyId'),
        'properties' => Property::all('id', 'name', 'unitId'),
        'sections' => Section::all('id', 'name'),
        'categories' => Category::all('id', 'name', 'sectionId'),
        'subcategories' => Subcategory::all('id', 'name', 'categoryId'),
        'units' => Unit::all('id', 'name'),
        'locations' => Location::all('id', 'name')
    ];
});

Route::post('/admin/read/productMobile', function (Request $request) {      // Separate into stages?
    return [
        'productProperties' => ProductProperty::all('id', 'productId', 'propertyId', 'value'),
        'subcategoryProperties' => SubcategoryProperty::all('id', 'subcategoryId', 'propertyId'),
        'properties' => Property::all('id', 'name', 'unitId'),
        'sections' => Section::all('id', 'name'),
        'categories' => Category::all('id', 'name', 'sectionId'),
        'subcategories' => Subcategory::all('id', 'name', 'categoryId'),
        'units' => Unit::all('id', 'name'),
        'locations' => Location::all('id', 'name')
    ];
});

Route::post('/admin/read/unit', function (Request $request) {
    return Unit::all('id', 'name');
});

Route::post('/admin/read/property', function (Request $request) {
    return [
        'properties' => Property::all('id', 'name', 'unitId'),
        'units' => Unit::all('id', 'name')
    ];
});

Route::post('/admin/read/section', function (Request $request) {
    return Section::all('id', 'name');
});

Route::post('/admin/read/category', function (Request $request) {
    return [
        'categories' => Category::all('id', 'name', 'sectionId'),
        'sections' => Section::all('id', 'name')
    ];
});

Route::post('/admin/read/subcategory', function (Request $request) {
    return [
        'subcategories' => Subcategory::all('id', 'name', 'categoryId'),
        'subcategoryProperties' => SubcategoryProperty::all('id', 'subcategoryId', 'propertyId'),
        'categories' => Category::all('id', 'name'),
        'properties' => Property::all('id', 'name', 'unitId'),
        'units' => Unit::all('id', 'name')
    ];
});

Route::post('/admin/read/location', function (Request $request) {
    return Location::all('id', 'name');
});

// ##### UPDATE #####

Route::post('/admin/update/product', function (Request $request) {
    return response(null, 501);
    if (!$request->user() || $request->user()->access < 1) return response('Insufficient access', 403);

    return response(200);
});

Route::post('/admin/update/unit', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    Unit::where('id', $request->post('id'))->update(['name' => $request->post('name')]);

    return response('Unit with ID ['.$request->post('id').'] updated to name ['.$request->post('name').']', 200);
});

Route::post('/admin/update/property', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    Property::where('id', $request->post('id'))->update([
        'name' => $request->post('name'),
        'unitId' => $request->post('unitId')
    ]);

    return response('Property with ID ['.$request->post('id').'] updated to name ['.$request->post('name').'] and unit ['.Unit::where('id', $request->post('unitId'))->first()->name.']', 200);
});

Route::post('/admin/update/section', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    Section::where('id', $request->post('id'))->update([
        'name' => $request->post('name')
    ]);

    return response('Section with ID ['.$request->post('id').'] updated to name ['.$request->post('name').']', 200);
});

Route::post('/admin/update/category', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    Category::where('id', $request->post('id'))->update([
        'name' => $request->post('name'),
        'sectionId' => $request->post('sectionId')
    ]);

    return response('Category with ID ['.$request->post('id').'] updated to name ['.$request->post('name').'] and section ['.Section::where('id', $request->post('sectionId'))->first()->name.']', 200);
});

Route::post('/admin/update/subcategory', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    Subcategory::where('id', $request->post('id'))->update([
        'name' => $request->post('name'),
        'categoryId' => $request->post('categoryId')
    ]);

    $propertiesUpdated = '';
    if ($request->post('properties')) {
        if (Property::where('subcategoryId', $request->post('id'))->first()) {
            return response('Subcategory ['.$request->post('name').'] has products. Properties were not updated.');
        }

        SubcategoryProperty::where('subcategoryId', $request->post('id'))->delete();

        foreach ($request->post('properties') as $value) {
            new SubcategoryProperty([
                'subcategoryId' => $request->post('id'),
                'propertyId' => $value
            ]);
        }
        $propertiesUpdated = ', properties were also updated';
    }

    return response('Subcategory with ID ['.$request->post('id').'] updated to name ['.$request->post('name').'] and category ['.Category::where('id', $request->post('categoryId'))->first()->name.']'.$propertiesUpdated, 200);
});

Route::post('/admin/update/location', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    Location::where('id', $request->post('id'))->update(['name' => $request->post('name')]);

    return response('Location with ID ['.$request->post('id').'] updated to name ['.$request->post('name').']', 200);
});

// ##### DELETE #####

Route::post('/admin/delete/product', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $product = Product::where('id', $request->post('id'))->first();
    ProductProperty::where('productId', $product->id)->delete();
    $product->delete();

    return response('Product with ID ['.$product->id.'] was deleted', 200);
});

Route::post('/admin/delete/unit', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $unit = Unit::where('id', $request->post('id'))->first();

    if (Property::where('unitId', $unit->id)->first())      // If there's a property bound to the unit, do not delete it
        return response('Unit ['.$unit->name.'] is used by properties', 406);

    $unit->delete();

    return response('Unit ['.$unit->name.'] was deleted', 200);
});

Route::post('/admin/delete/property', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $property = Property::where('id', $request->post('id'))->first();
    $property->delete();

    return response('Property ['.$property->name.'] was deleted', 200);
});

Route::post('/admin/delete/section', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $section = Section::where('id', $request->post('id'))->first();

    if (Category::where('sectionId', $section->id)->first())        // If there's a category bound to the section, do not delete it
        return response('Section ['.$section->name.'] has categories', 406);

    $section->delete();

    return response('Category ['.$section->name.'] was deleted', 200);
});

Route::post('/admin/delete/category', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $category = Category::where('id', $request->post('id'))->first();

    if (Subcategory::where('categoryId', $category->id)->first())       // If there's a subcategory bound to the category, do not delete it
        return response('Category ['.$category->name.'] has subcategories', 406);

    $category->delete();

    return response('Category ['.$category->name.'] was deleted', 200);
});

Route::post('/admin/delete/subcategory', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $subcategory = Subcategory::where('id', $request->post('id'))->first();

    if (Product::where('subcategoryId', $subcategory->id)->first())     // If there's a product bound to the subcategory, do not delete it
        return response('Subcategory ['.$subcategory->name.'] has products', 406);

    SubcategoryProperty::where('subcategoryId', $subcategory->id)->delete();

    $subcategory->delete();

    return response('Subcategory ['.$subcategory->name.'] was deleted', 200);
});

Route::post('/admin/delete/location', function (Request $request) {
    if (!$request->user() || $request->user()->access < 2) return response('Insufficient access', 403);
    
    $location = Location::where('id', $request->post('id'))->first();

    if (Product::where('locationId', $location->id)->first())       // If there's a product bound to the location, do not delete it
        return response('Location ['.$location->name.'] is used by products', 406);

    $location->delete();

    return response('Location ['.$location->name.'] was deleted', 200);
});

##### USER #####

Route::post('/user/create', [UserController::class, 'create']);
Route::post('/user/read', [UserController::class, 'read']);
Route::post('/user/update', [UserController::class, 'update']);
Route::post('/user/delete', [UserController::class, 'delete']);