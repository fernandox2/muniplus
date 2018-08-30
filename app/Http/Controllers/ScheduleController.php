<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use Illuminate\Support\Facades\DB;

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
            $horario = new Schedule();

            $nombre = $request->nombre;
            $horario->nombre = $nombre;
            $horario->save();

            return $horario;
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
}
