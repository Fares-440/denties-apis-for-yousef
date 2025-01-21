<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('universities')->delete();
        $uni = array(
            //////////////جامعات صنعاء
            array('name' => "جامعة العلوم والتكنولوجيا", 'city_id' => 1),
            array('name' => "جامعة صنعاء", 'city_id' => 1),
            array('name' => "جامعة الرازي", 'city_id' => 1),
            array('name' => "جامعة ابن النفيس", 'city_id' => 1),
            array('name' => "جامعة سبأ", 'city_id' => 1),
            array('name' => "جامعة الناصر ", 'city_id' => 1),
            array('name' => "جامعة إقرأ", 'city_id' => 1),
            array('name' => "جامعة الحكمة", 'city_id' => 1),
            array('name' => " جامعة الملكة أروى ", 'city_id' => 1),
            array('name' => "الجامعة العربية للعلوم والتقنية", 'city_id' => 1),
            array('name' => "جامعة العلوم الحديثة", 'city_id' => 1),
            array('name' => "جامعة دار السلام", 'city_id' => 1),
            array('name' => "جامعة الرشيد الذكية ", 'city_id' => 1),
            array('name' => "جامعة الجيل الجديد", 'city_id' => 1),
            array('name' => "الجامعة البريطانية", 'city_id' => 1),
            array('name' => "جامعة المستقبل", 'city_id' => 1),
            array('name' => "الجامعة الإمارتية الدولية", 'city_id' => 1),
            array('name' => "الجامعة اليمنية الإردنية", 'city_id' => 1),
            array('name' => "الجامعة اليمنية", 'city_id' => 1),
            array('name' => "جامعة المستقبل", 'city_id' => 1),
            array('name' => "الجامعة اللبنانية", 'city_id' => 1),
            array('name' => " جامعة الاندلس للعلوم والتقنية", 'city_id' => 1),
            ///////////////////////////////////جامعات تعز
            array('name' => " جامعة تعز", 'city_id' => 2),
            array('name' => " جامعة العلوم والتكنولوجيا", 'city_id' => 2),
            array('name' => "جامعة الرواد", 'city_id' => 2),
            array('name' => " جامعةالعطاء للعلوم والتكنولوجيا", 'city_id' => 2),
            array('name' => "كلية22مايو للعلوم الطبية والتطبيقية", 'city_id' => 2),
            array('name' => "الجامعة الوطنية", 'city_id' => 2),
            array('name' => " جامعة السعيد", 'city_id' => 2),

                ///////////////////////////جامعات عدن
            array('name' => " جامعة عدن", 'city_id' => 3),
            array('name' => " جامعة الحضارة", 'city_id' => 3),
            array('name' => "  جامعة ابن خلدون", 'city_id' => 3),
            array('name' => " جامعة العادل", 'city_id' => 3),
                ///////////////////////جامعات ذمار
            array('name' => " جامعة ذمار", 'city_id' => 4),
            array('name' => " جامعة الحكمة", 'city_id' => 4),
            array('name' => "  جامعة السعيدة", 'city_id' => 4),
            array('name' => " جامعة جينيس للعلوم والتكنولوجيا", 'city_id' => 4),
                /////////////////جامعات إب
            array('name' => " جامعة إب", 'city_id' => 5),
            array('name' => "جامعة القلم للعلوم الإنسانية والتطبيقية", 'city_id' => 5),
            array('name' => "جامعة العلوم والتكنولوجيا", 'city_id' => 5),
            array('name' => " جامعة الجزيرة", 'city_id' => 5),
            array('name' => "الجامعة الماليزية الدولية", 'city_id' => 5),
                /////////////////جامعات عمران
            array('name' => "جامعة عمران ", 'city_id' => 6),

        );

        DB::table('universities')->insert($uni);
    }
}
