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
        // se adiciona el almacen central
        Almacene::insert([
            [
                'nombre' => 'Almacen Central',
                'direccion' => 'Calle Uno',
                'mayorista' => 'No'
            ],
            [
                'nombre' => 'El Prado',
                'direccion' => 'Calle Uno',
                'mayorista' => 'No'
            ],
            [
                'nombre' => 'Villa Fatima',
                'direccion' => 'Calle Uno',
                'mayorista' => 'No'
            ],
            [
                'nombre' => 'Buenos Aires',
                'direccion' => 'Calle Uno',
                'mayorista' => 'No'
            ],
        ]);

        
    }
}
