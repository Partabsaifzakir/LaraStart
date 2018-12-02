<?php

use Illuminate\Database\Seeder;

use App\ChInvoice;
use App\ChInvoiceItems;
use Faker\Factory as Faker;
use App\Customer;

class ChInvoiceTableSeeder extends Seeder
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
            $chInvoiceItems = collect();

            foreach (range(1, mt_rand(2, 10)) as $j) {
                
                $ch_fares = $faker->numberBetween(1000, 10000);
                $ch_sub_total = $ch_fares;

                $chInvoiceItems->push(new ChInvoiceItems ([
                    'ch_description' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
                    'ch_fares' => $ch_fares,
                    'ch_sub_total' => $ch_sub_total
                ]));
            }

            $ch_invoice_fares_total = $chInvoiceItems->sum('ch_fares');
            $ch_invoice_grand_total = $ch_invoice_fares_total;

            $chInvoice = ChInvoice::create([
                'customer_id' => $faker->numberBetween(1, 20),
                'ch_invoice_no' => $faker->numerify('VHI-###-######'),
                'ch_invoice_date' => $faker->date,
                'ch_invoice_fares_total' => $ch_invoice_fares_total,
                'ch_invoice_grand_total' => $ch_invoice_grand_total,
            ]);

            $chInvoice->chInvoiceItems()->saveMany($chInvoiceItems);
    }
}

}