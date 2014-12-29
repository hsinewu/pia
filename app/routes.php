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
    Route::get('/', function(){ /*redirect by filter*/ });
    Route::get('/login' , array(
        'as' => 'login',
        function() { return View::make('guest/login'); }
    ));

    Route::post('/login' , array(
        'as' => 'login_process',
        function() {
            $person = PiaPerson::get(Input::get('user'),md5(Input::get('pwd')));
            if($person){
                Session::set("message","登入成功!");
                Session::set('user',$person);
                return Redirect::to('/');
            }
            else{
                Session::set("message","帳號或密碼錯誤");
                return Redirect::route('login');
            }
        }
    ));
});

Route::group(array('before' => 'auth'), function()
{
    Route::group(array('before' => 'is_admin'), function()
    {
        Route::get('/admin' , array(
            'as' => 'admin',
            function() { return Redirect::route('admin_info',"dept"); }
        ));
        Route::get('/admin/cal' , array(
            'as' => 'admin_cal',
            'uses' => 'AdminController@cal'
        ));

        Route::get('/admin/{type}' , array(
            'as' => 'admin_info',
            'uses' => 'AdminController@info'
        ));

        Route::get('/admin/edit/{type}/{id?}' , array(
            'as' => 'admin_edit',
            'uses' => 'AdminController@edit'
        ));

        Route::post('/admin/edit/auditor' , array(
            'as' => 'add_autitor_process',
            'uses' => 'AdminController@add_autitor'
        ));

        Route::post('/admin/edit/{type}/{id?}' , array(
            'as' => 'admin_edit_process',
            'uses' => 'AdminController@edit_process'
        ));

        Route::get('/admin/del/{type}/{id}' , array(
            'as' => 'admin_del',
            'uses' => 'AdminController@del'
        ));
    });

    Route::group(array('before' => 'is_audit'), function()
    {
        Route::get('/audit' , array(
            'as' => 'audit',
            function() { return Redirect::route('audit_tasks'); }
        ));

        Route::get('/audit/tasks' , array(
            'as' => 'audit_tasks',
            'uses' => 'AuditController@tasks'
        ));

        Route::get('/audit/report/{id}' , array(
            'as' => 'audit_report',
            'uses' => 'AuditController@report'
        ));

        Route::post('/audit/report/{id}' , array(
            'as' => 'audit_report_process',
            'uses' => 'AuditController@report_process'
        ));
    });

});

Route::get('/logout' , array(
    'as' => 'logout',
    function() {
        Session::flush();
        Session::set("message","已登出！");
        return Redirect::route('login');
    }
));

Route::get('/test' , array(
    'as' => 'test',
    function() {
        return PiaReport::all()->first()->gen_view();
    }
));
