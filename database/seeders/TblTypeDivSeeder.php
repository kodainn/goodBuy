<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TblTypeDivSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_type_div')->insert([
            [
                'type_name' => 'gender',
                'type_detail_div' => 1,
                'type_detail_name' => '男性'
            ],
            [
                'type_name' => 'gender',
                'type_detail_div' => 2,
                'type_detail_name' => '女性'
            ],
            [
                'type_name' => 'gender',
                'type_detail_div' => 3,
                'type_detail_name' => 'その他'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 1,
                'type_detail_name' => '食品'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 2,
                'type_detail_name' => '衣料品とファッション'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 3,
                'type_detail_name' => '家庭用品'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 4,
                'type_detail_name' => '電子機器'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 5,
                'type_detail_name' => '書籍とメディア'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 6,
                'type_detail_name' => 'スポーツ用品'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 7,
                'type_detail_name' => '車両'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 8,
                'type_detail_name' => '美容品'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 9,
                'type_detail_name' => '芸術品'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 10,
                'type_detail_name' => 'エレクトロニクスとアクセサリー'
            ],
            [
                'type_name' => 'genre',
                'type_detail_div' => 11,
                'type_detail_name' => 'その他'
            ]
        ]);
    }
}
