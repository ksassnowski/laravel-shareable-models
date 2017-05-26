<?php

Route::group(['middleware' => 'web'], function () {
    Route::get(
        '/shared/password/{shareable_link}',
        'Sassnowski\LaravelShareableModel\Http\Controllers\ShareableLinkPasswordController@show'
    );

    Route::post(
        '/shared/password/{shareable_link}',
        'Sassnowski\LaravelShareableModel\Http\Controllers\ShareableLinkPasswordController@store'
    );
});
