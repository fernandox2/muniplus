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

Route::apiResource('/employees','EmployeeController');

Route::get('/employees/departament/{codigo}', 'EmployeeController@employeebydepartament');

Route::apiResource('/departaments','DepartamentController');

Route::apiResource('/schedules','ScheduleController');

Route::get('/schedules/programs/{id}', 'ScheduleController@findPrograms');

Route::apiResource('/relationships','RelationShipController');

Route::get('/RelacionesConTurnos', 'RelationShipController@RelationTurn');

Route::apiResource('/assistances','AssistanceController');

Route::apiResource('/turns','TurnController');

Route::get('/assistances/fechas/{inicio}/{fin}', 'AssistanceController@filtrarFechas');

Route::get('/assistances/todos/{codigo}/{inicio}/{fin}/{depto}', 'AssistanceController@filtrarFuncionariosFechasDepartamentos');

Route::get('/assistances/fechasemployee/{codigo}/{inicio}/{fin}', 'AssistanceController@filtrarFuncionariosFechas');

Route::get('/assistances/fechasdepartament/{inicio}/{fin}/{codigo}', 'AssistanceController@filtrarDepartamentosFechas');

Route::get('/assistances/departamento/{codigo}', 'AssistanceController@filtrarDepartamento');

Route::get('/assistances/employee/{codigo}', 'AssistanceController@filtrarFuncionarios');

Route::get('/marks/today', 'MarkController@FindMarksToDay');

Route::get('/marks/errors/today', 'MarkController@ErrorsToDay');

Route::get('/marks/errors/five', 'MarkController@LastFiveEventError');

Route::get('/marks/info/five', 'MarkController@LastFiveEventInfo');

Route::apiResource('/exceptions','ExceptionController');

Route::get('/tiposdeexcepciones', 'ExceptionController@tipos');
