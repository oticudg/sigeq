<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ClearTablesSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(UserRootSeeder::class);
        $this->call(DeveloperSeeder::class);
    }
}
