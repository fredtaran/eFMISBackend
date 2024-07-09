<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            DivisionSeeder::class,
            SectionSeeder::class,
            LineItemSeeder::class,
            FundSourceSeeder::class
        ]);

        $permissions = Permission::all()->pluck('id')->toArray();
        $role = Role::where('id', 1)->first();
        $role->syncPermissions($permissions);

        $user = User::create([
            'firstname'     => "Fred",
            'middlename'    => "Polinar",
            'lastname'      => "Taran",
            'username'      => "fred.taran",
            'password'      => Hash::make("@dmin123"),
            'division_id'   => 1
        ]);

        $roleToAssign = Role::where('name', 'superadmin')->pluck('id')->toArray();
        $user->syncRoles($roleToAssign);
    }
}
