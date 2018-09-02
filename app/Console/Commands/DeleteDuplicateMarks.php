<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mark;
use App\Schedule;
use App\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteDuplicateMarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marks:delete:duplicados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borra registros de marcas duplicadas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hoy = date("Y") . "-" . date("m") . "-" . date("d");
        // Devuelve todas las marcas de hoy
        $marcas_hoy = DB::table('marks')
        ->where('fecha', $hoy)
        ->get();


        foreach ($marcas_hoy as $key => $value) {
            
            $marcas_dupli = DB::table('marks')
            ->where('fecha', $hoy)
            ->where('hora', $marcas_hoy[$key]->hora)
            ->where('codigo', $marcas_hoy[$key]->codigo)
            ->get();
           

            if(count($marcas_dupli) > 1){
                $marcas_dupli = DB::table('marks')
                ->where('fecha', $hoy)
                ->where('hora', $marcas_hoy[$key]->hora)
                ->where('codigo', $marcas_hoy[$key]->codigo)
                ->where('id',"<>",$marcas_hoy[$key]->id)
                ->delete();

                Log::info("Eliminando marcas duplicadas");
            }


        }
    }
}
