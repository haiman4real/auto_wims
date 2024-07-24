<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth','MasterAdmin', 'verified'])->namespace('MasterAdmin')->group(function(){
    Route::get('/ma/home', 'HomeController@index');
});

Route::middleware(['auth','SuperAdmin', 'verified'])->namespace('SuperAdmin')->group(function(){
    Route::get('/sa/home', 'HomeController@index');
});

Route::middleware(['auth','AdminOne','verified'])->namespace('AdminOne')->group(function(){
    Route::get('/ao/home', 'HomeController@index');
});

Route::middleware(['auth','AdminTwo','verified'])->namespace('AdminTwo')->group(function(){
    Route::get('/aw/home', 'HomeController@index');
});

Route::middleware(['auth','AdminThree', 'verified'])->namespace('AdminThree')->group(function(){
    Route::get('/ah/home', 'HomeController@index');
});

Route::middleware(['auth','CustomerService','verified'])->namespace('CustomerService')->group(function(){
    Route::get('/cs/home', 'HomeController@index');
});

Route::middleware(['auth','FrontDesk','verified'])->namespace('FrontDesk')->group(function(){
    Route::get('/fd/home', 'HomeController@index');
});

Route::middleware(['auth','Technician', 'verified'])->namespace('Technician')->group(function(){
    Route::get('/tc/home', 'HomeController@index');
});

Route::middleware(['auth','ServiceAdvisor', 'verified'])->namespace('ServiceAdvisor')->group(function(){
    Route::get('/sa/home', 'HomeController@index');
});

Route::middleware(['auth','JobController','verified'])->namespace('JobController')->group(function(){
    Route::get('/jc/home', 'HomeController@index');
});

Route::middleware(['auth','AccountsAdmin','verified'])->namespace('AccountsAdmin')->group(function(){
    Route::get('/aa/home', 'HomeController@index');
});

Route::middleware(['auth','BusinessView', 'verified'])->namespace('BusinessView')->group(function(){
    Route::get('/bv/home', 'HomeController@index');
});

Route::middleware(['auth','GuestUser','verified'])->namespace('GuestUser')->group(function(){
    Route::get('/gu/home', 'HomeController@index');
});

Route::middleware(['auth','CoporateUser','verified'])->namespace('CoporateUser')->group(function(){
    Route::get('/cu/home', 'HomeController@index');
});


//frontdesk,
//inspectionofficer
//certificationofficer
//globaladmin
//admin_view_only
