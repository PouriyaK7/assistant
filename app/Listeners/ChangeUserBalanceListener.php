<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Log;

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
            Log::error('User not found for changing it balance');
            return;
        }
        # Increase/Decrease user balance with the amount given and save to db
        $user->update([
            'balance' => (float)($user->balance + $event->amount)
        ]);
    }
}
