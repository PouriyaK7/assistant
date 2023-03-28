<?php

namespace App\Http\Livewire\Financial;

use App\Events\UpdateTransactionEvent;
use App\Models\BankCard;
use App\Services\Livewire\ConfirmSubmit;
use App\Services\Livewire\HasAlert;
use App\Services\Livewire\HasModal;
use App\Services\TransactionService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Store extends Component
{
    use HasModal, ConfirmSubmit, HasAlert;

    # Form states
    public string $title;
    public string $amount;
    public ?string $bank_card_id = null;

    # View properties
    public Collection $bankCards;

    # Transaction service instance
    protected TransactionService $service;

    # Listeners for ConfirmSubmit trait
    protected $listeners = ['store' => 'store'];

    # Storing transaction validation rules
    protected array $rules = [
        'title' => ['string', 'required', 'min:1'],
        'amount' => ['required', 'numeric'],
        'bank_card_id' => ['nullable', 'string', 'exists:bank_cards,id'],
    ];

    public function __construct($id = null)
    {
        # Initialize transaction service
        $this->service = new TransactionService();

        parent::__construct($id);
    }

    /**
     * Initialize component properties on component mount
     *
     * @return void
     */
    public function mount(): void
    {
        $this->bankCards = Auth::user()->bankCards()->orderBy('title')->get();
    }

    /**
     * Store new transaction in db
     *
     * @return void
     */
    public function store(): void
    {
        $this->validate();
        # Create transaction with given data
        $this->service->create($this->title, $this->amount, Auth::id(), $this->bank_card_id);

        # Show error alert on failure
        if (empty($this->service->get())) {
            $this->showAlert('Failed to create transaction', $this->icons['error']);
            return;
        }

        # Increase user balance
        $bankCard = BankCard::find($this->bank_card_id);
        event(new UpdateTransactionEvent($this->amount, Auth::user(), false, $bankCard));

        # Reload page on success
        $this->showAlert('Created transaction successfully', $this->icons['success']);
        $this->redirect(route('financial'));
    }

    public function render(): View
    {
        return view('livewire.financial.store');
    }
}
