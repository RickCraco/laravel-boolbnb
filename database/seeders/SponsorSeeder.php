<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sponsor;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsors = [
            [
                'name' => 'Bronze',
                'price' => 2.99,
                'duration' => 1
            ],
            [
                'name' => 'Silver',
                'price' => 5.99,
                'duration' => 3
            ],
            [
                'name' => 'Gold',
                'price' => 9.99,
                'duration' => 6
            ],   
        ];

        foreach ($sponsors as $sponsor) {
            Sponsor::create($sponsor);
        }
    }
}
