<?php

namespace Database\Seeders;

use App\Models\MyGold;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MyGoldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MyGold::factory()->count(3)->create();
    }
}
