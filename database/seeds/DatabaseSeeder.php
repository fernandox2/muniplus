<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
           $this->call(TypeMarkTableSeeder::class);
           $this->call(DaysTableSeeder::class);
           $this->call(PermissionsTableSeeder::class);
           $this->call(RolTableSeeder::class);
    }
}
