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
            'as' => 'admin_view_report',
            'uses' => 'AdminController@view_report'
        ));

        Route::get('/admin/report/{id}/download' , array(
            'as' => 'admin_download_report',
            'uses' => 'AdminController@download_report'
        ));

        Route::get('/admin/report_item/{id}/' , array(
            'as' => 'admin_view_report_item',
            'uses' => 'AdminController@report_item'
        ));

        Route::get('/admin/report_item/{id}/download' , array(
            'as' => 'admin_download_report_item',
            'uses' => 'AdminController@download_report_item'
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

        Route::group(array('before' => 'audit_has_report_a'),function()
        {
            Route::get('/audit/report/{id}' , array(  //aid
                'as' => 'audit_report',
                'uses' => 'AuditController@report'
            ));

            Route::post('/audit/report/{id}' , array(  //aid
                'as' => 'audit_report_process',
                'uses' => 'AuditController@report_process'
            ));
        });

        Route::group(array('before' => 'audit_has_report_r'),function()
        {
            Route::get('/audit/view/{id}' , array(  //rid
                'as' => 'audit_view_report',
                'uses' => 'AuditController@view_report'
            ));

            Route::get('/audit/view/{id}/download' , array(  //rid
                'as' => 'audit_download_report',
                'uses' => 'AuditController@download_report'
            ));
        });

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

        Route::get('/auditee/feedback/{r_id}' , array(
            'as' => 'auditee_feedback',
            'uses' => 'AuditeeController@feedback'
        ));

        Route::post('/auditee/feedback/{r_id}' , array(
                'as' => 'auditee_feedback_process',
                'uses' => 'AuditeeController@feedback_process'
        ));

        Route::post('/auditee/assign/{r_id}' , array(
                'as' => 'auditee_assign_process',
                'uses' => 'AuditeeController@assign_process'
        ));

        Route::group(array('before' => 'auditee_has_report'), function(){
            Route::get('/auditee/report/{id}' , array(
                'as' => 'auditee_view_report',
                'uses' => 'AuditeeController@view_report'
            ));

            Route::get('/auditee/report/{id}/download' , array(
                'as' => 'auditee_download_report',
                'uses' => 'AuditeeController@download_report'
            ));
        });

        // TODO: add a validation to report item
        Route::get('/auditee/report_item/{id}' , array(
                'as' => 'auditee_view_report_item',
                'uses' => 'AuditeeController@report_item'
        ));
        Route::get('/auditee/report_item/{id}/download' , array(
                'as' => 'auditee_download_report_item',
                'uses' => 'AuditeeController@download_report_item'
        ));
    });

});

Route::get('/logout' , array(
    'as' => 'logout',
    function() {
        $err_msg = Session::has("message") ? Session::get("message") : "";
        Session::flush();
        Session::set("message","$err_msg 已登出！");
        return Redirect::route('login');
    }
));

Route::get('/sign/{code}' , array(
    'as' => 'email_sign',
    'uses' => 'AuditController@sign'
));

Route::get('/rectify_sign/{code}/{yes_no}' , array(
    'as' => 'rectify_email_sign',
    'uses' => 'AuditeeController@sign'
));

Route::get('/rectify_sign2/{code}/{yes_no}' , array(
    'as' => 'rectify_email_sign2',
    'uses' => 'AuditeeController@sign2'
));

Route::get('/feedback/{code}' , array(
    'as' => 'feedback_assign',
    'uses' => 'AuditeeController@feedback_assign'
));

Route::post('/feedback/{code}' , array(
    'as' => 'feedback_assign_process',
    'uses' => 'AuditeeController@feedback_assign_process'
));

Route::get('/test' , array(
    'as' => 'test',
    function() {
        return "preserved for testing";
    }
));
