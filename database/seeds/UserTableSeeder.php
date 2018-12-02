<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::create([
        	'name' => 'Saif Zakir',
        	'email' => 'saif@gmail.com',
        	'password' => bcrypt('123456'),
            'type' => 'admin',
            'photo' => '1539351868.png'
        ]);
    }
}
