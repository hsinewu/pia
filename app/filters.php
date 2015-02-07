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
		if($person->is("admin"))
		    return Redirect::route('admin');
		if($person->is("audit"))
		    return Redirect::route('audit');
		if($person->is("auditee"))
		    return Redirect::route('auditee');
		dd("filters.php: Person level mismatch");
		// switch ($person->p_level) {
		// 	case 0:
		// 		return Redirect::route('auditee');
		//     case 1:
		//         return Redirect::route('audit');
		//     case 2:
		//         return Redirect::route('admin');
		//     default:
		//         dd("Unexpected person level in routes.");
		//         break;
		// }
	}
});

Route::filter('is_admin', function()
{
	if (!Session::get('user')->is("admin"))
	{
		Session::set("message","您沒有造訪該網頁的權限");
		return Redirect::to('/');
	}
});

Route::filter('is_audit', function()
{
	if (!Session::get('user')->is("audit"))
	{
		Session::set("message","您沒有造訪該網頁的權限");
		return Redirect::to('/');
	}
});

Route::filter('is_auditee', function()
{
	if (!Session::get('user')->is("auditee"))
	{
		Session::set("message","您沒有造訪該網頁的權限");
		return Redirect::to('/');
	}
});

Route::filter('audit_has_report_r', function($route)
{
	$id = $route->getParameter('id');
	try {
		$report = PiaReport::find($id);
		$audit = PiaAudit::find($report->a_id);
	} catch (Exception $e) {
		Session::set("message","Error");
		// App::abort(404);
		return Redirect::to('/');
	}
	if (Session::get('user')->p_id != $audit->p_id)
	{
		Session::set("message","Oops");
		return Redirect::to('/');
	}
});

Route::filter('audit_has_report_a', function($route)
{
	$id = $route->getParameter('id');
	try {
		$audit = PiaAudit::find($id);
	} catch (Exception $e) {
		Session::set("message","Error");
		// App::abort(404);
		return Redirect::to('/');
	}
	if (Session::get('user')->p_id != $audit->p_id)
	{
		Session::set("message","Oops");
		return Redirect::to('/');
	}
});

Route::filter('auditee_has_report', function($route)
{
	$id = $route->getParameter('id');
	try {
		$report = PiaReport::find($id);
		$audit = PiaAudit::find($report->a_id);
	} catch (Exception $e) {
		Session::set("message","Error");
		// App::abort(404);
		return Redirect::to('/');
	}
	if (Session::get('user')->dept_id != $audit->ad_dept_id)
	{
		Session::set("message","Oops");
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
