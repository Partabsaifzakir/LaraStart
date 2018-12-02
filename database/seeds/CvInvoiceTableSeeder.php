<?php

use Illuminate\Database\Seeder;

use App\CvInvoice;
use App\CvInvoiceItems;
use Faker\Factory as Faker;
use App\Customer;

class CvInvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $i) {
            $cvInvoiceItems = collect();

            foreach (range(1, mt_rand(2, 10)) as $j) {
                
                $cv_fares = $faker->numberBetween(1000, 10000);
                $cv_sub_total = $cv_fares;

                $cvInvoiceItems->push(new CvInvoiceItems ([
                    'cv_passenger_name' => $faker->name,
                    'cv_description' => $faker->country,
                    'cv_fares' => $cv_fares,
                    'cv_sub_total' => $cv_sub_total
                ]));
            }

            $cv_invoice_fares_total = $cvInvoiceItems->sum('cv_fares');
            $cv_invoice_grand_total = $cv_invoice_fares_total;

            $cvInvoice = CvInvoice::create([
                'customer_id' => $faker->numberBetween(1, 20),
                'cv_invoice_no' => $faker->numerify('VCI-###-######'),
                'cv_invoice_date' => $faker->date,
                'cv_invoice_fares_total' => $cv_invoice_fares_total,
                'cv_invoice_grand_total' => $cv_invoice_grand_total,
            ]);

            $cvInvoice->cvInvoiceItems()->saveMany($cvInvoiceItems);
        }
    }
}
