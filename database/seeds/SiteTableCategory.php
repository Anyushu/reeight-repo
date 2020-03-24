<?php

use Illuminate\Database\Seeder;

class SiteTableCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_categories')->insert([
            ['cat' => 'ブログサイト'],
            ['cat' => 'ポータルサイト'],
            ['cat' => 'プロモーションサイト'],
            ['cat' => 'ブランディングサイト'],
            ['cat' => 'ポートフォリオサイト'],
            ['cat' => 'ECサイト'],
            ['cat' => 'コーポレートサイト'],
            ['cat' => '店舗サイト'],
            ['cat' => 'リクルートサイト'],
            ['cat' => 'ランディングページ'],
        ]);
    }
}
