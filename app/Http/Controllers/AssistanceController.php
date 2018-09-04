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
