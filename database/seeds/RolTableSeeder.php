<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Role;

class RolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
        	'name'			=> 'Administrador',
        	'slug'			=> 'admin',
        	'description'	=> 'Administrador del Sistema',
        	'special'	=> 'all-access',
        ]);
    }
}
