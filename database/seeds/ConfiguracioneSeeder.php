<?php

use Illuminate\Database\Seeder;

class ConfiguracioneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuracione::insert([
            [
                'descripcion' => 'generacionCodigos',
                'valor' => 'Si'
            ],
            [
                'descripcion' => 'comboEliminaVenta',
                'valor' => 'Cliente molesto'
            ],
            [
                'descripcion' => 'comboEliminaVenta',
                'valor' => 'Autorizacion Jefe'
            ],
            [
                'descripcion' => 'comboCambiaProductoVenta',
                'valor' => 'Producto defectuoso'
            ],
            [
                'descripcion' => 'ComboCambiaProductoVenta',
                'valor' => 'Producto roto'
            ]
        ]);
    }
}
