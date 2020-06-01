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
          [
            'name'=>'Administrador',
            'rol'=>'Administrador',
            'almacen_id'=> 1,
            'email'=>'admin@sazez.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Publico General',
            'rol'=>'Cliente',
            'almacen_id'=> null,
            'email'=>'cliente@cliente.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Tienda Prado',
            'rol'=>'Tienda',
            'almacen_id'=> 2,
            'email'=>'tienda1@tienda1.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Tienda Villa Fatima',
            'rol'=>'Tienda',
            'almacen_id'=> 3,
            'email'=>'tienda2@tienda2.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Tienda Buenos Aires',
            'rol'=>'Tienda',
            'almacen_id'=> 4,
            'email'=>'tienda3@tienda3.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 1',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente1@cliente1.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 2',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente2@cliente2.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 3',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente3@cliente3.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 4',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente4@cliente4.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 5',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente5@cliente5.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 6',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente6@cliente6.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 7',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente7@cliente7.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 8',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente8@cliente8.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 9',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente9@cliente9.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 10',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente10@cliente10.net',
            'password'=> bcrypt('123456789')
          ],
          [
            'name'=>'Cliente 11',
            'rol'=>'Cliente',
            'almacen_id'=>null,
            'email'=>'cliente11@cliente11.net',
            'password'=> bcrypt('123456789')
          ],
        ]);
    }
}
