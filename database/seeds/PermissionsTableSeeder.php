<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::create([
        	'name'			=> 'Navegar Departamentos',
        	'slug'			=> 'departaments.index',
        	'description'	=> 'Lista y navega por los departamentos del sistema',
        ]);

        Permission::create([
        	'name'			=> 'Crear Departamentos',
        	'slug'			=> 'departaments.store',
        	'description'	=> 'Crear nuevos departamentos',
        ]);

        Permission::create([
        	'name'			=> 'Eliminar Departamentos',
        	'slug'			=> 'departaments.destroy',
        	'description'	=> 'Eliminar departamentos',
        ]);
    }
}
