<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin/tablerate/')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //Table SuperSets Shipping
            Route::get('/supersets', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@index')->defaults('_config', [
                'view' => 'tablerate::admin.super_sets.index'
            ])->name('admin.tablerate.supersets.index');

            //Add Superset
            Route::get('/supersets/create', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@create')->defaults('_config', [
                'view' => 'tablerate::admin.super_sets.create'
            ])->name('admin.tablerate.supersets.create');

            //Store superset
            Route::post('/supersets/create', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@store')->defaults('_config', [
                'redirect' => 'admin.tablerate.supersets.index'
            ])->name('admin.tablerate.supersets.store');

            //Edit Superset
            Route::get('/supersets/edit/{id}', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@edit')->defaults('_config', [
                'view' => 'tablerate::admin.super_sets.edit'
            ])->name('admin.tablerate.supersets.edit');

            //Update Superset
            Route::put('/supersets/edit/{id}', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@update')->defaults('_config', [
                'redirect' => 'admin.tablerate.supersets.index'
            ])->name('admin.tablerate.supersets.update');

            //Mass Update Superset
            Route::post('supersets/massupdate', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.tablerate.supersets.index'
            ])->name('admin.tablerate.supersets.massupdate');

            //delete Superset
            Route::post('/supersets/delete/{id}', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@destroy')->name('admin.tablerate.supersets.delete');

             //mass delete Superset
             Route::post('/supersets/massdelete/', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.tablerate.supersets.index'
            ])->name('admin.tablerate.supersets.massdelete');


            //Table SuperSetRates
            Route::get('/superset_rates', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetRateController@index')->defaults('_config', [
                'view' => 'tablerate::admin.super_set_rates.index'
            ])->name('admin.tablerate.superset_rates.index');

            //Create New Superset Rate
            Route::get('/superset_rates/create', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetRateController@create')->defaults('_config', [
                'view' => 'tablerate::admin.super_set_rates.create'
            ])->name('admin.tablerate.superset_rates.create');

            //Store New Superset Rate
            Route::post('/superset_rates/create', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetRateController@store')->defaults('_config', [
                'redirect' => 'admin.tablerate.superset_rates.index'
            ])->name('admin.tablerate.superset_rates.store');

            //Edit Superset Rates
            Route::get('/superset_rates/edit/{id}', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetRateController@edit')->defaults('_config', [
                'view' => 'tablerate::admin.super_set_rates.edit'
            ])->name('admin.tablerate.superset_rates.edit');

            //Update Superset Rate
            Route::post('/superset_rates/edit/{id}', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetRateController@update')->defaults('_config', [
                'redirect' => 'admin.tablerate.superset_rates.index'
            ])->name('admin.tablerate.superset_rates.update');

            //Delete one Superset Rate
            Route::post('/superset_rates/destroy/{id}', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetRateController@destroy')
            ->name('admin.tablerate.superset_rates.delete');

            //Mass Delete Superset Rate
            Route::post('/superset_rates/mass-delete', 'Webkul\TableRate\Http\Controllers\Admin\SuperSetRateController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.tablerate.superset_rates.index'
            ])->name('admin.tablerate.superset_rates.massdelete');


            //Table ShippingRate
            Route::get('/shipping_rates', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@index')->defaults('_config', [
                'view' => 'tablerate::admin.rates.index'
            ])->name('admin.tablerate.shipping_rates.index');

            //Add ShippingRate
            Route::get('/shipping_rates/create', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@create')->defaults('_config', [
                'view' => 'tablerate::admin.rates.create'
            ])->name('admin.tablerate.shipping_rates.create');
            
            //Store ShippingRate
            Route::post('/shipping_rates/create', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@store')->defaults('_config', [
                'redirect' => 'admin.tablerate.shipping_rates.index'
            ])->name('admin.tablerate.shipping_rates.store');

            //Edit Shipping Rates
            Route::get('/shipping_rates/edit/{id}', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@edit')->defaults('_config', [
                'view' => 'tablerate::admin.rates.edit'
            ])->name('admin.tablerate.shipping_rates.edit');

            //Update ShippingRate
            Route::post('/shipping_rates/edit/{id}', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@update')->defaults('_config', [
                'redirect' => 'admin.tablerate.shipping_rates.index'
            ])->name('admin.tablerate.shipping_rates.update');

            //Delete one ShippingRate
            Route::post('/shipping-rates/destroy/{id}', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@destroy')
            ->name('admin.tablerate.shipping_rates.delete');

            //Mass Delete ShippingRate
            Route::post('/shipping_rates/mass_delete', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.tablerate.shipping_rates.index'
            ])->name('admin.tablerate.shipping_rates.massdelete');

            //File ShippingRate
            Route::post('/shipping_rates/import', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@import')->defaults('_config', [
                'redirect' => 'admin.tablerate.shipping_rates.index'
            ])->name('admin.tablerate.shipping_rates.import');

            //DataGrid Export
            Route::post('/shipping_rates/export', 'Webkul\TableRate\Http\Controllers\Admin\ExportController@export')->name('admin.tablerate.shipping_rates.export');

            //Download Sample File CSV
            Route::get('/shipping_rates/sample_download', 'Webkul\TableRate\Http\Controllers\Admin\ShippingRateController@sampleDownload')->defaults('_config', [
                'view' => 'tablerate::admin.rates.index'
            ])->name('admin.tablerate.shipping_rates.sample_download');
        });

    });

});