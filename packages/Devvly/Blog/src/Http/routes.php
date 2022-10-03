<?php
// Admin Routes:
Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/blog'], function () {
    Route::get('/', 'Devvly\Blog\Http\Controllers\Admin\PostController@index')->defaults('_config', [
        'view' => 'blog::admin.post.index'
    ])->name('blog.admin.post.index');


    Route::get('create', 'Devvly\Blog\Http\Controllers\Admin\PostController@create')->defaults('_config', [
        'view' => 'blog::admin.post.create'
    ])->name('blog.admin.post.create');

    Route::post('create', 'Devvly\Blog\Http\Controllers\Admin\PostController@store')->defaults('_config', [
        'redirect' => 'blog.admin.post.index'
    ])->name('blog.admin.post.store');

    Route::get('edit/{id}', 'Devvly\Blog\Http\Controllers\Admin\PostController@edit')->defaults('_config', [
        'view' => 'blog::admin.post.edit'
    ])->name('blog.admin.post.edit');

    Route::post('edit/{id}', 'Devvly\Blog\Http\Controllers\Admin\PostController@update')->defaults('_config', [
        'redirect' => 'blog.admin.post.index'
    ])->name('blog.admin.post.update');

    Route::post('/delete/{id}', 'Devvly\Blog\Http\Controllers\Admin\PostController@delete')->defaults('_config', [
        'redirect' => 'blog.admin.post.index'
    ])->name('blog.admin.post.delete');

    Route::post('/massdelete', 'Devvly\Blog\Http\Controllers\Admin\PostController@massDelete')->defaults('_config', [
        'redirect' => 'blog.admin.post.index'
    ])->name('blog.admin.post.mass-delete');
    // laravel file manager routes:
    Route::group(['prefix' => 'laravel-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', 'Devvly\Blog\Http\Controllers\Admin\PostCategoryController@index')->defaults('_config', [
            'view' => 'blog::admin.post_category.index'
        ])->name('blog.admin.post_category.index');
        Route::get('create', 'Devvly\Blog\Http\Controllers\Admin\PostCategoryController@create')->defaults('_config', [
            'view' => 'blog::admin.post_category.create'
        ])->name('blog.admin.post_category.create');

        Route::post('create', 'Devvly\Blog\Http\Controllers\Admin\PostCategoryController@store')->defaults('_config', [
            'redirect' => 'blog.admin.post_category.index'
        ])->name('blog.admin.post_category.store');

        Route::get('edit/{id}', 'Devvly\Blog\Http\Controllers\Admin\PostCategoryController@edit')->defaults('_config', [
            'view' => 'blog::admin.post_category.edit'
        ])->name('blog.admin.post_category.edit');

        Route::post('edit/{id}', 'Devvly\Blog\Http\Controllers\Admin\PostCategoryController@update')->defaults('_config', [
            'redirect' => 'blog.admin.post_category.index'
        ])->name('blog.admin.post_category.edit');

        Route::post('/delete/{id}', 'Devvly\Blog\Http\Controllers\Admin\PostCategoryController@delete')->defaults('_config', [
            'redirect' => 'blog.admin.post_category.index'
        ])->name('blog.admin.post_category.delete');

        Route::post('/massdelete', 'Devvly\Blog\Http\Controllers\Admin\PostCategoryController@massDelete')->defaults('_config', [
            'redirect' => 'blog.admin.post_category.index'
        ])->name('blog.admin.post_category.mass-delete');
    });

    Route::group(['prefix' => 'tag'], function () {
        Route::get('/', 'Devvly\Blog\Http\Controllers\Admin\TagController@index')->defaults('_config', [
            'view' => 'blog::admin.tag.index'
        ])->name('blog.admin.tag.index');
        Route::get('create', 'Devvly\Blog\Http\Controllers\Admin\TagController@create')->defaults('_config', [
            'view' => 'blog::admin.tag.create'
        ])->name('blog.admin.tag.create');

        Route::post('create', 'Devvly\Blog\Http\Controllers\Admin\TagController@store')->defaults('_config', [
            'redirect' => 'blog.admin.tag.index'
        ])->name('blog.admin.tag.store');

        Route::get('edit/{id}', 'Devvly\Blog\Http\Controllers\Admin\TagController@edit')->defaults('_config', [
            'view' => 'blog::admin.tag.edit'
        ])->name('blog.admin.tag.edit');

        Route::post('edit/{id}', 'Devvly\Blog\Http\Controllers\Admin\TagController@update')->defaults('_config', [
            'redirect' => 'blog.admin.tag.index'
        ])->name('blog.admin.tag.edit');

        Route::post('/delete/{id}', 'Devvly\Blog\Http\Controllers\Admin\TagController@delete')->defaults('_config', [
            'redirect' => 'blog.admin.tag.index'
        ])->name('blog.admin.tag.delete');

        Route::post('/massdelete', 'Devvly\Blog\Http\Controllers\Admin\TagController@massDelete')->defaults('_config', [
            'redirect' => 'blog.admin.tag.index'
        ])->name('blog.admin.tag.mass-delete');
    });
});

// Front Routes:
Route::group(['middleware' => ['web', 'locale', 'theme', 'currency'], 'prefix' => 'blog'], function () {
    Route::get('/', 'Devvly\Blog\Http\Controllers\Front\PostController@index')->defaults('_config', [
        'view' => 'blog::front.post.index'
    ])->name('blog.front.post.index');
    Route::get('/category/{slug}', 'Devvly\Blog\Http\Controllers\Front\PostController@filterByCategory')->defaults('_config', [
        'view' => 'blog::front.post.index'
    ])->name('blog.front.post.category');
    Route::get('/tag/{slug}', 'Devvly\Blog\Http\Controllers\Front\PostController@filterByTag')->defaults('_config', [
        'view' => 'blog::front.post.index'
    ])->name('blog.front.post.tag');
    Route::get('/{slug}', 'Devvly\Blog\Http\Controllers\Front\PostController@view')->defaults('_config', [
        'view' => 'blog::front.post.view'
    ])->name('blog.front.post.view');
});