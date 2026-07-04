<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        Role::create(['name' => 'manager', 'display_name' => 'Manager']);
        Role::create(['name' => 'parent', 'display_name' => 'Parent']);
        Role::create(['name' => 'driver', 'display_name' => 'Driver']);
        Role::create(['name' => 'attendant', 'display_name' => 'Attendant']);
    }
}
