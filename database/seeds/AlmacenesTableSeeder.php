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
            ],
            [
                'nombre' => 'El Prado',
                'direccion' => 'Calle Uno',
            ],
            [
                'nombre' => 'Villa Fatima',
                'direccion' => 'Calle Uno',
            ],
            [
                'nombre' => 'Buenos Aires',
                'direccion' => 'Calle Uno',
            ],
        ]);

        
    }
}
