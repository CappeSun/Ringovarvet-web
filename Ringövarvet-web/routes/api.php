<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Unit;

##### SERVER #####

Route::get('/srv/setup', function () {
	if (Schema::hasTable('users')) return response('Setup already run', 403);

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