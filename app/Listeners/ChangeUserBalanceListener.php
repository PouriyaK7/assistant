<?php

namespace App\Listeners;

use App\Models\User;

class ChangeUserBalanceListener
{
    /**
     * Change user balance with the amount given
     */
    public function handle(object $event): void
    {
        # Fetch user from db and exit if not exists
        $user = User::find($event->userID);
        if (empty($user)) {
            return;
        }
        # Increase/Decrease user balance with the amount given and save to db
        $user->balance += $event->amount;
        $user->save();
    }
}
