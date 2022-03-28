<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice_id'    => 'INVO_' . rand(23535,235353253),
            'client_id'     => Client::all()->random()->id,
            'user_id'     => User::all()->random()->id,
            'amount'     => rand(100,5000),
            'download_url'     => 'https://picsum.photos/300?ranndom='.rand(235,35235),
        ];
    }
}
