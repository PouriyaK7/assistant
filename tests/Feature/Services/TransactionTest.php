<?php

namespace Tests\Feature\Services;

use App\Events\UpdateTransactionEvent;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public function __construct(string $name)
    {
        # Create test user with factory and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->user = $user;

        parent::__construct($name);
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_store_positive_transaction()
    {
        # Get user balance
        $balance = $this->user->balance;

        # Create a new transaction with positive amount
        $service = new TransactionService();
        $transaction = $service->create(10000, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($transaction);

        # Trigger create transaction event
        event(new UpdateTransactionEvent($transaction, $this->user));

        # Check if user balance increased
        $this->assertTrue($balance < $this->user->balance);
    }

    public function test_store_negative_transaction()
    {
        # Get user balance
        $balance = $this->user->balance;

        # Create a new transaction with negative amount
        $service = new TransactionService();
        $transaction = $service->create(-10000, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($transaction);

        # Trigger create transaction event
        event(new UpdateTransactionEvent($transaction, $this->user));

        # Check if user balance decreased
        $this->assertTrue($balance > $this->user->balance);
    }
}
