<?php
namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Seeder;

class ChatsTableSeeder extends Seeder
{
    public function run()
    {
        Chat::factory()->times(3)->create();
    }
}
