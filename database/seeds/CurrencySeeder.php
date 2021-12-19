<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            ['id' => 1, 'title' => 'GBP', 'default_currency' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'USD', 'default_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'CAD', 'default_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => 'RUB', 'default_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'title' => 'RMB', 'default_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'title' => 'AUD', 'default_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'title' => 'EUR', 'default_currency' => 0, 'created_at' => now(), 'updated_at' => now()]
        ];

        foreach($currencies as $currency) {
            DB::table('currency')->insert($currency);
        }
    }
}
