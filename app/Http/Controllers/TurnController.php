<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Turn;
use App\Event;
use Illuminate\Support\Facades\DB;

class TurnController extends Controller
{

    public function index()
    {
        $turnos = DB::table('turns')
        ->join('relationships', 'relationships.id', '=', 'turns.relationship_id')
        ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
        ->join('schedules', 'schedules.id', '=', 'relationships.schedule_id')
        ->join('employees', 'employees.id', '=', 'relationships.employee_id')
        ->select('turns.*','employees.nombre as nameEmployee','departaments.nombre as nameDepartament','schedules.nombre as nameSchedule')
        ->OrderBy('employees.nombre','asc')
        ->get();

        return $turnos;
    }


    public function store(Request $request)
    {
        $turno = new Turn();

        if($request->relationship <> 0 && $request->schedule <> 0 && $request->inicio <> null && $request->fin <> null){

            $turno->relationship_id = $request->relationship;
            $turno->schedule_id = $request->schedule;
            $turno->inicio = $request->inicio;
            $turno->fin = $request->fin;

            $turno->save();
            $turno->save = true;

            $relation = DB::table('relationships')
            ->join('employees', 'employees.id', '=', 'relationships.employee_id')
            ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
            ->join('schedules', 'schedules.id', '=', 'relationships.schedule_id')
            ->where('relationships.id',$request->relationship)
            ->select('relationships.*','employees.nombre as nameEmployee','departaments.nombre as nameDepartament','schedules.nombre as nameSchedule')
            ->OrderBy('employees.nombre','asc')
            ->first();

            $evento = new Event();
            $evento->evento = "Se creó un nuevo turno para el funcionario " . $relation->nameEmployee . " en el (la) " . $relation->nameDepartament;
            $evento->tipo = "Info";
            $evento->save();

            $turno->nameEmployee = $relation->nameEmployee;
            $turno->nameDepartament = $relation->nameDepartament;
            $turno->nameSchedule = $relation->nameSchedule;

            return $turno;

        }else{

            $turno->save = false;
            $evento = new Event();
            $evento->evento = "Ocurrió un error al intentar crear un nuevo turno";
            $evento->tipo = "Error";
            $evento->save();

            return $turno;
        }
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $turno = Turn::find($id);

        if($request->relationship <> 0 && $request->schedule <> 0 && $request->inicio <> null && $request->fin <> null){

            $turno->relationship_id = $request->relationship;
            $turno->schedule_id = $request->schedule;
            $turno->inicio = $request->inicio;
            $turno->fin = $request->fin;

            $turno->save();
            $turno->save = true;

            $relation = DB::table('relationships')
            ->join('employees', 'employees.id', '=', 'relationships.employee_id')
            ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
            ->join('schedules', 'schedules.id', '=', 'relationships.schedule_id')
            ->where('relationships.id',$request->relationship)
            ->select('relationships.*','employees.nombre as nameEmployee','departaments.nombre as nameDepartament','schedules.nombre as nameSchedule')
            ->OrderBy('employees.nombre','asc')
            ->first();

            $evento = new Event();
            $evento->evento = "Se modificó un turno para el funcionario " . $relation->nameEmployee . " en el (la) " . $relation->nameDepartament;
            $evento->tipo = "Info";
            $evento->save();

            $turno->nameEmployee = $relation->nameEmployee;
            $turno->nameDepartament = $relation->nameDepartament;
            $turno->nameSchedule = $relation->nameSchedule;

            return $turno;

        }else{

            $turno->save = false;
            $evento = new Event();
            $evento->evento = "Ocurrió un error al intentar modificar el turno ID " . $turno->id;
            $evento->tipo = "Error";
            $evento->save();

            return $turno;
        }
    }


    public function destroy($id)
    {
        $turno = Turn::find($id);
        $turno->delete();
    }
}
