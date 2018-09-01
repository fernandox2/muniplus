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

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::apiResource('/employees','EmployeeController');

Route::apiResource('/departaments','DepartamentController');

Route::apiResource('/schedules','ScheduleController');

Route::get('/schedules/programs/{id}', 'ScheduleController@findPrograms');

Route::apiResource('/relationships','RelationShipController');

Route::apiResource('/marks','MarkController');
