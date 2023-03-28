<?php

namespace App\Http\Livewire\Financial;

use App\Events\UpdateTransactionEvent;
use App\Models\Transaction;
use App\Services\Livewire\HasAlert;
use App\Services\TransactionService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    use HasAlert;

    /**
     * Delete transaction from db and increase the amount from user balance
     *
     * @param Transaction $transaction
     * @return void
     */
    public function delete(Transaction $transaction): void
    {
        $service = new TransactionService($transaction);
        $amount = $service->delete();
        event(new UpdateTransactionEvent($amount, auth()->id()));

        $this->showAlert('Transaction deleted successfully', $this->icons['success']);
        $this->redirect(route('financial'));
    }

    public function render(): View
    {
        $transactions = Auth::user()->transactions()->with('bankCard')->paginate(20);
        return view('livewire.financial.index', compact('transactions'));
    }
}
