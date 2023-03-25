<?php

namespace App\Http\Livewire\BankCard;

use App\Models\BankCard;
use App\Services\BankCardService;
use App\Services\Livewire\HasAlert;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    use HasAlert;

    /**
     * Delete card from db
     *
     * @param BankCard $card
     * @return void
     */
    public function delete(BankCard $card): void
    {
        # Delete bank card and return error if
        $service = new BankCardService($card);
        $deleted = $service->delete();
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
