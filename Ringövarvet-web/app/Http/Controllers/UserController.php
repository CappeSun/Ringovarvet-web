<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
	public function create(Request $request) {
		if (!$request->user() || $request->user()->access < 7) return response('Insufficient access', 403);

		$credentials = $request->validate([
			'name' => 'required|string|max:31',
			'password' => 'required|string|max:31',
			'access' => 'required|integer|min:0|max:2'
		]);

		new User([
			'name' => htmlspecialchars($request->name),
			'password' => htmlspecialchars($request->password),
			'access' => htmlspecialchars($request->access)
		])->save();

		return response('User ['.$request->name.'] was created', 200);
	}

	public function read(Request $request) {
		if (!$request->user() || $request->user()->access < 7) return response('Insufficient access', 403);

		return User::select('id', 'name', 'access')->where('id', '!=', 1)->get();
	}

	public function update(Request $request) {
		return response(null, 501);
		if (!$request->user() || $request->user()->access < 7) return response('Insufficient access', 403);

		if ($request->user()->id == 1) return response('Master admin cannot be updated', 403);

		$user = User::where('id', $request->id);

		$user->update(['update' => $request->update]);

		return response('User ['.$user->name.'] was updated to access level ['.$user->access.']', 200);
	}

	public function delete(Request $request) {
		if (!$request->user() || $request->user()->access < 7) return response('Insufficient access', 403);

		if ($request->id == 1) return response('Master admin cannot be deleted', 403);

		$user = User::where('id', $request->id)->first();

		if (!$user) return response('User does not exist', 406);

		$user->delete();

		return response('User ['.$user->name.'] was deleted', 200);
	}

	public function login(Request $request) {
		$credentials = $request->validate([
			'name' => 'required',
			'password' => 'required|string'
		]);

		if (Auth::attempt($credentials)){
			$request->session()->regenerate();

			return redirect('/');
		}

		return redirect('/login');
	}

	public function logout(Request $request)
	{
		Auth::logout($request);
		return redirect('/');
	}
}

function userInfo(){
	if (Auth::user())
		return ['name' => Auth::user()['name'], 'id' => Auth::user()['id']];
	else
		return ['name' => 'Guest', 'id' => 0];
}