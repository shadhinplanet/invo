<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterNotifierEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $admin,$user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin,$user)
    {
        $this->admin = $admin;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.register_notifier_admin',['user'=>$this->user])
            ->from('no_reply@pixcafe.xyz','Invo')
            ->to($this->admin->email,$this->admin->name)
            ->subject('New User Registered [Invo]');
    }
}
