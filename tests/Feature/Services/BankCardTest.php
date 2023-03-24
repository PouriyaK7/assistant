<?php

namespace Tests\Feature\Services;

use App\Models\BankCard;
use App\Models\User;
use App\Services\BankCardService;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BankCardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public User $user;

    public function setUp(): void
    {
        parent::setUp();

        # Create fake user with faker and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->user = $user;

        $this->setUpFaker();
    }

    public function test_store_bank_card()
    {
        $service = new BankCardService();

        $service->create(
            'title',
            'card_number',
            $this->user->id
        );

        $this->assertNotEmpty($service->get());
    }

    public function test_update_bank_card()
    {
        $service = new BankCardService();

        $service->create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        );

        $updated = $service->update('new title', '1234567890');

        $this->assertEquals('new title', $service->get()->title);
        $this->assertEquals('1234567890', $service->get()->number);
        $this->assertTrue($updated);
    }

    public function test_delete_without_transactions()
    {
        $service = new BankCardService();
        $service->create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id
        );

        $id = $service->get()->id;

        $deleted = $service->delete();

        $this->assertFalse(BankCard::where('id', $id)->exists());
        $this->assertTrue($deleted);
    }

    public function test_delete_with_transactions()
    {
        $service = new BankCardService();
        $service->create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id
        );

        $id = $service->get()->id;

        (new TransactionService())->create(
            $this->faker->title,
            $this->faker->numberBetween(100000, 150000),
            $id,
            $this->user->id,
        );

        $deleted = $service->delete();

        $this->assertFalse($deleted);
        $this->assertTrue(BankCard::where('id', $id)->exists());
    }
}
