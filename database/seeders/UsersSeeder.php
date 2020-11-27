<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nb = (int) $this->command->ask('How many users do you want to create?', 12);
        User::factory($nb)->create();
    }
}
