<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = config('my_services.services');

        foreach($data as $service){
            $newService = new Service();
            $newService->name = $service['name'];
            $newService->icon = $service['icon'];
            $newService->save();
        }
    }
}
