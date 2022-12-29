<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DebitCardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Passport::actingAs($this->user);
    }

    public function testCustomerCanSeeAListOfDebitCards()
    {
        $response = $user->get('/debit-cards');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id','debit_card_id','number','type','expiration_date','disabled_at'
            ]
        ]);

    }

    public function testCustomerCannotSeeAListOfDebitCardsOfOtherCustomers()
    {
        $response = $user->get('/debit-cards');

        $response->assertStatus(403);
        $response->assertJson([
            'data' =>'you are not allowed'
        ]);
    }

    public function testCustomerCanCreateADebitCard()
    {
        $response = $user->post('/debit-cards');
        $response->assertStatus(200);
        $response->assertJson([
            'data' =>'data created successfully!'
        ]);
    }

    public function testCustomerCanSeeASingleDebitCardDetails()
    {
        // get api/debit-cards/{debitCard}
        $response = $this->get("/debit-cards/{$user->id}");
        $response->assertStatus(200);
        $response->assertJSON([
            'data' => [
                'id','card_id','transaction'
            ]
            ]);   
    }

    public function testCustomerCannotSeeASingleDebitCardDetails()
    {
        // get api/debit-cards/{debitCard}
        $response = $this->get("/debit-cards/{$user->id}");
        $response->assertStatus(403);
        $response->assertJSON([
            'data' => 'you are not allowed!']);   
    }

    public function testCustomerCanActivateADebitCard()
    {
        // put api/debit-cards/{debitCard}
        $response = $this->put("/debit-cards/{$user->id}");
        $response->assertStatus(200);
        $response->assertJSON([
            'data' => 'Debit Card Activated Successfully!']);   
    }

    public function testCustomerCanDeactivateADebitCard()
    {
        // put api/debit-cards/{debitCard}
        $response = $this->put("/debit-cards/{$user->id}");
        $response->assertStatus(200);
        $response->assertJSON([
            'data' => 'Debit Card Deactivated Successfully!']);   
    }

    public function testCustomerCannotUpdateADebitCardWithWrongValidation()
    {
        // put api/debit-cards/{debitCard}
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'number' => 'required',
            'type' => 'required'
        ]);
        $response = $this->put("/debit-cards/{$user->id}");
        $response->assertTrue($validator->fails());
        $response->assertStatus(302);
        $response->assertJSON([
            'data' => 'Debit Card Cannot Update with Wrong Validation!']); 
    }

    public function testCustomerCanDeleteADebitCard()
    {
        // delete api/debit-cards/{debitCard}
        $response = $this->delete("/debit-cards/{$user->id}");
        $response->assertStatus(200);
        $response->assertJSON([
            'data' => 'Debit Card Deleted Successfully!']);   
    }

    public function testCustomerCannotDeleteADebitCardWithTransaction()
    {
        // delete api/debit-cards/{debitCard}
        $response = $this->delete("/debit-cards/{$user->id}");
        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'You cannot delete a debit card with transactions !!.'
        ]);
    }

    // Extra bonus for extra tests :)
}
