<?php

namespace App\Http\Livewire\Financial;

use App\Events\UpdateTransactionEvent;
use App\Models\BankCard;
use App\Models\Transaction;
use App\Services\BankCardService;
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
        # Get origin card from transaction
        $originCard = $this->transaction->bankCard;
        # Get destination card from user input
        $destCard = null;
        if (!empty($this->bank_card_id)) {
            $destCard = BankCardService::get($this->bank_card_id);
        }

        # Transfer money between two wallets if transaction wallet is changed before updating the diff in transaction
        if ($this->bank_card_id != ($originCard?->id ?? null)) {
            BankCardService::transfer(
                $this->transaction->amount,
                $originCard ?? Auth::user(),
                $destCard ?? Auth::user(),
            );
        }

        # Update transaction and reload page
        $amount = TransactionService::update(
            $this->transaction->id,
            $this->title,
            $this->amount,
            $this->bank_card_id
        );

        event(new UpdateTransactionEvent($amount, Auth::user(), false, $destCard));

        $this->showAlert('Transaction updated successfully', $this->icons['success']);
        $this->redirect(route('financial'));
    }

    public function render(): View
    {
        return view('livewire.financial.update');
    }
}
