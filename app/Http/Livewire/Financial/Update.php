<?php

namespace App\Http\Livewire\Financial;

use App\Events\UpdateTransactionBankCardEvent;
use App\Events\UpdateTransactionEvent;
use App\Models\BankCard;
use App\Models\Transaction;
use App\Services\Livewire\HasAlert;
use App\Services\Livewire\HasModal;
use App\Services\TransactionService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Update extends Component
{
    use HasModal, HasAlert;

    # Form states
    public string $title;
    public string $amount;
    public ?string $bank_card_id;

    # View properties
    public Collection $bankCards;

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
        $this->bank_card_id = $this->transaction->bank_card_id;
        $this->bankCards = BankCard::orderBy('title')->get();
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
        $oldCard = $service->get()->bankCard;

        # Update transaction and reload page
        $amount = $service->update($this->title, $this->amount, $this->bank_card_id);

        $bankCard = BankCard::find($this->bank_card_id);
        $amount = $amount + $this->transaction->amount;
        event(new UpdateTransactionEvent($amount, Auth::user(), $oldCard, $bankCard));

        $this->showAlert('Transaction updated successfully', $this->icons['success']);
        $this->redirect(route('financial'));
    }

    public function render(): View
    {
        return view('livewire.financial.update');
    }
}
