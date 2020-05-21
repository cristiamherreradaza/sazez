<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // se adiciono al administrador     
        User::insert([
            'name'=>'Administrador',
            'rol'=>'Administrador',
            'almacen_id'=> 1,
            'email'=>'admin@sazez.net',
            'password'=> bcrypt('123456789'),
        ]);
    }
}