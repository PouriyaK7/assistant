<?php

namespace App\Http\Livewire\Financial;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Store extends Component
{
    public int $amount;

    public function store()
    {
        //
    }

    public function render(): View
    {
        return view('livewire.financial.store');
    }
}
