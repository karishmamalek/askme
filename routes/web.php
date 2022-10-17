<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('send-mail', function () {
   
    $data  = ['message' => 'This is a test!'];
    \Mail::to('sahkaritech@gmail.com')->send(new \App\Mail\sendEmail($data));

    dd("Email is Sent.");
});