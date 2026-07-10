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
// post routs
Route::get('/admin/post' , 'Admin\PostController@index' , 'admin.post.index');

Route::get('/admin/post/create1' , 'Admin\PostController@create1' , 'admin.post.create1');
Route::get('/admin/post/create2' , 'Admin\PostController@create2' , 'admin.post.create2');

Route::post('/admin/post/store' , 'Admin\PostController@store' , 'admin.post.store');
Route::get('/admin/post/edit/{id}' , 'Admin\PostController@edit' , 'admin.post.edit');
Route::put('/admin/post/update/{id}' , 'Admin\PostController@update' , 'admin.post.update');
Route::delete('/admin/post/delete/{id}' , 'Admin\PostController@destroy' , 'admin.post.destroy');

// media routs
Route::get('/admin/media' , 'Admin\MediaController@index' , 'admin.media.index');

Route::get('/admin/media/create1' , 'Admin\MediaController@create' , 'admin.media.create');

Route::post('/admin/media/store' , 'Admin\MediaController@store' , 'admin.media.store');
Route::post('/admin/media/altImage' , 'Admin\MediaController@altImage' , 'admin.media.altImage');
Route::get('/admin/media/edit/{id}' , 'Admin\MediaController@edit' , 'admin.media.edit');
Route::put('/admin/media/update/{id}' , 'Admin\MediaController@update' , 'admin.media.update');
Route::put('/admin/media/updateAlt/{id}' , 'Admin\MediaController@updateAlt' , 'admin.media.updateAlt');
Route::delete('/admin/media/delete/{id}' , 'Admin\MediaController@destroy' , 'admin.media.destroy');
Route::get('/admin/media/list', 'Admin\MediaController@getList', 'admin.media.list');
// Users routs
Route::get('/admin/users' , 'Admin\UsersController@index' , 'admin.users.index');
Route::get('/admin/users/create' , 'Admin\UsersController@create' , 'admin.users.create');
Route::post('/admin/users/store' , 'Admin\UsersController@store' , 'admin.users.store');
Route::get('/admin/users/edit/{id}' , 'Admin\UsersController@edit' , 'admin.users.edit');
Route::put('/admin/users/update/{id}' , 'Admin\UsersController@update' , 'admin.users.update');
Route::delete('/admin/users/delete/{id}' , 'Admin\UsersController@destroy' , 'admin.users.destroy');

//coment routs
Route::get('/admin/comments' , 'Admin\CommentsController@index' , 'admin.comments.index');
Route::get('/admin/comments/{id}' , 'Admin\CommentsController@edit' , 'admin.comments.edit');
Route::post('/admin/comments/update' , 'Admin\CommentsController@update' , 'admin.comments.update');
Route::post('/admin/comments/answer' , 'Admin\CommentsController@answer' , 'admin.comments.answer');
Route::delete('/admin/comments/delete/{id}' , 'Admin\CommentsController@destroy' , 'admin.comments.destroy');
Route::put('/admin/comments/app' , 'Admin\CommentsController@approved' , 'admin.comments.approved');