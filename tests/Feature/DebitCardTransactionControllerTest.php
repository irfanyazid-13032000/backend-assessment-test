<?php

namespace Tests\Feature;

use App\Models\DebitCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DebitCardTransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected DebitCard $debitCard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->debitCard = DebitCard::factory()->create([
            'user_id' => $this->user->id
        ]);
        Passport::actingAs($this->user);
    }

    public function testCustomerCanSeeAListOfDebitCardTransactions()
    {
        // get /debit-card-transactions
        $response = $this->get("/debit-card-transactions");
        $response->assertStatus(200);
        $response->assertJSON([
            'data' => [
                'id','debit_card_id','amount','currency_code'
            ]
            ]);
    }

    public function testCustomerCannotSeeAListOfDebitCardTransactionsOfOtherCustomerDebitCard()
    {
        $response = $this->get("/debit-card-transactions");
        $response->assertStatus(403);
        $response->assertJSON([
            'data' => 'you are not allowed']);
    }

    public function testCustomerCanCreateADebitCardTransaction()
    {
        $response = $this->post("/debit-card-transactions");
        $response->assertStatus(200);
        $response->assertJSON([
            'data' => 'transaction created succesfully!'
        ]);
    }

    public function testCustomerCannotCreateADebitCardTransactionToOtherCustomerDebitCard()
    {
        $response = $this->post("/debit-card-transactions");
        $response->assertStatus(403);
        $response->assertJSON([
            'data' => 'You are not allowed!'
        ]);
    }

    public function testCustomerCanSeeADebitCardTransaction(Request $request)
    {
        $response = $this->get("/debit-cards-transactions/{$request->id}");
        $response->assertStatus(200);
        $response->assertJSON([
            'data' => [
                'id','debit_card_id','amount','currency_code'
            ]
            ]);    
        
    }

    public function testCustomerCannotSeeADebitCardTransactionAttachedToOtherCustomerDebitCard(Request $request)
    {
        $response = $this->get("/debit-cards-transactions/{$request->id}");
        $response->assertStatus(403);
        $response->assertJSON([
            'data' => 'you are not allowed!']);    

    }

    // Extra bonus for extra tests :)
}
