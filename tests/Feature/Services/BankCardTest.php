<?php

namespace Tests\Feature\Services;

use App\Models\BankCard;
use App\Models\User;
use App\Repositories\BankCardRepository;
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
        $card = BankCardService::create(
            'title',
            'card_number',
            $this->user->id
        );

        $this->assertNotEmpty($card);
    }

    public function test_update_bank_card()
    {
        $card = BankCardService::create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id,
        );

        $updated = BankCardService::update($card->id, 'new title', '1234567890');

        $card = (new BankCardRepository())->get($card->id);

        $this->assertEquals('new title', $card->title);
        $this->assertEquals('1234567890', $card->number);
        $this->assertTrue($updated);
    }

    public function test_delete_without_transactions()
    {
        $card = BankCardService::create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id
        );

        $id = $card->id;

        $deleted = BankCardService::delete($id);

        $this->assertFalse(BankCard::where('id', $id)->exists());
        $this->assertTrue($deleted);
    }

    public function test_delete_with_transactions()
    {
        $card = BankCardService::create(
            $this->faker->title,
            $this->faker->creditCardNumber,
            $this->user->id
        );

        $id = $card->id;

        TransactionService::create(
            $this->faker->title,
            $this->faker->numberBetween(100000, 150000),
            $this->user->id,
            $id,
        );

        $deleted = BankCardService::delete($id);

        $this->assertFalse($deleted);
        $this->assertTrue(BankCard::where('id', $id)->exists());
    }
}
