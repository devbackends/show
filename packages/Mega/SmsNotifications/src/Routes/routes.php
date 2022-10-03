<?php
/*
 *  
 *
 *
 * */

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {
        Route::group(['middleware' => ['admin']], function () {
            Route::get('configuration/megasmsnotifications', 'Mega\SmsNotifications\Http\Controllers\ConfigurationController@general')
                ->defaults('_config', [
                'view' => 'megasmsnotifications::configuration.index'
            ])->name('megasmsnotifications.configuration.index');
       });
    });
});