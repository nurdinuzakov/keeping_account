<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 100; $i++) {
            DB::table('incomes')->insert([
                'date' => new \DateTime(),
                'from' => 'admin',
                'description' => Str::random(100),
                'amount' => rand(0, 99999),
            ]);
        }
    }
}
