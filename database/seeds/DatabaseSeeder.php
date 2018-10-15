<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $faker = Faker::create();
        foreach (range(1, 20) as $index) {
            DB::table('vendors')->insert([
                'vendor_company_name' => $faker->company,
                'vendor_contact' => $faker->phoneNumber,
                'vendor_person_name' => $faker->name,
                'vendor_address' => $faker->address,
                'vendor_email' => $faker->email
            ]);
        }
        foreach (range(1, 20) as $index) {
            DB::table('customers')->insert([
                'customer_name' => $faker->name,
                'customer_company_name' => $faker->company,
                'customer_contact' => $faker->numerify('03#########'),
                'customer_type' => $faker->randomElement($array = array ('Personal','Corporate')),
                'customer_home_address' => $faker->address,
                'customer_office_address' => $faker->country,
                'customer_email' => $faker->email,
                'customer_date' => $faker->date
            ]);
        }
    }
}
