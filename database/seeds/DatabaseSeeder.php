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
        $this->call(AlmacenesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EscalasTableSeeder::class);
    }
}