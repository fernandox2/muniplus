<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Relationship;
use Illuminate\Support\Facades\DB;

class RelationshipController extends Controller
{

    public function index()
    {
        $relations = DB::table('relationships')
        ->join('employees', 'employees.id', '=', 'relationships.employee_id')
        ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
        ->join('schedules', 'schedules.id', '=', 'relationships.schedule_id')
        ->select('relationships.*','employees.nombre as nameEmployee','departaments.nombre as nameDepartament','schedules.nombre as nameSchedule')
        ->OrderBy('employees.nombre','asc')
        ->get();

        return $relations;
    }

    public function store(Request $request)
    {

        $relation = new Relationship();

        $exist_relations = DB::table('relationships') // Revisa si el funcionario está ya en el departamento
        ->where('employee_id', $request->employee)
        ->Where('departament_id', $request->departament)
        ->get(); 

        $relations_exist = count($exist_relations);

        if($request->employee <> 0 && $request->departament <> 0 && $request->schedule <> 0 && $relations_exist == 0){

            $relation->employee_id = $request->employee; 
            $relation->departament_id = $request->departament; 
            $relation->schedule_id = $request->schedule;
            $relation->turn = $request->turn;

            $relation->save();

            $relation->save = true;
            
            return $relation;        
        }

        $relation->save = false;

        return $relation;
    }


    public function update(Request $request, $id)
    {
        $relation = Relationship::find($id);

        if($request->employee <> 0 && $request->departament <> 0 && $request->schedule <> 0){

            $relation->employee_id = $request->employee; 
            $relation->departament_id = $request->departament; 
            $relation->schedule_id = $request->schedule;
            $relation->turn = $request->turn;

            $relation->save();

            $relation->save = true;
            
            return $relation;        
        }

        $relation->save = false;

        return $relation;
    }


    public function destroy($id)
    {
        $relation = Relationship::find($id);
        $relation->delete();
    }
}
