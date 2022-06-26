<?php

namespace Tests\Feature;

use App\Models\{
    Field,
    Subscriber
};
use Database\Seeders\{
    FieldSeeder,
    SubscriberSeeder
};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * Test it has table and all required columns.
     *
     * @return void
     */
    public function testItHasTableAndAllRequiredColumns()
    {
        $this->assertTrue(Schema::hasTable('subscribers'));

        $columns = Schema::getColumnListing('subscribers');
        $filtered = Arr::where($columns, fn($value) => in_array($value, ['email', 'name', 'state']));
        $this->assertSame(count($filtered), 3);
    }

    /**
     * Test it can retrieve subscribers.
     *
     * @return void
     */
    public function testItCanRetrieveSubscribers()
    {
        $this->seed(SubscriberSeeder::class);

        $response = $this->get('/api/v1/subscribers');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'email',
                'name',
                'state',
                'fields'
            ],
        ]);
    }

    /**
     * Test it can retrieve a subscriber
     *
     * @return void
     */
    public function testItCanRetrieveASubscriber()
    {
        $this->seed(SubscriberSeeder::class);
        $subscriber = Subscriber::first();

        $response = $this->get('/api/v1/subscribers/' . $subscriber->id);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'id',
            'email',
            'name',
            'state',
            'fields'
        ]);
    }

    /**
     * Test it can create a subscriber
     *
     * @return void
     */
    public function testItCanCreateASubscriber()
    {
        $this->seed(FieldSeeder::class);

        $name = fake()->name();
        $email = fake()->freeEmail();
        $state = Arr::random(['active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']);

        $field = Field::where('type', 'boolean')->first();

        $response = $this->postJson('/api/v1/subscribers', [
            'name' => $name,
            'email' => $email,
            'state' => $state,
            'fields' => [
                [
                    'id' => $field->id,
                    'value' => true,
                ],
            ],
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('subscribers', [
            'name' => $name,
            'email' => $email,
            'state' => $state
        ]);
        
        $this->assertSame($field->subscribers()->count(), 1);
    }

    /**
     * Test it can update a subscriber.
     *
     * @return void
     */
    public function testItCanUpdateASubscriber()
    {
        $this->seed(FieldSeeder::class);
        $this->seed(SubscriberSeeder::class);

        $field = Field::where('type', 'date')->first();
        $subscriber = Subscriber::first();
        $updatedName = 'Updated '. $subscriber->name;

        $now = now()->format('Y-m-d');

        $response = $this->putJson('/api/v1/subscribers/' . $subscriber->id, [
            'name' => $updatedName,
            'email' => $subscriber->email,
            'fields' => [
                [
                    'id' => $field->id,
                    'value' => $now,
                ],
            ]
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('subscribers', [
            'id' => $subscriber->id,
            'name' => $updatedName,
        ]);

        $this->assertDatabaseHas('field_subscriber', [
            'field_id' => $field->id,
            'subscriber_id' => $subscriber->id,
            'value' => $now,
        ]);
    }

    /**
     * Test it can remove a field.
     *
     * @return void
     */
    public function testUtCanRemoveASubscriber()
    {
        $this->seed(SubscriberSeeder::class);

        $totalSubscribers = Subscriber::count();
        $subscriber = Subscriber::first();

        $response = $this->delete('/api/v1/subscribers/' . $subscriber->id);

        $response->assertStatus(204);
        $this->assertDatabaseCount('subscribers', $totalSubscribers - 1);
    }
}
