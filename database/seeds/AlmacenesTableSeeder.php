<?php

use App\Almacene;
use Illuminate\Database\Seeder;

class AlmacenesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Almacene::insert([
            'user_id' => 1,
            'nombre' => 'Almacen Central',
            'direccion' => 'Calle Uno',
        ]);
    }
}
