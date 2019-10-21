<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $roles  = ['administrador', 'operador','desarrollador'];

        foreach ($roles as $key => $role) {
           $n_role = new Role;
           $n_role->name = $role;
           $n_role->save();
        }
    }
}
