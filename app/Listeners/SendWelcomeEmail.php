<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Resources\Json\JsonResource;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct(public User $user)
    { }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event)
    {
        return $event;
        // Mail::send('',$event->user,route()->redirect('/home'));
    }
}
