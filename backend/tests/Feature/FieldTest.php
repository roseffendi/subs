<?php

namespace Tests\Feature;

use App\Models\Field;
use Database\Seeders\FieldSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FieldTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test it has table and all required columns.
     *
     * @return void
     */
    public function testItHasTableAndAllRequiredColumns()
    {
        $this->assertTrue(Schema::hasTable('fields'));

        $columns = Schema::getColumnListing('fields');
        $filtered = Arr::where($columns, fn($value) => in_array($value, ['title', 'type']));
        $this->assertSame(count($filtered), 2);
    }

    /**
     * Test it can retrieve fields.
     *
     * @return void
     */
    public function testItCanRetrieveFields()
    {
        $this->seed(FieldSeeder::class);

        $response = $this->get('/api/v1/fields');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'type',
            ],
        ]);
    }

    /**
     * Test it can create a field.
     *
     * @return void
     */
    public function testItCanCreateAField()
    {
        $response = $this->post('/api/v1/fields', [
            'title' => 'Test',
            'type' => 'number'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('fields', [
            'title' => 'Test',
            'type' => 'number'
        ]);
    }

    /**
     * Test if can update a field.
     *
     * @return void
     */
    public function testItCanUpdateAField()
    {
        $this->seed(FieldSeeder::class);

        $field = Field::first();
        $updatedTitle = 'Updated ' . $field->title;

        $response = $this->put('/api/v1/fields/' . $field->id, [
            'title' => $updatedTitle,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('fields', [
            'id' => $field->id,
            'title' => $updatedTitle,
            'type' => $field->type,
        ]);
    }

    /**
     * Test it can validate input
     *
     * @return void
     */
    public function testItCanValidateInput()
    {
        $response = $this->postJson('/api/v1/fields', [
            'title' => '',
            'type' => ''
        ]);

        $response->assertStatus(422);

        $this->seed(FieldSeeder::class);

        $field = Field::first();
        $response = $this->putJson('/api/v1/fields/' . $field->id, [
            'title' => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test it can remove a field.
     *
     * @return void
     */
    public function testItCanRemoveAField()
    {
        $this->seed(FieldSeeder::class);

        $totalField = Field::count();
        $field = Field::first();

        $response = $this->delete('/api/v1/fields/' . $field->id);

        $response->assertStatus(204);
        $this->assertDatabaseCount('fields', $totalField - 1);
    }
}
