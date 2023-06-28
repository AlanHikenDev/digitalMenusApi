<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Soporte',
            'email' => 'soporte@gmail.com',
            'password' => Hash::make('ferlan'),
            'rol' => 'admin',
            'nombre' => 'Digital Menus',
            'a_paterno' => 'soporte',
            'a_materno' => 'soporte',
        ]);

        DB::table('users')->insert([
            'name' => 'soporte',
            'email' => 'cliente@gmail.com',
            'password' => Hash::make('soporte'),
            'rol' => 'cliente',
            'nombre' => 'soporte',
            'a_paterno' => 'soporte',
            'a_materno' => 'soporte',
        ]);
    }
}
