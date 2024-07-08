<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            "allocation",
            "division",
            "fund source",
            "line",
            "log",
            "purchase_detail",
            "transaction",
            "uacs",
            "uacs_transaction",
            "user",
            "role",
            "permission"
        ];
        
        $actions = [
            "create",
            "view",
            "update",
            "delete"
        ];

        foreach($tables as $table) {
            foreach($actions as $action) {
                Permission::create([
                    'name'          => $action . " " . $table,
                    'guard_name'    => 'sanctum',
                    'created_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at'    => \Carbon\Carbon::now()->toDateTimeString(),
                ]);
            }
        }
    }
}
