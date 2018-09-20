<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Relationship;

class RevisarTurnos extends Command
{

    protected $signature = 'Turns:Check';


    protected $description = 'Asigna los horarios de acuerdo a lo indicado en la table turnos.';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $hoy = date("Y") . "-" . date("m") . "-" . date("d");
        // Devuelve todos los turnos
        $turnos = DB::table('turns')
        ->get();

        foreach ($turnos as $key => $value) {
            
           if ($hoy >= $value->inicio && $hoy <= $value->fin){

                $relation = Relationship::find($value->relationship_id);
                $relation->schedule_id = $value->schedule_id;
                $relation->save();
           }
        }
    }
}
