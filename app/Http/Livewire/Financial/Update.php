<?php

namespace App\Http\Livewire\Financial;

use App\Events\UpdateTransactionEvent;
use App\Models\Transaction;
use App\Services\Livewire\HasAlert;
use App\Services\Livewire\HasModal;
use App\Services\TransactionService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Update extends Component
{
    use HasModal, HasAlert;

    public string $title;
    public string $amount;

    public Transaction $transaction;

    # Updating transaction validation rules
    protected array $rules = [
        'title' => ['string', 'required', 'min:1'],
        'amount' => ['required', 'numeric'],
    ];

    /**
     * Set component states on component mount
     *
     * @return void
     */
    public function mount(): void
    {
        # Set title and amount values
        $this->title = $this->transaction->title;
        $this->amount = $this->transaction->amount;
    }

    /**
     * Update an existing transaction in db
     *
     * @return void
     */
    public function update(): void
    {
        $this->validate();
        $service = new TransactionService($this->transaction);

        # Update transaction and reload page
        $amount = $service->update($this->title, $this->amount);
        event(new UpdateTransactionEvent($amount, auth()->id()));
        $this->showAlert('Transaction updated successfully', $this->icons['success']);
        $this->redirect(route('financial'));
    }

    public function render(): View
    {
        return view('livewire.financial.update');
    }
}
