<?php

namespace App\Http\Livewire\BankCard;

use App\Services\BankCardService;
use App\Services\Livewire\HasAlert;
use App\Services\Livewire\HasModal;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Store extends Component
{
    use HasAlert, HasModal;

    # Form states
    public string $number;
    public string $title;

    public array $rules = [
        'title' => ['required', 'string', 'min:1'],
        'number' => ['required', 'string', 'min:1'],
    ];

    /**
     * Store new bank card in db
     *
     * @return void
     */
    public function store(): void
    {
        $this->validate();

        # Store new bank card in db and return error on failure
        $service = new BankCardService();
        $id = $service->create($this->title, $this->number, Auth::id());
        if (empty($id)) {
            $this->showAlert('Could not create bank card please try again', $this->icons['error']);
            return;
        }

        $this->showAlert('Bank card created successfully', $this->icons['success']);
        $this->redirect(route('card'));
    }

    public function render(): View
    {
        return view('livewire.bank-card.store');
    }
}
