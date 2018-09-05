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

Route::get('/employees/departament/{codigo}', 'EmployeeController@employeebydepartament');

Route::apiResource('/departaments','DepartamentController');

Route::apiResource('/schedules','ScheduleController');

Route::get('/schedules/programs/{id}', 'ScheduleController@findPrograms');

Route::apiResource('/relationships','RelationShipController');

Route::apiResource('/assistances','AssistanceController');

Route::get('/assistances/fechas/{inicio}/{fin}', 'AssistanceController@filtrarFechas');

Route::get('/assistances/todos/{codigo}/{inicio}/{fin}/{depto}', 'AssistanceController@filtrarFuncionariosFechasDepartamentos');

Route::get('/assistances/fechasemployee/{codigo}/{inicio}/{fin}', 'AssistanceController@filtrarFuncionariosFechas');

Route::get('/assistances/fechasdepartament/{inicio}/{fin}/{codigo}', 'AssistanceController@filtrarDepartamentosFechas');

Route::get('/assistances/departamento/{codigo}', 'AssistanceController@filtrarDepartamento');

Route::get('/assistances/employee/{codigo}', 'AssistanceController@filtrarFuncionarios');