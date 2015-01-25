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
    Route::get('/', function(){ return Redirect::route('login'); });
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
                $person->p_level = 1;
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

        Route::get('/admin/reports' , array(
            'as' => 'admin_reports',
            'uses' => 'AdminController@reports'
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

        Route::get('/admin/report/{id}' , array(
            'as' => 'admin_preview_report',
            'uses' => 'AdminController@preview_report'
        ));

        Route::get('/admin/report/{id}/download' , array(
            'as' => 'admin_download_report',
            'uses' => 'AdminController@download_report'
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

        Route::get('/audit/calendar' , array(
            'as' => 'audit_calendar',
            'uses' => 'AuditController@calendar'
        ));

        Route::get('/audit/report/{id}' , array(
            'as' => 'audit_report',
            'uses' => 'AuditController@report'
        ));

        Route::post('/audit/report/{id}' , array(
            'as' => 'audit_report_process',
            'uses' => 'AuditController@report_process'
        ));

        Route::get('/audit/view/{id}' , array(
            'as' => 'audit_preview_report',
            'uses' => 'AuditController@view_report'
        ));

        Route::get('/audit/view/{id}/download' , array(
            'as' => 'audit_download_report',
            'uses' => 'AuditController@download_report'
        ));

    });

    Route::group(array('before' => 'is_auditee'), function(){
        Route::get('/auditee' , array(
            'as' => 'auditee',
            function() { return Redirect::route('auditee_status'); }
        ));
        Route::get('/auditee/status' , array(
            'as' => 'auditee_status',
            'uses' => 'AuditeeController@status'
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

Route::get('/sign/{code}' , array(
    'as' => 'email_sign',
    'uses' => 'AuditController@sign'
));

Route::get('/test' , array(
    'as' => 'test',
    function() {
        return "preserved for testing";
    }
));
