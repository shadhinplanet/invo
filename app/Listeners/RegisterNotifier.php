<?php

namespace App\Listeners;

use App\Mail\RegisterNotifierEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class RegisterNotifier implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $admins = User::where('role','admin')->get();

        foreach ($admins as $admin) {
            Mail::send(new RegisterNotifierEmail($admin,$event->user));
        }

    }
}
