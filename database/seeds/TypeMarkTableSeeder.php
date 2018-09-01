<?php

use Illuminate\Database\Seeder;

class TypeMarkTableSeeder extends Seeder
{

    public function run()
    {
        App\TypeMark::create([
        	'nombre' => 'Entrada 1'
        ]);

        App\TypeMark::create([
        	'nombre' => 'Salida 1'
        ]);

        App\TypeMark::create([
        	'nombre' => 'Entrada 2'
        ]);

        App\TypeMark::create([
        	'nombre' => 'Salida 2'
        ]);

        App\TypeMark::create([
        	'nombre' => 'Inicio Trabajo Extraordinario 1'
        ]);

        App\TypeMark::create([
        	'nombre' => 'Término Trabajo Extraordinario 1'
        ]);

        App\TypeMark::create([
        	'nombre' => 'Inicio Trabajo Extraordinario 2'
        ]);

        App\TypeMark::create([
        	'nombre' => 'Fin Trabajo Extraordinario 2'
        ]);

        App\TypeMark::create([
            'nombre' => 'Sin procesar'
        ]);
    }
}
