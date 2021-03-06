<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call([UsersTableSeeder::class]);
        // $this->call(ProvinceCitySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(ResellerSeeder::class);
        \App\Models\Client::factory()->count(100)->create();
        \App\Models\Transaction::factory()->count(50)->create();
    }
}
