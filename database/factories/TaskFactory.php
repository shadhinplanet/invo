<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = [300,500,800,1500];
        $status = ['pending','complete'];
        $priority = ['low','medium','high'];
        $name =$this->faker->sentence();
        return [
            'name'  => $name,
            'slug'  => Str::slug($name),
            'description' => $this->faker->sentences(rand(2,5),true),
            'price' => $price[rand(0,3)],
            'status' => $status[rand(0,1)],
            'priority' => $priority[rand(0,2)],
            'client_id' => Client::all()->random()->id,
            'user_id'   => User::all()->random()->id,
            'end_date'   => Carbon::now()->addDays(rand(1,7))->format('Y-m-d H:i:s'),
        ];
    }
}
