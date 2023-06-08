<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\UserPointsHelper;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $jsonFile = storage_path('seeds\data.json'); // Replace with the path to your JSON file

        $data = json_decode(file_get_contents($jsonFile), true);

        Role::insert(
            [
                [
                    'name' => 'user',
                ],
                [
                    'name' => 'moderator'
                ],
                [
                    'name' => 'admin'
                ]
            ]
        );

        foreach ($data as $item) {
            // Insert user data
            $userId = DB::table('users')->insertGetId([
                'username' => strtolower($item['username']),
                'email' => strtolower($item['username']).'@email.com',
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
                'password' => Hash::make("Passw0rd"),
                'role_id' => 1
            ]);

            // Insert photos
            $photos = json_decode(json_encode($item['photos']), true);

            foreach ($photos as $photo) {
                DB::table('photos')->insert([
                    'user_id' => $userId,
                    'public_id' => "temp",
                    'url' => $photo['url'],
                    'is_main' => $photo['is_main'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('default_points')->insert([
            'what_for' => 'introduction',
            'points' => 10,
        ]);
        DB::table('default_points')->insert([
            'what_for' => 'looking_for',
            'points' => 10,
        ]);
        DB::table('default_points')->insert([
            'what_for' => 'interests',
            'points' => 10,
        ]);
        DB::table('default_points')->insert([
            'what_for' => 'first_photo',
            'points' => 10,
        ]);
        DB::table('default_points')->insert([
            'what_for' => 'next_photo',
            'points' => 5,
        ]);
        DB::table('default_points')->insert([
            'what_for' => 'likes',
            'points' => 2,
        ]);
        DB::table('default_points')->insert([
            'what_for' => 'bans',
            'points' => -20,
        ]);

        $users = User::all();

        foreach ($users as $user) {
            UserPointsHelper::CalculateAndUpdateUserPoints($user);
        }
    }

}
