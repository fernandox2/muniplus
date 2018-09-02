<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mark;
use App\Schedule;
use App\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcesaMarcas extends Command
{

    protected $signature = 'marks:update:types';


    protected $description = 'Procesa marcas para verificar sus tipos';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        function getMenor($marca, $entrada1, $salida1, $entrada2, $salida2 )
        {
            if(isset($entrada1)){

                $dif_entrada1 = abs(strtotime($entrada1) - strtotime($marca));

            }else{

                $dif_entrada1 = 999999999;
            }

            if(isset($salida1)){

                $dif_salida1 = abs(strtotime($salida1) - strtotime($marca));

            }else{

                $dif_salida1 = 999999999;
            }
            
            if(isset($entrada2)){

                $dif_entrada2 = abs(strtotime($entrada2) - strtotime($marca));

            }else{

                $dif_entrada2 = 999999999;
            }
            
            if(isset($salida2)){

                $dif_salida2 = abs(strtotime($salida2) - strtotime($marca));

            }else{

                $dif_salida2 = 999999999;
            }

            $menor = min($dif_entrada1,$dif_salida1,$dif_entrada2,$dif_salida2);

            return $menor;
        }

        function buscaTipoMarca($marca, $entrada1, $salida1, $entrada2, $salida2 )
        {
            if(isset($entrada1)){

                $dif_entrada1 = abs(strtotime($entrada1) - strtotime($marca));

            }else{

                $dif_entrada1 = 999999999;
            }

            if(isset($salida1)){

                $dif_salida1 = abs(strtotime($salida1) - strtotime($marca));

            }else{

                $dif_salida1 = 999999999;
            }
            
            if(isset($entrada2)){

                $dif_entrada2 = abs(strtotime($entrada2) - strtotime($marca));

            }else{

                $dif_entrada2 = 999999999;
            }
            
            if(isset($salida2)){

                $dif_salida2 = abs(strtotime($salida2) - strtotime($marca));

            }else{

                $dif_salida2 = 999999999;
            }

            $menor = min($dif_entrada1,$dif_salida1,$dif_entrada2,$dif_salida2);

            switch ($menor) {
                case $dif_entrada1:
                    return 1;
                    break;
                case $dif_salida1:
                    return 2;
                    break;
                case $dif_entrada2:
                    return 3;
                    break;
                case $dif_salida2:
                    return 4;
                    break;
                default:
                    return 0;
                    break;
            }

        }

        // Devuelve todas las marcas que no han sido procesadas
        $no_procesadas = DB::table('marks')
        ->where('type_mark_id', 9)
        ->get();

        for($i = 0; $i <= count($no_procesadas) - 1; $i++){           
            // Definir que usuario es
            $employee = DB::table('employees')
            ->where('codigo', $no_procesadas[$i]->codigo)
            ->first();
            if(isset($employee)){
            // Definir el horario del empleado sin turnos
            $programs = DB::table('relationships')
            ->join('schedules', 'schedules.id', '=', 'relationships.schedule_id')
            ->join('programs', 'schedules.id', '=', 'programs.schedule_id')
            ->select('relationships.*','programs.*','schedules.nombre as nombreHorario')
            ->where('relationships.employee_id', $employee->id)
            ->where('programs.dia_id',$no_procesadas[$i]->dia)
            ->get();

            // Si solo tiene un horario
            if(count($programs) == 1){

                $entrada1 = $programs[0]->entrada1;
                $salida1 = $programs[0]->salida1;
                $entrada2 = $programs[0]->entrada2;
                $salida2 = $programs[0]->salida2;

                $marca = $no_procesadas[$i]->hora;
                // Busca el tipo de marca
                $tipo_marca = buscaTipoMarca($marca,$entrada1, $salida1, $entrada2, $salida2);
                // Busca la marca
                $mark = Mark::find($no_procesadas[$i]->id);
                // Asigna el nuevo tipo
                $mark->type_mark_id = $tipo_marca;
                // Asigna Checksum
                $mark->checksum = md5($mark->id . "romero2010");
                // Guarda marca procesada
                $mark->save();

            }elseif(count($programs) == 2){

            $marca = $no_procesadas[$i]->hora;
            $men_1 = getMenor($marca,$programs[0]->entrada1,$programs[0]->salida1,$programs[0]->entrada2,$programs[0]->salida2);
            $men_2 = getMenor($marca,$programs[1]->entrada1,$programs[1]->salida1,$programs[1]->entrada2,$programs[1]->salida2);

            if($men_1 < $men_2){

                $entrada1 = $programs[0]->entrada1;
                $salida1 = $programs[0]->salida1;
                $entrada2 = $programs[0]->entrada2;
                $salida2 = $programs[0]->salida2;

                $marca = $no_procesadas[$i]->hora;
                // Busca el tipo de marca
                $tipo_marca = buscaTipoMarca($marca,$entrada1, $salida1, $entrada2, $salida2);
                // Busca la marca
                $mark = Mark::find($no_procesadas[$i]->id);
                // Asigna el nuevo tipo
                $mark->type_mark_id = $tipo_marca;
                // Asigna Checksum
                $mark->checksum = md5($mark->id . "romero2010");
                // Guarda marca procesada
                $mark->save();

            }else{

                $entrada1 = $programs[1]->entrada1;
                $salida1 = $programs[1]->salida1;
                $entrada2 = $programs[1]->entrada2;
                $salida2 = $programs[1]->salida2;

                $marca = $no_procesadas[$i]->hora;
                // Busca el tipo de marca
                $tipo_marca = buscaTipoMarca($marca,$entrada1, $salida1, $entrada2, $salida2);
                // Busca la marca
                $mark = Mark::find($no_procesadas[$i]->id);
                // Asigna el nuevo tipo
                $mark->type_mark_id = $tipo_marca;
                // Asigna Checksum
                $mark->checksum = md5($mark->id . "romero2010");
                // Guarda marca procesada
                $mark->save();
            }

            }

            }  
        }
    }
}
