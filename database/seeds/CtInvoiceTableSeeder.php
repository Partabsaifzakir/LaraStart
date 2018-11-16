<?php

use Illuminate\Database\Seeder;

use App\CtInvoice;
use App\CtInvoiceItems;
use Faker\Factory as Faker;

class CtInvoiceTableSeeder extends Seeder
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
            $ctInvoiceItems = collect();

            foreach (range(1, mt_rand(2, 10)) as $j) {
                
                $ct_fares = $faker->numberBetween(1000, 10000);

                $ct_tax_SB = $faker->numberBetween(100, 900);
                $ct_tax_SRP = $faker->numberBetween(100, 900);
                $ct_tax_YQ = $faker->numberBetween(100, 900);
                $ct_tax_RG = $faker->numberBetween(100, 900);
                $ct_tax_PK = $faker->numberBetween(100, 900);
                $ct_tax_YR = $faker->numberBetween(100, 900);
                $ct_tax_SF = $faker->numberBetween(100, 900);
                $ct_tax_PTT = $faker->numberBetween(100, 900);
                $ct_tax_OAS = $faker->numberBetween(100, 900);
                $ct_tax_PSF = $faker->numberBetween(100, 900);
                $ct_tax_PB = $faker->numberBetween(100, 900);
                $ct_tax_OAD = $faker->numberBetween(100, 900);
                $ct_total_tax_breakup = (
                                        $ct_tax_SB + 
                                        $ct_tax_SRP + 
                                        $ct_tax_YQ + 
                                        $ct_tax_RG + 
                                        $ct_tax_PK + 
                                        $ct_tax_YR + 
                                        $ct_tax_SF + 
                                        $ct_tax_PTT + 
                                        $ct_tax_OAS + 
                                        $ct_tax_PSF + 
                                        $ct_tax_PB + 
                                        $ct_tax_OAD);

                $ctInvoiceItems->push(new CtInvoiceItems ([
                    'ct_passenger_name' => $faker->name,
                    'ct_ticket_no' => $faker->numerify('###-####-###-###/###'),
                    'ct_flight_no' => $faker->numerify('TK ###'),
                    'ct_departure_date' => $faker->date,
                    'ct_sector' => $faker->lexify('???-???-???-???-???'),
                    'ct_tax_SB' => $ct_tax_SB,
                    'ct_tax_SRP' => $ct_tax_SRP,
                    'ct_tax_YQ' => $ct_tax_YQ,
                    'ct_tax_RG' => $ct_tax_RG,
                    'ct_tax_PK' => $ct_tax_PK,
                    'ct_tax_YR' => $ct_tax_YR,
                    'ct_tax_SF' => $ct_tax_SF,
                    'ct_tax_PTT' => $ct_tax_PTT,
                    'ct_tax_OAS' => $ct_tax_OAS,
                    'ct_tax_PSF' => $ct_tax_PSF,
                    'ct_tax_PB' => $ct_tax_PB,
                    'ct_tax_OAD' => $ct_tax_OAD,
                    'ct_fares' => $ct_fares,
                    'ct_total_tax_breakup' => $ct_total_tax_breakup,
                    'ct_sub_total' => ((int)$ct_total_tax_breakup + (int)$ct_fares)
                ]));
            } 

            $ct_invoice_total_tax_SB = $ctInvoiceItems->sum('ct_tax_SB');
            $ct_invoice_total_tax_SRP = $ctInvoiceItems->sum('ct_tax_SRP');
            $ct_invoice_total_tax_YQ = $ctInvoiceItems->sum('ct_tax_YQ');
            $ct_invoice_total_tax_RG = $ctInvoiceItems->sum('ct_tax_RG');
            $ct_invoice_total_tax_PK = $ctInvoiceItems->sum('ct_tax_PK');
            $ct_invoice_total_tax_YR = $ctInvoiceItems->sum('ct_tax_YR');
            $ct_invoice_total_tax_SF = $ctInvoiceItems->sum('ct_tax_SF');
            $ct_invoice_total_tax_PTT = $ctInvoiceItems->sum('ct_tax_PTT');
            $ct_invoice_total_tax_OAS = $ctInvoiceItems->sum('ct_tax_OAS');
            $ct_invoice_total_tax_PSF = $ctInvoiceItems->sum('ct_tax_PSF');
            $ct_invoice_total_tax_PB = $ctInvoiceItems->sum('ct_tax_PB');
            $ct_invoice_total_tax_OAD = $ctInvoiceItems->sum('ct_tax_OAD');

            $ct_invoice_fares_total = $ctInvoiceItems->sum('ct_fares');
            $ct_invoice_taxes_grand_total = $ctInvoiceItems->sum('ct_total_tax_breakup');
            $ct_invoice_grand_total = $ct_invoice_fares_total + $ct_invoice_taxes_grand_total;

            $ctInvoice = CtInvoice::create([
                'customer_id' => $faker->numberBetween(1, 20),
                'ct_invoice_no' => $faker->numerify('CTI-###-######'),
                'ct_invoice_date' => $faker->date,
                'ct_invoice_fares_total' => $ct_invoice_fares_total,

                'ct_invoice_total_tax_SB' => $ct_invoice_total_tax_SB,
                'ct_invoice_total_tax_SRP' => $ct_invoice_total_tax_SRP,
                'ct_invoice_total_tax_YQ' => $ct_invoice_total_tax_YQ,
                'ct_invoice_total_tax_RG' => $ct_invoice_total_tax_RG,
                'ct_invoice_total_tax_PK' => $ct_invoice_total_tax_PK,
                'ct_invoice_total_tax_YR' => $ct_invoice_total_tax_YR,
                'ct_invoice_total_tax_SF' => $ct_invoice_total_tax_SF,
                'ct_invoice_total_tax_PTT' => $ct_invoice_total_tax_PTT,
                'ct_invoice_total_tax_OAS' => $ct_invoice_total_tax_OAS,
                'ct_invoice_total_tax_PSF' => $ct_invoice_total_tax_PSF,
                'ct_invoice_total_tax_PB' => $ct_invoice_total_tax_PB,
                'ct_invoice_total_tax_OAD' => $ct_invoice_total_tax_OAD,

                'ct_invoice_taxes_grand_total' => $ct_invoice_taxes_grand_total,
                'ct_invoice_grand_total' => $ct_invoice_grand_total
            ]);

            $ctInvoice->ctInvoiceItems()->saveMany($ctInvoiceItems);
        }
    }
}
