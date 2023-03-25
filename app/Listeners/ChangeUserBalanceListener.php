<?php

namespace App\Listeners;

use App\Events\UpdateTransactionEvent;

class ChangeUserBalanceListener
{
    /**
     * Change user balance with the amount given
     */
    public function handle(UpdateTransactionEvent $event): void
    {
        $old = $event->oldCard;
        $new = $event->newCard;
        $user = $event->user;

        if ($old !== false) {
            if (empty($old)) {
                $old = $user;
            }
            $old->update([
                'balance' => (float)($old->balance - $event->amount),
            ]);
        }

        if (empty($new)) {
            $new = $user;
        }
        $new->update([
            'balance' => (float)($new->balance + $event->amount),
        ]);
    }
}
