<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['id' => 1, 'title' => 'Hotel', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'B&B', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => '3 Star Hotel', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => '4 Star Hotel', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'title' => '5 Star Hotel', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 6, 'title' => 'Flights', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'title' => 'Bus', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'title' => 'Car', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'title' => 'Walking Tour', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'title' => 'Mini Bus', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'title' => 'Bike Tour', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 12, 'title' => 'Movies Deals', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'title' => 'Camping', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'title' => 'Diving Experiences', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'title' => 'Shopping', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'title' => 'Fast Food', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'title' => 'General Dining', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'title' => 'Cafes & Desert', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'title' => 'Spa & Wellness', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'title' => 'Cooking Class', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'title' => 'City Transfer', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            
            ['id' => 22, 'title' => 'Driver', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'title' => 'Entertainer', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'title' => 'Fixer', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'title' => 'Guide', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'title' => 'Key Account', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'title' => 'Speaker', 'parent_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            
            ['id' => 28, 'title' => 'Free Time', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'title' => 'Day Description', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'title' => 'Journey', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'title' => 'Time Taken', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'title' => 'Start / End', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'title' => 'Distance', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()]
        ];

        foreach($categories as $category) {
            DB::table('category')->insert($category);
        }
    }
}
