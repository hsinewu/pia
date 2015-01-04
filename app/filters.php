<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	// if (Auth::guest())
	// {
	// 	if (Request::ajax())
	// 	{
	// 		return Response::make('Unauthorized', 401);
	// 	}
	// 	else
	// 	{
	// 		return Redirect::guest('login');
	// 	}
	// }
	if(!Session::get('user'))
		return Redirect::route('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	// if (Auth::check()) return Redirect::to('/');
	$person = Session::get('user');
	if($person){
		switch ($person->p_level) {
			case 0:
				return Redirect::route('auditee');
		    case 1:
		        return Redirect::route('audit');
		        break;
		    case 2:
		        return Redirect::route('admin');
		        break;
		    default:
		        dd("Unexpected person level in routes.");
		        break;
		}
	}
});

Route::filter('is_admin', function()
{
	if (Session::get('user')->p_level!=2)
	{
		Session::set("message","您沒有造訪該網頁的權限");
		return Redirect::to('/');
	}
});

Route::filter('is_audit', function()
{
	if (Session::get('user')->p_level!=1)
	{
		Session::set("message","您沒有造訪該網頁的權限");
		return Redirect::to('/');
	}
});

Route::filter('is_auditee', function()
{
	if (Session::get('user')->p_level!=0)
	{
		Session::set("message","您沒有造訪該網頁的權限");
		return Redirect::to('/');
	}
});
/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
