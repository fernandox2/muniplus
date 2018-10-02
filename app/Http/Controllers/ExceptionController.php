<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exception;
use App\Event;
use App\Mark;
use Illuminate\Support\Facades\DB;

class ExceptionController extends Controller
{

    public function index()
    {
        $excepciones = DB::table('exceptions')
        ->join('employees', 'employees.id', '=', 'exceptions.employee_id')
        ->join('type_exceptions', 'type_exceptions.id', '=', 'exceptions.type_exception_id')
        ->select('exceptions.*','employees.nombre as nombreFuncionario','type_exceptions.name as nombreTipo')
        ->OrderBy('exceptions.created_at','asc')
        ->get();

        return $excepciones;
    }


    public function store(Request $request)
    {
        $exception = new Exception();

        $exception->employee_id = $request->relationship;
        $exception->fecha_inicio = $request->inicio;
        $exception->fecha_fin = $request->fin;
        $exception->type_exception_id = $request->tipo;
        $exception->glosa = $request->glosa;

        if($request->relationship != 0 && $request->inicio <= $request->fin && $request->tipo != 0)
        {

            $funcionario = DB::table('employees')
            ->where('id',$exception->employee_id)
            ->first();

           $exception->save();
           $exception->save = true;


           switch ($request->tipo) 
           {
               
               case 1:
                
                $fechaInicio=strtotime($request->inicio);
                $fechaFin=strtotime($request->fin);

                for($i=$fechaInicio; $i<=$fechaFin - 86400; $i+=86400)
                {
                    
                    $fecha = date('Y-m-j', $i);
                    list($anio, $mes, $dia) = split('[/.-]', $fecha);
                    $jd=gregoriantojd($mes,$dia,$anio);
                    $dianumero = jddayofweek($jd,0);
                    $programa = DB::table('programs')
                    ->join('schedules','schedules.id','=','programs.schedule_id')
                    ->join('relationships','relationships.schedule_id','=','schedules.id')
                    ->join('employees', 'employees.id', '=', 'relationships.employee_id')
                    ->select('programs.*')
                    ->where('employees.id','=',$funcionario->id)
                    ->where('programs.dia_id','=',$dianumero)
                    ->first();

                    if(!$programa->isEmpty())
                    {

                        if($programa->entrada1 != null)
                        {
                            $marca1 = new Mark();
                            $marca1->codigo = $funcionario->codigo;
                            $marca1->type_mark_id = 9;               
                            $marca1->fecha = $fecha;                 
                            $marca1->hora = $programa->entrada1;
                            $marca1->dia = $dianumero;
                            $marca1->comentario = "Permiso Administrativo"
                            $marca1->save();
                        }
                        
                        if($programa->salida1 != null)
                        {
                            $marca2 = new Mark();
                            $marca2->codigo = $funcionario->codigo;
                            $marca2->type_mark_id = 9;               
                            $marca2->fecha = $fecha;                 
                            $marca2->hora = $programa->salida1;
                            $marca2->dia = $dianumero;
                            $marca2->comentario = "Permiso Administrativo"
                            $marca2->save();
                        }

                        if($programa->entrada2 != null)
                        {
                            $marca1 = new Mark();
                            $marca1->codigo = $funcionario->codigo;
                            $marca1->type_mark_id = 9;               
                            $marca1->fecha = $fecha;                 
                            $marca1->hora = $programa->entrada2;
                            $marca1->dia = $dianumero;
                            $marca1->comentario = "Permiso Administrativo"
                            $marca1->save();
                        }

                        if($programa->salida2 != null)
                        {
                            $marca2 = new Mark();
                            $marca2->codigo = $funcionario->codigo;
                            $marca2->type_mark_id = 9;               
                            $marca2->fecha = $fecha;                 
                            $marca2->hora = $programa->salida2;
                            $marca2->dia = $dianumero;
                            $marca2->comentario = "Permiso Administrativo"
                            $marca2->save();
                        }
                    }
                }
           
                $exception->nombre = $funcionario->nombre;

                return $exception;
                   break;
           }

        }else{

            $funcionario = DB::table('employees')
            ->where('id',$exception->employee_id)
            ->first();

            $evento = new Event();
            $evento->evento = "Error al crear una excepción para el funcionario " . $funcionario->nombre;
            $evento->tipo = "Error";
            $evento->save();

            $exception->nombre = $funcionario->nombre;

            return $exception;

        }
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


    public function tipos()
    {
        $tipos = DB::table('type_exceptions')
        ->OrderBy('name','asc')
        ->get();

        return $tipos;
    }


}
