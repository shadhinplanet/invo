<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientYearlyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $client;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $user = $this->client->user;

        return $this->markdown('email.client_year_email',['client'=>$this->client])
        ->from(env('MAIL_FROM_ADDRESS'), $user->name)
        ->to($this->client->email, $this->client->name)
        ->replyTo( $user->email, $user->name)
        ->subject('Yearly Aniversary Email');
    }
}
