<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_type = [
            ['id' => 1, 'title' => 'Direct Customer', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'Operator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'Supplier', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'title' => 'Admin', 'created_at' => now(), 'updated_at' => now()]
        ];

        foreach($account_type as $type) {
            DB::table('account_type')->insert($type);
        }
    }
}
