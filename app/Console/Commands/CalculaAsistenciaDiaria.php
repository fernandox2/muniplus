<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mark;
use App\Schedule;
use App\Program;
use App\Assistance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculaAsistenciaDiaria extends Command
{

    protected $signature = 'marks:create:consolidados';


    protected $description = 'Calcula las marcas de un funcionario por día';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        // Obtiene marcas no calculadas
        $marcas_hoy = DB::table('marks')
        ->where('calculada', false)
        ->where('type_mark_id','<>',9)
        ->get();

        //Recorre array y calcula marcas
        foreach ($marcas_hoy as $i => $marca) {

            $marca_calc = Mark::find($marca->id);
            $marca_calc->calculada = true;
            $marca_calc->save();

            $consolidado = DB::table('assistances')
            ->where('fecha',$marca->fecha)
            ->where('codigo',$marca->codigo)
            ->get();

            switch ($marca->type_mark_id) {
                case 1:
                    if(count($consolidado) == 0){
                        $ass = new Assistance();
                        $ass->codigo = $marca->codigo;
                        $ass->fecha = $marca->fecha;
                        $ass->entrada1 = $marca->hora;
                        $ass->save();
                    }elseif (!isset($consolidado->entrada1)) {
                        $ass = Assistance::find($consolidado[0]->id);
                        $ass->entrada1 = $marca->hora;
                        $ass->save();
                    }  
                    break;
                case 2:
                    if(count($consolidado) == 0){
                        $ass = new Assistance();
                        $ass->codigo = $marca->codigo;
                        $ass->fecha = $marca->fecha;
                        $ass->salida1 = $marca->hora;
                        $ass->save();
                    }elseif (!isset($consolidado->salida1)) {
                        $ass = Assistance::find($consolidado[0]->id);
                        $ass->salida1 = $marca->hora;
                        $ass->save();
                    }  
                    break;
                case 3:
                    if(count($consolidado) == 0){
                        $ass = new Assistance();
                        $ass->codigo = $marca->codigo;
                        $ass->fecha = $marca->fecha;
                        $ass->entrada2 = $marca->hora;
                        $ass->save();
                    }elseif (!isset($consolidado->entrada2)) {
                        $ass = Assistance::find($consolidado[0]->id);
                        $ass->entrada2 = $marca->hora;
                        $ass->save();
                    }  
                    break;
                case 4:
                    if(count($consolidado) == 0){
                        $ass = new Assistance();
                        $ass->codigo = $marca->codigo;
                        $ass->fecha = $marca->fecha;
                        $ass->salida2 = $marca->hora;
                        $ass->save();
                    }elseif (!isset($consolidado->salida2)) {
                        $ass = Assistance::find($consolidado[0]->id);
                        $ass->salida2 = $marca->hora;
                        $ass->save();
                    }  
                    break;
                default:
                    break;
            }
        }
    }
}
