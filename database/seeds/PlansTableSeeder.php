<?php

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            ['name' => 'レポートプラン（年間契約）', 'price' => '48000', 'contract_period' => '12'],
            ['name' => 'SEOプラン（年間契約）', 'price' => '84000', 'contract_period' => '12'],
            ['name' => 'レポートプラン（半年契約）', 'price' => '30000', 'contract_period' => '6'],
            ['name' => 'SEOプラン（半年契約）', 'price' => '48000', 'contract_period' => '6'],
            ['name' => 'レポートプラン（月間契約）', 'price' => '6000', 'contract_period' => '1'],
            ['name' => 'SEO（月間契約）', 'price' => '9000', 'contract_period' => '1'],
            ['name' => 'レポートプラン（トライアル）', 'price' => '0', 'contract_period' => '1'],
            ['name' => 'SEOプラン（トライアル）', 'price' => '0', 'contract_period' => '1'],
        ]);
    }
}
