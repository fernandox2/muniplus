<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Assistance;
use App\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CalculaHorasTrabajadas extends Command
{

    protected $signature = 'marks:update:hrs';


    protected $description = 'Calcula las horas trabajadas';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        function resta($inicio, $fin)
        {
            $dif = date("H:i:s", strtotime("00:00:00") + strtotime($fin) - strtotime($inicio) );
            return $dif;
        }

        $consolidados_no_calculados = DB::table('assistances')
        ->where('calculada', false)
        ->get();

        foreach ($consolidados_no_calculados as $i => $con) {

            if(isset($con->entrada1) && isset($con->salida2)){

                $horas_trabajadas = resta($con->entrada1, $con->salida2);
                
                $cons = Assistance::find($con->id);
                $cons->horas_trabajadas = $horas_trabajadas;
                $cons->calculada = true;
                $cons->save();
                Log::info("Se calcularon ". $horas_trabajadas . " de trabajo para el consolidado " . $con->id);

            }elseif(isset($con->entrada1) && isset($con->salida1)){

                $horas_trabajadas = resta($con->entrada1, $con->salida1);
                $cons = Assistance::find($con->id);
                $cons->horas_trabajadas = $horas_trabajadas;
                $cons->calculada = true;
                $cons->save();
                Log::info("Se calcularon ". $horas_trabajadas . " de trabajo para el consolidado " . $con->id);

            }elseif(isset($con->entrada2) && isset($con->salida2)){

                $horas_trabajadas = resta($con->entrada2, $con->salida2);
                $cons = Assistance::find($con->id);
                $cons->horas_trabajadas = $horas_trabajadas;
                $cons->calculada = true;
                $cons->save();
                Log::info("Se calcularon ". $horas_trabajadas . " de trabajo para el consolidado " . $con->id);

            }else{
                
                $employee = DB::table('employees')
                ->where('codigo',$con->codigo)
                ->first();

                $cons = Assistance::find($con->id);
                $cons->comentario = "El funcionario " . $employee->nombre ." no tiene todas sus marcas hoy";
                $cons->calculada = true;
                $cons->save();

                $evento = new Event();
                $evento->evento = "El funcionario " . $employee->nombre ." no tiene todas sus marcas hoy " . $con->fecha;
                $evento->tipo = "Error";
                $evento->save();
                Log::info("El regsitro no tiene todas las marcas");
            }

            // Envia correo con asistencia el día
            $asistencia = DB::table('assistances')
            ->join('employees','employees.codigo','=','assistances.codigo')
            ->where('assistances.id',$con->id)
            ->first();

            if($asistencia->correo <> ""){
                Mail::to($asistencia->correo)->send(new \App\Mail\EnvioAsistencia($asistencia));
            }else{
                $evento = new Event();
                $evento->evento = "El funcionario " . $employee->nombre ." no tiene correo electrónico asociado.";
                $evento->tipo = "Error";
                $evento->save();
            }
            
            
        }


    }
}
