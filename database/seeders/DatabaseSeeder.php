<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Thomas Muster',
            'email' => 'thomas.muster@gmx.ch',
            'password' => bcrypt('Muster1998/')

        ]);

        User::factory(10)->create();
    }
}
