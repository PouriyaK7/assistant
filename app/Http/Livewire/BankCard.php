<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class BankCard extends Component
{
    public function render(): View
    {
        return view('livewire.bank-card');
    }
}
