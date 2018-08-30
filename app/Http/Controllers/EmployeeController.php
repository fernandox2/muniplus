<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function index()
    {
        $cursos = DB::table('employees')
                    ->OrderBy('nombre','asc')
                    ->get();

        return $cursos;
    }


    public function store(Request $request)
    {
        $empleado = new Employee();

        $empleado->rut = $request->rut;
        $empleado->nombre = $request->nombre;
        $empleado->correo = $request->correo;
        $empleado->codigo = $request->codigo;

        if($request->rut <> "" || $request->nombre <> "" || $request->codigo <> 0)
        {
            $empleado->save();
            $empleado->save = true;
            return $empleado;
        }else{
            $empleado->save = false;
            return $empleado;
        }
    }


    public function show($id)
    {
        //return view('employees/index');
    }

    public function update(Request $request, $id)
    {
        $empleado = Employee::find($id);

        $empleado->rut = $request->rut;
        $empleado->nombre = $request->nombre;
        $empleado->correo = $request->correo;
        $empleado->codigo = $request->codigo;

        if($request->rut <> "" || $request->nombre <> "" || $request->codigo <> 0)
        {
            $empleado->save();
            $empleado->save = true;
            return $empleado;
        }else{
            $empleado->save = false;
            return $empleado;
        }
    }

    public function destroy($id)
    {
        $empleado = Employee::find($id);
        $empleado->delete();
    }
}
