<?php

namespace Tests\Feature\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Services\BankCardService;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
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
        $service = $this->app->make('transaction.service');
        $service->create('Test title 1', self::AMOUNT, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($service->get());
    }

    public function test_store_negative_transaction()
    {
        # Create a new transaction with negative amount
        $service = $this->app->make('transaction.service');
        $service->create('Test title 2', -self::AMOUNT, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($service->get());
    }

    public function test_update_transaction()
    {
        # Create a new transaction
        $service = $this->app->make('transaction.service');
        $service->create('Test title 3', self::AMOUNT, $this->user->id);

        # Update created transaction and get the difference
        $diff = $service->update('Updated test title 3', -self::AMOUNT);

        # Check if diff is calculated correctly
        $this->assertEquals(-self::AMOUNT - self::AMOUNT, $diff);
    }

    public function test_delete_transaction()
    {
        # Create a new transaction
        $service = $this->app->make('transaction.service');
        $service->create('Test title 4', self::AMOUNT, $this->user->id);

        # Get transaction id
        $id = $service->get()->id;

        # Delete created transaction and get the difference
        $diff = $service->delete();

        # Check if diff is returned correctly
        $this->assertEquals(-self::AMOUNT, $diff);

        # Check if transaction is deleted from db
        $this->assertFalse(Transaction::where('id', $id)->exists());
    }

    public function test_set_transaction_with_id()
    {
        # Create a new transaction service instance
        $service = $this->app->make('transaction.service');

        # Check if transaction is empty
        $this->assertEmpty($service->get());

        # Create a new transaction with model itself
        $transaction = Transaction::create([
            'id' => Str::uuid()->toString(),
            'title' => 'Test Transaction',
            'amount' => self::AMOUNT,
            'user_id' => $this->user->id,
        ]);

        # Set service transaction with created transaction id
        $service->set($transaction->id);

        # Check if service transaction is instance of Transaction model
        $this->assertTrue($service->get() instanceof Transaction);
        # Check if service transaction is equal with created transaction before
        $this->assertTrue($service->get()->id == $transaction->id);
    }

    public function test_set_transaction_with_model_instance()
    {
        # Create a new transaction service instance
        $service = $this->app->make('transaction.service');

        # Check if transaction of service is empty
        $this->assertEmpty($service->get());

        # Create new transaction without transaction service
        $transaction = Transaction::create([
            'id' => Str::uuid()->toString(),
            'title' => 'Test Transaction',
            'amount' => self::AMOUNT,
            'user_id' => $this->user->id,
        ]);

        # Set transaction with transaction model instance
        $service->set($transaction);

        # Check if service transaction is the same with we created
        $this->assertTrue($service->get() == $transaction);
    }

    public function test_create_transaction_with_bank_card()
    {
        # Create a new transaction service instance
        $service = $this->app->make('transaction.service');

        # Create new bank card
        $id = (new BankCardService())->create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        );

        # Create transaction with bank card
        $service->create(
            $this->faker->title,
            self::AMOUNT,
            $this->user->id,
            $id,
        );

        # Check if transaction created successfully
        $this->assertNotEmpty($service->get());
    }

    public function test_update_with_bank_card()
    {
        # Create a new transaction service instance
        $service = $this->app->make('transaction.service');

        # Create new bank card
        $oldID = (new BankCardService())->create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        );

        # Create new transaction
        $service->create(
            $this->faker->title,
            self::AMOUNT,
            $this->user->id,
            $oldID,
        );

        # Keep old transaction
        $oldTransaction = $service->get();

        # Create a new bank card
        $newID = (new BankCardService())->create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        );

        # Assign new bank card to transaction
        $service->update(
            bankCardID: $newID,
        );

        # Check if transaction updated successfully
        $this->assertNotEquals($newID, $oldTransaction);
        $this->assertEquals($oldTransaction->amount, $service->get()->amount);
        $this->assertEquals($oldTransaction->title, $service->get()->title);
    }
}
