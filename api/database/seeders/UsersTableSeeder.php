<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonFile = storage_path('data.json'); // Replace with the path to your JSON file

        $data = json_decode(file_get_contents($jsonFile), true);

        foreach ($data as $item) {
            // Insert user data
            $userId = DB::table('users')->insertGetId([
                'user_name' => $item['user_name'],
                'gender' => $item['gender'],
                'date_of_birth' => $item['date_of_birth'],
                'known_as' => $item['known_as'],
                'created' => $item['created'],
                'last_active' => $item['last_active'],
                'introduction' => $item['introduction'],
                'looking_for' => $item['looking_for'],
                'interests' => $item['interests'],
                'city' => $item['city'],
                'country' => $item['country'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert photos
            $photos = json_decode($item['photos'], true);

            foreach ($photos as $photo) {
                DB::table('photos')->insert([
                    'user_id' => $userId,
                    'url' => $photo['url'],
                    'is_main' => $photo['is_main'],
                    // 'created_at' => now(),
                    // 'updated_at' => now(),
                ]);
            }
        }
    }
}
