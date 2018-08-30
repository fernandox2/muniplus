<?php

use Illuminate\Database\Seeder;

class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Day::create([
        	'dia' => 'Lunes'
        ]);

        App\Day::create([
        	'dia' => 'Martes'
        ]);

        App\Day::create([
        	'dia' => 'Miércoles'
        ]);

        App\Day::create([
        	'dia' => 'Jueves'
        ]);

        App\Day::create([
        	'dia' => 'Viernes'
        ]);

        App\Day::create([
        	'dia' => 'Sábado'
        ]);

        App\Day::create([
        	'dia' => 'Domingo'
        ]);
    }
}
