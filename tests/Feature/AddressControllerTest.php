<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_get_all_addresses()
    {
        Address::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('addresses.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['html']);
    }

    public function test_can_store_a_new_address()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $addressData = [
            'street_address' => 'Test Street',
            'apartment_suite_unit' => 'Test Apartment',
            'town_city' => 'Test City',
            'postcode' => '12345',
            'phone' => '01012345678',
        ];

        $response = $this->postJson(route('addresses.store'), $addressData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Address added successfully']);

        $this->assertDatabaseHas('addresses', [
            'street_address' => 'Test Street',
            'town_city' => 'Test City',
            'phone' => '01012345678',
        ]);
    }

    public function test_cannot_store_more_than_3_addresses()
    {
        Address::factory()->count(3)->create(['user_id' => $this->user->id]);

        $addressData = [
            'street_address' => 'Overflow Street',
            'apartment_suite_unit' => 'Apartment 4',
            'town_city' => 'Overflow City',
            'postcode' => '12345',
            'phone' => '01012345678',
        ];

        $response = $this->postJson(route('addresses.store'), $addressData);

        $response->assertStatus(400)
            ->assertJson(['message' => 'You can only have 3 addresses']);
    }

    public function test_can_update_an_address()
    {
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'street_address' => 'Updated Street',
            'apartment_suite_unit' => 'Updated Apartment',
            'town_city' => 'Updated City',
            'postcode' => '54321',
            'phone' => '01112345678',
        ];

        $response = $this->putJson(route('addresses.update', $address->id), $updatedData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Address updated successfully']);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'street_address' => 'Updated Street',
            'town_city' => 'Updated City',
            'phone' => '01112345678',
        ]);
    }

    public function test_can_delete_an_address()
    {
        $address = Address::factory()->create(['user_id' => $this->user->id, 'primary' => false]);

        $response = $this->deleteJson(route('addresses.destroy', $address->id));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Address deleted successfully']);

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_cannot_delete_primary_address()
    {
        $address = Address::factory()->create(['user_id' => $this->user->id, 'primary' => true]);

        $response = $this->deleteJson(route('addresses.destroy', $address->id));

        $response->assertStatus(400)
            ->assertJson(['message' => 'Primary address cannot be deleted']);
    }

    public function test_can_make_an_address_primary()
    {
        $address1 = Address::factory()->create(['user_id' => $this->user->id, 'primary' => true]);
        $address2 = Address::factory()->create(['user_id' => $this->user->id, 'primary' => false]);

        $response = $this->postJson(route('addresses.makePrimary', $address2->id));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Address set as primary successfully']);

        $this->assertDatabaseHas('addresses', ['id' => $address2->id, 'primary' => true]);
        $this->assertDatabaseHas('addresses', ['id' => $address1->id, 'primary' => null]);
    }
}
