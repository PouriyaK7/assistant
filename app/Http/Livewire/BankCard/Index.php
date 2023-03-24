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

    public function delete(BankCard $card): void
    {
        # Delete bank card and return error if
        $service = new BankCardService($card);
        if (!$service->delete()) {
            $this->showAlert('You cannot delete bank card that has transactions', $this->icons['error']);
        }

        $this->showAlert('Bank card deleted successfully', $this->icons['success']);
    }

    public function render(): View
    {
        return view('livewire.bank-card.index');
    }
}
