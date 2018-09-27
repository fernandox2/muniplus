<?php

use Illuminate\Database\Seeder;

class TypeExceptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\TypeException::create([
        	'name' => 'Permiso Administrativo'
        ]);

        App\TypeException::create([
        	'name' => 'Licencia Médica'
        ]);

        App\TypeException::create([
        	'name' => 'Permiso Sin Goce de Remuneraciones'
        ]);

        App\TypeException::create([
        	'name' => 'Pre Natal'
        ]);

        App\TypeException::create([
        	'name' => 'Post Natal'
        ]);

        App\TypeException::create([
        	'name' => 'Feriado Legal'
        ]);

    }
}
