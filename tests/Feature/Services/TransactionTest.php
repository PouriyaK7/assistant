<?php

namespace Tests\Feature\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Services\BankCardService;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public User $user;
    const AMOUNT = 10000;

    protected function setUp(): void
    {
        parent::setUp();

        # Create test user with factory and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->user = $user;

        $this->setUpFaker();
    }

    public function test_store_positive_transaction()
    {
        # Create a new transaction with positive amount
        $service = new TransactionService();
        $transaction = $service->create('Test title 1', self::AMOUNT, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($transaction);
        $this->assertTrue(Transaction::where('id', $transaction->id)->exists());
    }

    public function test_store_negative_transaction()
    {
        # Create a new transaction with negative amount
        $service = new TransactionService();
        $transaction = $service->create('Test title 2', -self::AMOUNT, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($transaction);
    }

    public function test_update_transaction()
    {
        # Create a new transaction
        $transaction = TransactionService::create('Test title 3', self::AMOUNT, $this->user->id);


        # Update created transaction and get the difference
        $diff = TransactionService::update($transaction->id, 'Updated test title 3', -self::AMOUNT);

        # Check if diff is calculated correctly
        $this->assertEquals(-self::AMOUNT - self::AMOUNT, $diff);

        # Check if updated successfully in storage
        $newAmount = Transaction::where('amount', -self::AMOUNT)->first()->amount;
        $this->assertNotEquals($transaction->amount, $newAmount);
    }

    public function test_delete_transaction()
    {
        # Create a new transaction
        $transaction = TransactionService::create('Test title 4', self::AMOUNT, $this->user->id);

        # Get transaction id
        $id = $transaction->id;

        # Delete created transaction and get the difference
        $diff = TransactionService::delete($id);

        # Check if diff is returned correctly
        $this->assertEquals(-self::AMOUNT, $diff);

        # Check if transaction is deleted from db
        $this->assertFalse(Transaction::where('id', $id)->exists());
    }

    public function test_create_transaction_with_bank_card()
    {
        # Create new bank card
        $id = BankCardService::create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        )->id;

        # Create transaction with bank card
        $transaction = TransactionService::create(
            $this->faker->title,
            self::AMOUNT,
            $this->user->id,
            $id,
        );

        # Check if transaction created successfully
        $this->assertNotEmpty($transaction);
    }

    public function test_update_with_bank_card()
    {
        # Create new bank card
        $oldID = BankCardService::create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        )->id;

        # Create new transaction
        $oldTransaction = TransactionService::create(
            $this->faker->title,
            self::AMOUNT,
            $this->user->id,
            $oldID,
        );

        # Create a new bank card
        $newID = BankCardService::create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        )->id;

        # Assign new bank card to transaction
        TransactionService::update(
            id: $oldTransaction->id,
            bankCardID: $newID,
        );

        $transaction = Transaction::find($oldTransaction->id);

        # Check if transaction updated successfully
        $this->assertNotEquals($newID, $oldTransaction);
        $this->assertEquals($oldTransaction->amount, $transaction->amount);
        $this->assertEquals($oldTransaction->title, $transaction->title);
    }
}
