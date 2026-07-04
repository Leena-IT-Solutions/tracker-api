<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'sandeep198558@yahoo.com'],
            [
                'name' => 'Sandeep Rathod',
                'mobile' => '9664588677',
                'password' => Hash::make('password'),
            ]
        );

        $allRoles = Role::all();
        $user->roles()->sync($allRoles->pluck('id'));
    }
}
