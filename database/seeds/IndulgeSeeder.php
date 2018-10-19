<?php

use Illuminate\Database\Seeder;

class IndulgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \Mrlaozhou\Indulge\Providers\Option::query()->insert([
            [
                "name" => "出国年份",
                "keywords" => "ex_year",
                "pid" => 0,
                "weight" => 1,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:18",
                "updated_at" => "2018-10-19 06:07:23",
            ],
            [
                "name" => "2018",
                "keywords" => "",
                "pid" => 1,
                "weight" => 2,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
            [
                "name" => "2019",
                "keywords" => "",
                "pid" => 1,
                "weight" => 3,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
            [
                "name" => "2020",
                "keywords" => "",
                "pid" => 1,
                "weight" => 4,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
            [
                "name" => "日语等级",
                "keywords" => "level_jp",
                "pid" => 0,
                "weight" => 5,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
            [
                "name" => "N1",
                "keywords" => "",
                "pid" => 5,
                "weight" => 6,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
            [
                "name" => "N2",
                "keywords" => "",
                "pid" => 5,
                "weight" => 7,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
            [
                "name" => "N3",
                "keywords" => "",
                "pid" => 5,
                "weight" => 8,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
            [
                "name" => "N4",
                "keywords" => "",
                "pid" => 5,
                "weight" => 9,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:07:58",
                "updated_at" => "2018-10-19 06:08:01",
            ],
        ]);
        
        \Mrlaozhou\Indulge\Providers\Field::query()->insert([
            [
                "table" => "leads",
                "name" => "ex_year",
                "label" => "预计出国年份",
                "type" => 0,
                "form_type" => "select",
                "option_id" => 1,
                "default" => "",
                "description" => "学员预计出国时间",
                "require" => 'required|integer',
                "ext_is_index" => 1,
                "ext_is_write" => 1,
                "weight" => 1,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:47:33",
                "updated_at" => "2018-10-19 06:47:38",
            ],
            [
                "table" => "leads",
                "name" => "ex_univ",
                "label" => "期望院校",
                "type" => 0,
                "form_type" => "input",
                "option_id" => 0,
                "default" => "",
                "description" => "期望院校",
                "require" => 'required|max:30',
                "ext_is_index" => 1,
                "ext_is_write" => 1,
                "weight" => 2,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:47:33",
                "updated_at" => "2018-10-19 06:47:38",
            ],
            [
                "table" => "leads",
                "name" => "level_jp",
                "label" => "日语等级",
                "type" => 0,
                "form_type" => "select",
                "option_id" => 5,
                "default" => "",
                "description" => "日语等级",
                "require" => 'integer',
                "ext_is_index" => 1,
                "ext_is_write" => 1,
                "weight" => 3,
                "deleted_at" => null,
                "created_at" => "2018-10-19 06:47:33",
                "updated_at" => "2018-10-19 06:47:38",
            ],
        ]);
    }
}
