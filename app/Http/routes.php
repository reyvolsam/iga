<?php
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Modules\Users\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', ['middleware' => 'loginIn', function (Request $request){

	$uc = new UsersController($request);
	$ru = $uc->GetUserRole();
	return view('index', ['request' => $request, 'ru' => $ru]);
}]);

Route::get('index', function () {
	if( \Sentry::check() ){
		return redirect('/');
	} else {
		return redirect('login');
	}
});

Route::get('login', function (){
	if( \Sentry::check() ){
		return redirect('/');
	} else {
		return view('login');
	}
});

Route::post('login/form', 'LoginController@LoginUser');

Route::get('logout', function (Request $request){
	\Sentry::logout();
	$request->session()->flush();
	return redirect('/');
});