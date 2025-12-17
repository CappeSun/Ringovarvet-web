<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Unit;

##### SERVER #####

Route::get('/srv/setup', function () {
	if (Schema::hasTable('users')) return response('Setup already run', 406);

	Artisan::call('migrate', [
		'--path' => 'database/migrations',
		'--database' => 'mysql',
		'--force' => true
	]);
	
	new User([
		'name' => env('ADMIN_NAME'),
		'password' => env('ADMIN_PASSWORD'),
		'access' => 7
	])->save();
	
	new Unit([
		'name' => '(Ingen enhet)',
	])->save();

	return response('Setup complete', 200);
});

Route::get('/srv/admin_update', function () {
	$user = User::where('id', 1)->first();

	$env = preg_split("/\r\n|\n|\r/", file_get_contents('../.env'));

	$adminName = explode('=', array_find($env, function ($entry) {
		return str_contains($entry, 'ADMIN_NAME=');
	}))[1];

	$adminPass = explode('=', array_find($env, function ($entry) {
		return str_contains($entry, 'ADMIN_PASSWORD=');
	}))[1];

	if ($user->name == $adminName && Hash::check($adminPass, $user->password))
		return response('Neither admin name nor password is changed', 406);

	$user->name = $adminName;
	$user->password = Hash::make($adminPass);
	$user->save();

	return response('Admin updated', 200);
});