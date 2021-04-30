<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ["name" => 'buyer','created_at' => now(),'updated_at' => now()],
            ["name" => 'photographer','created_at' => now(),'updated_at' => now()]
        ]);
    }
}
