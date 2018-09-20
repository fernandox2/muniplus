<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use App\Event;
use App\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{

    public function index()
    {
        $schedules = DB::table('schedules')
                    ->OrderBy('nombre','asc')
                    ->get();

        return $schedules;
    }


    public function store(Request $request)
    {
        if(count($request->HorarioLunes) % 2 == 0 && count($request->HorarioMartes) % 2 == 0 && count($request->HorarioMiercoles) % 2 == 0 && count($request->HorarioJueves) % 2 == 0 && count($request->HorarioViernes) % 2 == 0 && count($request->HorarioSabado) % 2 == 0 && count($request->HorarioDomingo) % 2 == 0)
        {
            // Agregar Horario
            $horario = new Schedule();
            $horario->nombre  = $request->nombre;
            $horario->save();
            // Agregar programas del horario
            for ($i = 1; $i <= 7; $i++) {
                switch (count($request->todos[$i - 1])) {
                    case 0:
                        break;
                    case 2:
                        $program = new Program();
                        $program->entrada1 = $request->todos[$i - 1][0];
                        $program->salida1 = $request->todos[$i - 1][1];
                        $program->dia_id = $i;
                        $program->schedule_id = $horario->id;
                        $program->save();
                        break;
                    case 4:
                        $program = new Program();
                        $program->entrada1 = $request->todos[$i - 1][0];
                        $program->salida1 = $request->todos[$i - 1][1];
                        $program->entrada2 = $request->todos[$i - 1][2];
                        $program->salida2 = $request->todos[$i - 1][3];
                        $program->dia_id = $i;
                        $program->schedule_id = $horario->id;
                        $program->save();
                        break;
                    default:
                        break;
                }
            }
            $horario->save = true;

            $evento = new Event();
            $evento->evento = "Se creó correctamente el horario " . $horario->nombre;
            $evento->tipo = "Info";
            $evento->save();

            return $horario;
        }
        $horario->save = false;
        return $horario;
    }


    public function findPrograms($id)
    {
        $programs = DB::table('programs')
            ->where('schedule_id',$id)
            ->get();

        return $programs;
    }


    public function update(Request $request, $id)
    {


        if(count($request->HorarioLunes) % 2 == 0 && count($request->HorarioMartes) % 2 == 0 && count($request->HorarioMiercoles) % 2 == 0 && count($request->HorarioJueves) % 2 == 0 && count($request->HorarioViernes) % 2 == 0 && count($request->HorarioSabado) % 2 == 0 && count($request->HorarioDomingo) % 2 == 0)
        {

            //Agregar Horario
            $horario = Schedule::find($id);
            $horario->nombre  = $request->nombre;
            $horario->save();

            //Eliminar programas del horario
            $programs = DB::table('programs')
            ->where('schedule_id',$id)
            ->delete();

            $cant_reg_borr = DB::table('programs')
            ->where('schedule_id',$id)
            ->get();

            if(count($cant_reg_borr) == 0){

                // Agregar programas del horario
                for ($i = 1; $i <= 7; $i++) {
                    switch (count($request->todos[$i - 1])) {
                        case 0:
                            break;
                        case 2:
                            $program = new Program();
                            $program->entrada1 = $request->todos[$i - 1][0];
                            $program->salida1 = $request->todos[$i - 1][1];
                            $program->dia_id = $i;
                            $program->schedule_id = $horario->id;
                            $program->save();
                            break;
                        case 4:
                            $program = new Program();
                            $program->entrada1 = $request->todos[$i - 1][0];
                            $program->salida1 = $request->todos[$i - 1][1];
                            $program->entrada2 = $request->todos[$i - 1][2];
                            $program->salida2 = $request->todos[$i - 1][3];
                            $program->dia_id = $i;
                            $program->schedule_id = $horario->id;
                            $program->save();
                            break;
                        default:
                            break;
                    }
                }

                $horario->save = true;
                $evento = new Event();
                $evento->evento = "Se modificó correctamente el horario " . $horario->nombre;
                $evento->tipo = "Info";
                $evento->save();
                return $horario;

            }

        }
        
        $horario->save = false;
        return $horario;
    }

    public function destroy($id)
    {
        $cant_reg = DB::table('relationships')
        ->where('schedule_id',$id)
        ->get();
        
        if(count($cant_reg) == 0){
            $schedule = Schedule::find($id);
            $schedule->delete();
        }

        $evento = new Event();
        $evento->evento = "Existen datos asociados al horario por lo que no se puede eliminar";
        $evento->tipo = "Error";
        $evento->save();
    }
}
