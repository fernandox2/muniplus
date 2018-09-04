<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assistance;
use Illuminate\Support\Facades\DB;

class AssistanceController extends Controller
{

    public function index()
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->where('calculada',true)
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }

    public function filtrarFechas($inicio, $fin)
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->where('calculada',true)
        ->whereBetween('assistances.fecha', [$inicio, $fin])
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }



    public function filtrarFuncionarios($codigo)
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->where('calculada',true)
        ->where('employees.id',$codigo)
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }

    public function filtrarFuncionariosFechas($codigo, $inicio, $fin)
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->where('calculada',true)
        ->where('employees.id',$codigo)
        ->whereBetween('assistances.fecha', [$inicio, $fin])
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }

    public function filtrarFuncionariosFechasDepartamentos($codigo, $inicio, $fin, $departamento)
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->join('relationships', 'relationships.employee_id', '=', 'employees.id')
        ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
        ->where('calculada',true)
        ->where('departaments.id',$departamento)
        ->where('employees.id',$codigo)
        ->whereBetween('assistances.fecha', [$inicio, $fin])
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }

    public function filtrarDepartamento($departamento)
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->join('relationships', 'relationships.employee_id', '=', 'employees.id')
        ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
        ->where('calculada',true)
        ->where('departaments.id',$departamento)
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }

    public function filtrarDepartamentoFuncionario($codigo, $departamento)
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->join('relationships', 'relationships.employee_id', '=', 'employees.id')
        ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
        ->where('calculada',true)
        ->where('departaments.id',$departamento)
        ->where('employees.codigo',$codigo)
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }

    public function filtrarDepartamentosFechas($inicio, $fin, $departamento)
    {
        $con = DB::table('assistances')
        ->join('employees', 'employees.codigo', '=', 'assistances.codigo')
        ->join('relationships', 'relationships.employee_id', '=', 'employees.id')
        ->join('departaments', 'departaments.id', '=', 'relationships.departament_id')
        ->where('calculada',true)
        ->where('departaments.id',$departamento)
        ->whereBetween('assistances.fecha', [$inicio, $fin])
        ->select('assistances.*','employees.nombre as funcionario')
        ->OrderBy('assistances.fecha','desc')
        ->get();

        return $con;
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
