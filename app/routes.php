<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('before' => 'guest'), function()
{
    ## 登入頁面
    Route::get('/', function()  { return Redirect::route('login'); });
    Route::get('/login' , array(
        'as' => 'login',
        function() { return View::make('guest/login'); }
    ));

    Route::post('/login' , array(
        'as' => 'login_process',
        function() {
            $person = Person::get(Input::get('user'),md5(Input::get('pwd')));
            if($person){
                Session::set("message","login success!");
                Session::set('user',$person);
                return Redirect::route('admin');
            }
            else{
                Session::set("message","帳號或密碼錯誤");
                
                die();
                return Redirect::route('login');
            }
        }
    ));
});

Route::group(array('before' => 'auth'), function()
{
    // Route::group(array('before' => 'admin'), function()
    // {
        Route::get('/admin' , array(
            'as' => 'admin',
            function() { return Redirect::route('admin_info',"dept"); }
        ));
        Route::get('/admin/{type}' , array(
            'as' => 'admin_info',
            'uses' => 'AdminController@info'
        ));
    // });
});
