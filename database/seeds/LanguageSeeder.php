<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['id' => 1, 'name' => 'ENGLISH', 'title' => 'gb', 'default_language' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'FRANCE', 'title' => 'fr', 'default_language' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'GERMAN', 'title' => 'de', 'default_language' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'PORTUGUESE', 'title' => 'pr', 'default_language' => 0, 'created_at' => now(), 'updated_at' => now()]
        ];

        foreach($languages as $language) {
            DB::table('language')->insert($language);
        }
    }
}
