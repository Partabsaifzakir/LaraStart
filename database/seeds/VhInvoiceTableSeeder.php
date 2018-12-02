<?php

use Illuminate\Database\Seeder;

use App\VhInvoice;
use App\VhInvoiceItems;
use Faker\Factory as Faker;
use App\Vendor;

class VhInvoiceTableSeeder extends Seeder
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
            $vhInvoiceItems = collect();

            foreach (range(1, mt_rand(2, 10)) as $j) {
                
                $vh_fares = $faker->numberBetween(1000, 10000);
                $vh_sub_total = $vh_fares;

                $vhInvoiceItems->push(new VhInvoiceItems ([
                    'vh_description' => $faker->sentences($nb = 3, $asText = true),
                    'vh_fares' => $vh_fares,
                    'vh_sub_total' => $vh_sub_total
                ]));
            }

            $vh_invoice_fares_total = $vhInvoiceItems->sum('vh_fares');
            $vh_invoice_grand_total = $vh_invoice_fares_total;

            $vhInvoice = VhInvoice::create([
                'vendor_id' => $faker->numberBetween(1, 20),
                'vh_invoice_no' => $faker->numerify('VHI-###-######'),
                'vh_invoice_date' => $faker->date,
                'vh_invoice_fares_total' => $vh_invoice_fares_total,
                'vh_invoice_grand_total' => $vh_invoice_grand_total,
            ]);

            $vhInvoice->vhInvoiceItems()->saveMany($vhInvoiceItems);
        }
    }
}
