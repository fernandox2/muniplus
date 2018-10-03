<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exception;
use App\Event;
use App\Mark;
use App\Assistance;
use App\TypeException;
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

                for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
                {
                    
                    $fecha = date('Y-m-j', $i);          
                    $valores = explode("-", $fecha);
                    $jd=gregoriantojd($valores[1],$valores[2],$valores[0]);
                    $dianumero = jddayofweek($jd,0);
                    if($dianumero == 0){ $dianumero = 7; }
                
                    $programa = DB::table('programs')
                    ->join('schedules','schedules.id','=','programs.schedule_id')
                    ->join('relationships','relationships.schedule_id','=','schedules.id')
                    ->join('employees', 'employees.id', '=', 'relationships.employee_id')
                    ->select('programs.*')
                    ->where('employees.id','=',$funcionario->id)
                    ->where('programs.dia_id','=',$dianumero)
                    ->first();

                    if($i == $fechaFin && $request->radio == true && $request->mediodia != 0 && isset($programa))
                    {
                        if($request->mediodia == 1)
                        {
                            $marca1 = new Mark();
                            $marca1->codigo = $funcionario->codigo;
                            $marca1->type_mark_id = 9;
                            $marca1->hora = $programa->entrada1;
                            $marca1->fecha = $fecha;
                            $marca1->dia = $dianumero;
                            $marca1->excepcion = $exception->id;
                            $marca1->comentario = "Marca ingresada desde la Excepción N°" . $exception->id;
                            $marca1->save();

                            $marca2 = new Mark();
                            $marca2->codigo = $funcionario->codigo;
                            $marca2->type_mark_id = 9;
                            $marca2->hora = $programa->salida1;
                            $marca2->fecha = $fecha;
                            $marca2->dia = $dianumero;
                            $marca2->excepcion = $exception->id;
                            $marca2->comentario = "Marca ingresada desde la Excepción N°" . $exception->id;
                            $marca2->save();
                        }elseif ($request->mediodia == 2) 
                        {
                            $marca1 = new Mark();
                            $marca1->codigo = $funcionario->codigo;
                            $marca1->type_mark_id = 9;
                            $marca1->hora = $programa->entrada2;
                            $marca1->fecha = $fecha;
                            $marca1->dia = $dianumero;
                            $marca1->excepcion = $exception->id;
                            $marca1->comentario = "Marca ingresada desde la Excepción N°" . $exception->id;
                            $marca1->save();

                            $marca2 = new Mark();
                            $marca2->codigo = $funcionario->codigo;
                            $marca2->type_mark_id = 9;
                            $marca2->hora = $programa->salida2;
                            $marca2->fecha = $fecha;
                            $marca2->dia = $dianumero;
                            $marca2->excepcion = $exception->id;
                            $marca2->comentario = "Marca ingresada desde la Excepción N°" . $exception->id;
                            $marca2->save(); 
                        }
                    }elseif(isset($programa))
                    {
                        $consolidado = new Assistance();
                        $consolidado->codigo = $funcionario->codigo;         
                        $consolidado->fecha = $fecha;                 
                        $consolidado->entrada1 = $programa->entrada1;
                        $consolidado->salida1 = $programa->salida1;
                        $consolidado->entrada2 = $programa->entrada2;
                        $consolidado->salida2 = $programa->salida2;
                        $consolidado->calculada = true;
                        $consolidado->excepcion = $exception->id;
                        $consolidado->comentario = "Permiso Administrativo. Excepción N°" . $exception->id;
                        $consolidado->save();
                    }
                }

                $tipo = TypeException::find($request->tipo);
           
                $exception->nombreFuncionario = $funcionario->nombre;
                $exception->nombreTipo = $tipo->name;

                $evento = new Event();
                $evento->evento = "Se creó una nueva excepción para el funcionario  " . $funcionario->nombre . " con motivo de " . $tipo->name;
                $evento->tipo = "Info";
                $evento->save();

                return $exception;
                   break;
            default:
                
                $fechaInicio=strtotime($request->inicio);
                $fechaFin=strtotime($request->fin);

                for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
                {
                    
                    $fecha = date('Y-m-j', $i);          
                    $valores = explode("-", $fecha);
                    $jd=gregoriantojd($valores[1],$valores[2],$valores[0]);
                    $dianumero = jddayofweek($jd,0);
                    if($dianumero == 0){ $dianumero = 7; }
                
                    $programa = DB::table('programs')
                    ->join('schedules','schedules.id','=','programs.schedule_id')
                    ->join('relationships','relationships.schedule_id','=','schedules.id')
                    ->join('employees', 'employees.id', '=', 'relationships.employee_id')
                    ->select('programs.*')
                    ->where('employees.id','=',$funcionario->id)
                    ->where('programs.dia_id','=',$dianumero)
                    ->first();

                    $tipo = TypeException::find($request->tipo);

                    if(isset($programa))
                    {
                        $consolidado = new Assistance();
                        $consolidado->codigo = $funcionario->codigo;         
                        $consolidado->fecha = $fecha;                 
                        $consolidado->entrada1 = $programa->entrada1;
                        $consolidado->salida1 = $programa->salida1;
                        $consolidado->entrada2 = $programa->entrada2;
                        $consolidado->salida2 = $programa->salida2;
                        $consolidado->calculada = true;
                        $consolidado->excepcion = $exception->id;
                        $consolidado->comentario = $tipo->name .". Excepción N°" . $exception->id;
                        $consolidado->save();
                    }
                }

                $tipo = TypeException::find($request->tipo);
          
                $exception->nombreFuncionario = $funcionario->nombre;
                $exception->nombreTipo = $tipo->name;

                $evento = new Event();
                $evento->evento = "Se creó una nueva excepción para el funcionario  " . $funcionario->nombre . " con motivo de " . $tipo->name;
                $evento->tipo = "Info";
                $evento->save();

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
        $consolidados = DB::table('assistances')
        ->where('excepcion', '=',$id)
        ->delete();

        $marcas = DB::table('marks')
        ->where('excepcion', '=', $id)
        ->delete();

        $exception = Exception::find($id);
        $exception->delete();
    }


    public function tipos()
    {
        $tipos = DB::table('type_exceptions')
        ->OrderBy('name','asc')
        ->get();

        return $tipos;
    }


}
