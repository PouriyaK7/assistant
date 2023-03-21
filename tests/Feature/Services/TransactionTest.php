<?php

namespace Tests\Feature\Services;

use App\Models\Transaction;
use App\Models\User;
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
        $service->create('Test title 1', self::AMOUNT, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($service->get());
    }

    public function test_store_negative_transaction()
    {
        # Create a new transaction with negative amount
        $service = new TransactionService();
        $service->create('Test title 2', -self::AMOUNT, $this->user->id);

        # Assert if transaction created successfully
        $this->assertNotEmpty($service->get());
    }

    public function test_update_transaction()
    {
        # Create a new transaction
        $service = new TransactionService();
        $service->create('Test title 3', self::AMOUNT, $this->user->id);

        # Update created transaction and get the difference
        $diff = $service->update('Updated test title 3', -self::AMOUNT);

        # Check if diff is calculated correctly
        $this->assertEquals(-self::AMOUNT - self::AMOUNT, $diff);
    }

    public function test_delete_transaction()
    {
        # Create a new transaction
        $service = new TransactionService();
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
}
