<?php

use Illuminate\Database\Seeder;
use App\Entities\User;

class SampleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local') !== true) {
            return;
        }
        DB::table('users')->delete();
        $data = [
            [
                'first_name' => 'test',
                'last_name' => 'test',
                'email' => 'test',
                'phone_number' => '123412',
                'password' => bcrypt('test'),
                'role_id' => 2
            ], [
                'first_name' => 'salesman',
                'last_name' => 'salesman',
                'email' => 'salesman@gmail.com',
                'phone_number' => '123412',
                'password' => bcrypt('password'),
                'role_id' => 2
            ], [
                'first_name' => 'allen',
                'last_name' => 'desamparado',
                'email' => 'ajt.tester313@gmail.com',
                'phone_number' => '123412',
                'password' => bcrypt('password'),
                'role_id' => 2
            ], [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@gmail.com',
                'phone_number' => '123412',
                'password' => bcrypt('password'),
                'role_id' => 1
            ], [
                'first_name' => 'john',
                'last_name' => 'doe',
                'email' => 'john.doe@gmail.com',
                'phone_number' => '123412',
                'password' => bcrypt('password'),
                'role_id' => 2
            ], [
                'first_name' => 'Kobe',
                'last_name' => 'Doe',
                'phone_number' => '123412',
                'email' => 'kobe.doe@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 2
            ], [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'phone_number' => '123412',
                'email' => 'jane.doe@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 2
            ], [
                'first_name' => '竜矢',
                'last_name' => '泰道',
                'phone_number' => '090-1435-1447',
                'email' => 'yamaguchi@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 2
            ], [
                'first_name' => 'tuyet',
                'last_name' => 'morgan',
                'phone_number' => '090-1435-1447',
                'email' => 'tuyetmorgans@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 2
            ], [
                'first_name' => 'rhea',
                'last_name' => 'bulahan',
                'phone_number' => '090-1435-1447',
                'email' => 'rcbulahan@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 2
            ],
        ];

        foreach ($data as $source) {
            User::updateOrCreate(['email' => $source['email']], $source);
        }
    }
}
