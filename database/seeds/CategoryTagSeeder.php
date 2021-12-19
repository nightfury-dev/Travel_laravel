<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorytags = [
            ['id' => 1, 'title' => 'Single Room', 'value' => 'single', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'Double Room', 'value' => 'double', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'Triple Room', 'value' => 'triple', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => 'Twin Room', 'value' => 'twin', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'title' => 'Family Room', 'value' => 'family', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 6, 'title' => 'Economy', 'value' => 'economy', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'title' => 'Normal', 'value' => 'normal', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 8, 'title' => '1 Hour', 'value' => 'one', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'title' => '2 Hour', 'value' => 'two', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 10, 'title' => 'AAA', 'value' => 'aaa', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'title' => 'BBB', 'value' => 'bbb', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 12, 'title' => 'CCC', 'value' => 'ccc', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'title' => 'DDD', 'value' => 'ddd', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()]
        ];

        foreach($categorytags as $tag) {
            DB::table('category_tag')->insert($tag);
        }
    }
}
