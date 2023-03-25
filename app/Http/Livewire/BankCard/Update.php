<?php

namespace App\Http\Livewire\BankCard;

use App\Models\BankCard;
use App\Services\BankCardService;
use App\Services\Livewire\HasAlert;
use App\Services\Livewire\HasModal;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Update extends Component
{
    use HasAlert, HasModal;

    # Form states
    public string $title;
    public string $number;
    public BankCard $card;

    public array $rules = [
        'title' => ['required', 'string', 'min:1'],
        'number' => ['required', 'string', 'min:1'],
    ];

    /**
     * Set component states on component mount
     *
     * @return void
     */
    public function mount(): void
    {
        $this->title = $this->card->title;
        $this->number = $this->card->number;
    }

    /**
     * Update bank card in db
     *
     * @return void
     */
    public function update(): void
    {
        $this->validate();

        $service = new BankCardService($this->card);
        $updated = $service->update($this->title, $this->number);
        if (!$updated) {
            $this->showAlert('Could not edit card please try again', $this->icons['error']);
            return;
        }

        $this->showAlert('Bank card created successfully', $this->icons['success']);
        $this->redirect(route('card'));
    }

    public function render(): View
    {
        return view('livewire.bank-card.update');
    }
}
