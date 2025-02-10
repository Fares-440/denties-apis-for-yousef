<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['service_name' => 'تنظيف الأسنان', 'icon' => 'cleaning.png'],
            ['service_name' => 'حشو الأسنان', 'icon' => 'filling.png'],
            ['service_name' => 'تبييض الأسنان', 'icon' => 'whitening.png'],
            ['service_name' => 'تقويم الأسنان', 'icon' => 'orthodontics.png'],
            ['service_name' => 'زراعة الأسنان', 'icon' => 'implant.png'],
            ['service_name' => 'تركيب التيجان والجسور', 'icon' => 'crowns_bridges.png'],
            ['service_name' => 'علاج قناة الجذر', 'icon' => 'root_canal.png'],
            ['service_name' => 'خلع الأسنان', 'icon' => 'extraction.png'],
            ['service_name' => 'علاج اللثة', 'icon' => 'gum_treatment.png'],
            ['service_name' => 'فحص الأسنان', 'icon' => 'checkup.png'],
            ['service_name' => 'تلميع الأسنان', 'icon' => 'polishing.png'],
            ['service_name' => 'ترميم الأسنان', 'icon' => 'restoration.png'],
            ['service_name' => 'معالجة حساسية الأسنان', 'icon' => 'sensitivity.png'],
            ['service_name' => 'علاج البقع السنية', 'icon' => 'stain_removal.png'],
            ['service_name' => 'تنظيف عميق للأسنان', 'icon' => 'deep_cleaning.png'],
            ['service_name' => 'علاج التهاب اللثة', 'icon' => 'gum_inflammation.png'],
            ['service_name' => 'علاج نزيف اللثة', 'icon' => 'gum_bleeding.png'],
            ['service_name' => 'ترميم الابتسامة', 'icon' => 'smile_makeover.png'],
            ['service_name' => 'تقشير الأسنان', 'icon' => 'scaling.png'],
            ['service_name' => 'تثبيت الأسنان', 'icon' => 'bracing.png'],
            ['service_name' => 'علاج انحسار اللثة', 'icon' => 'gum_recession.png'],
        ];

        DB::table('services')->insert($services);
    }
}
