<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = ['Mbank', 'Cash'];

        foreach ($paymentMethods as $value){

            DB::table('payment_methods')->insert([
                'name' => $value
            ]);
        }
    }
}
