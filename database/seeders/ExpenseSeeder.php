<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 100; $i++) {
            DB::table('expenses')->insert([
                'date' => new \DateTime(),
                'responsible_person' => 'admin',
                'category_id' => rand(0, 10),
                'item_id' => rand(0, 10),
                'expense_amount' => rand(0, 99999),
                'comments' => Str::random(100),
                'receipt_photo' => Str::random(100),
            ]);
        }
    }
}
