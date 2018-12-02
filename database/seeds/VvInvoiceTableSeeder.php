<?php

use Illuminate\Database\Seeder;

use App\VvInvoice;
use App\VvInvoiceItems;
use Faker\Factory as Faker;
use App\Vendor;

class VvInvoiceTableSeeder extends Seeder
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
            $vvInvoiceItems = collect();

            foreach (range(1, mt_rand(2, 10)) as $j) {
                
                $vv_fares = $faker->numberBetween(1000, 10000);
                $vv_sub_total = $vv_fares;

                $vvInvoiceItems->push(new VvInvoiceItems ([
                    'vv_passenger_name' => $faker->name,
                    'vv_description' => $faker->country,
                    'vv_fares' => $vv_fares,
                    'vv_sub_total' => $vv_sub_total
                ]));
            }

            $vv_invoice_fares_total = $vvInvoiceItems->sum('vv_fares');
            $vv_invoice_grand_total = $vv_invoice_fares_total;

            $vvInvoice = VvInvoice::create([
                'vendor_id' => $faker->numberBetween(1, 20),
                'vv_invoice_no' => $faker->numerify('VVI-###-######'),
                'vv_invoice_date' => $faker->date,
                'vv_invoice_fares_total' => $vv_invoice_fares_total,
                'vv_invoice_grand_total' => $vv_invoice_grand_total,
            ]);

            $vvInvoice->vvInvoiceItems()->saveMany($vvInvoiceItems);
        }
    }
}

