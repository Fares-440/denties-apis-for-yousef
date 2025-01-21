<?php
namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition()
    {
        $users = User::all()->pluck('id')->toArray();
        do {
            $senderId = $this->faker->randomElement($users);
            $receiverId = $this->faker->randomElement($users);
        } while ($senderId == $receiverId);

        return [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $this->faker->paragraph(),
            'sent_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
