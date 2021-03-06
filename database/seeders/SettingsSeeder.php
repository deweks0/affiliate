<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key' => 'percentage',
            'label' => 'Percentage',
            'value' => '10',
            'product_id' => '1',
            'company_id' => Company::where('name', 'affiliate')->get()->first()->id,

            'group' => 'admin',
            'updated_at' => now()
        ]);
        DB::table('settings')->insert([
            'key' => 'day of settelment',
            'label' => 'Day of Settelment',
            'value' => '20',
            'product_id' => '1',
            'company_id' => Company::where('name', 'affiliate')->get()->first()->id,
            'group' => 'admin',
            'updated_at' => now()
        ]);
    }
}
