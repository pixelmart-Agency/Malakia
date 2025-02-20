<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add static roles
        Role::create([
            'name' => 'مدير النظام',
            'guard_name' => 'web',
        ]); // 1
        Role::create([
            'name' => 'مشرف',
            'guard_name' => 'user',
        ]); // 2
        Role::create([
            'name' => 'مراقب',
            'guard_name' => 'user',
        ]); // 3
        Role::create([
            'name' => 'سائق',
            'guard_name' => 'user',
        ]); // 4
        Role::create([
            'name' => 'موظف',
            'guard_name' => 'user',
        ]); // 5
    }
}
