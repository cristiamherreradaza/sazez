<?php

use App\Escala;
use Illuminate\Database\Seeder;

class EscalasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // se aidciona la escala tienda
        Escala::insert([
            'user_id' => 1,
            'nombre' => 'Tienda',
        ]);
    }
}
