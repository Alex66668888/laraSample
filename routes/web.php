<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

//显示注册页面
Route::get('signup', 'UsersController@create')->name('signup');
//邮件发送
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

// 使用 resource 方法定义用户资源路由
Route::resource('users', 'UsersController');

//显示登录页面
Route::get('login','SessionsController@create')->name('login');
//创建新会话（登录）
Route::post('login','SessionsController@store')->name('login');
//销毁会话（退出登录）
Route::delete('logout','SessionsController@destroy')->name('logout');

//密码重设功能
//显示重置密码的邮箱发送页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//微博的创建和删除
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);