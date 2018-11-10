<?php

use Illuminate\Database\Seeder;

use App\TicketInvoice;
use App\TicketInvoiceItems;
use Faker\Factory as Faker;
use App\Customer;

class TicketInvoiceTableSeeder extends Seeder
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
            $ticketInvoiceItems = collect();

            foreach (range(1, mt_rand(2, 10)) as $j) {
                
                $fares = $faker->numberBetween(1000, 10000);

                $tax_SB = $faker->numberBetween(100, 900);
                $tax_SRP = $faker->numberBetween(100, 900);
                $tax_YQ = $faker->numberBetween(100, 900);
                $tax_RG = $faker->numberBetween(100, 900);
                $tax_PK = $faker->numberBetween(100, 900);
                $tax_YR = $faker->numberBetween(100, 900);
                $tax_SF = $faker->numberBetween(100, 900);
                $tax_PTT = $faker->numberBetween(100, 900);
                $tax_OAS = $faker->numberBetween(100, 900);
                $tax_PSF = $faker->numberBetween(100, 900);
                $tax_PB = $faker->numberBetween(100, 900);
                $tax_OAD = $faker->numberBetween(100, 900);
                $total_tax_breakup = ($tax_SB + $tax_SRP + $tax_YQ + $tax_RG + $tax_PK + $tax_YR + $tax_SF + $tax_PTT + $tax_OAS + $tax_PSF + $tax_PB + $tax_OAD);
                
                $ticketInvoiceItems->push(new TicketInvoiceItems ([
                    'passenger_name' => $faker->name,
                    'ticket_no' => $faker->numerify('###-####-###-###/###'),
                    'flight_no' => $faker->numerify('TK ###'),
                    'departure_date' => $faker->date,
                    'sector' => $faker->lexify('???-???-???-???-???'),
                    'tax_SB' => $tax_SB,
                    'tax_SRP' => $tax_SRP,
                    'tax_YQ' => $tax_YQ,
                    'tax_RG' => $tax_RG,
                    'tax_PK' => $tax_PK,
                    'tax_YR' => $tax_YR,
                    'tax_SF' => $tax_SF,
                    'tax_PTT' => $tax_PTT,
                    'tax_OAS' => $tax_OAS,
                    'tax_PSF' => $tax_PSF,
                    'tax_PB' => $tax_PB,
                    'tax_OAD' => $tax_OAD,
                    'fares' => $fares,
                    'total_tax_breakup' => $total_tax_breakup,
                    'sub_total' => ((int)$total_tax_breakup + (int)$fares)
                ]));
            }

            $ticket_invoice_fares_total = $ticketInvoiceItems->sum('fares');
            $ticket_invoice_taxes_grand_total = $ticketInvoiceItems->sum('total_tax_breakup');
            $ticket_invoice_grand_total = $ticket_invoice_fares_total + $ticket_invoice_taxes_grand_total;

            $ticketInvoice = TicketInvoice::create([
                'vendor_id' => $faker->numberBetween(1, 20),
                'ticket_invoice_no' => $faker->numerify('VI-###-######'),
                'ticket_invoice_date' => $faker->date,
                'ticket_invoice_fares_total' => $ticket_invoice_fares_total,
                'ticket_invoice_taxes_grand_total' => $ticket_invoice_taxes_grand_total,
                'ticket_invoice_grand_total' => $ticket_invoice_grand_total
            ]);

            $ticketInvoice->ticketInvoiceItems()->saveMany($ticketInvoiceItems);
        }
        
    }
}
