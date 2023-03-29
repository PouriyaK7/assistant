<?php

namespace App\Http\Livewire\BankCard;

use App\Models\BankCard;
use App\Services\BankCardService;
use App\Services\Livewire\HasAlert;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    use HasAlert;

    /**
     * Delete card from db
     *
     * @param string $cardID
     * @return void
     */
    public function delete(string $cardID): void
    {
        # Delete bank card and return error if
        $deleted = BankCardService::delete($cardID);
        if (!$deleted) {
            $this->showAlert('You cannot delete bank card that has transactions', $this->icons['error']);
            return;
        }

        $this->showAlert('Bank card deleted successfully', $this->icons['success']);
    }

    public function render(): View
    {
        $cards = BankCard::orderBy('title')->paginate(20);
        return view('livewire.bank-card.index', compact('cards'));
    }
}
