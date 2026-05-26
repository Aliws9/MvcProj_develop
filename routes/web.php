<?php

use System\Router\Web\Route;

Route::get('', 'HomeController@index', 'index');
Route::get('create', 'HomeController@create', 'create');
Route::get('edit/{id}', 'HomeController@edit', 'edit');
Route::post('store', 'HomeController@store', 'store');
Route::put('/update/{id}', 'HomeController@update', 'update');
Route::delete('/delete/{id}', 'HomeController@destroy', 'delete');

//admin routs
Route::get('/admin' , 'Admin\AdminController@index' , 'admin.index');
// Category routs
Route::get('/admin/category' , 'Admin\CategoryController@index' , 'admin.category.index');
Route::get('/admin/category/create' , 'Admin\CategoryController@create' , 'admin.category.create');
Route::post('/admin/category/store' , 'Admin\CategoryController@store' , 'admin.category.store');
Route::get('/admin/category/edit/{id}' , 'Admin\CategoryController@edit' , 'admin.category.edit');
Route::put('/admin/category/update/{id}' , 'Admin\CategoryController@update' , 'admin.category.update');
Route::delete('/admin/category/delete/{id}' , 'Admin\CategoryController@destroy' , 'admin.category.destroy');

