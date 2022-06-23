<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $userCount=  (int) $this->command->ask('how many users would you like ?', 20);
      
      User::factory($userCount)->create();
      // User::factory()->isAdmin()->create();
    }
}
