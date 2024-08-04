<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private $note;
    private $apiUrl = '/api';
    private $current_note_id;

    /**
     * A basic feature test example.
     */

    public function setUp(): void
    {
        parent::setUp();
        $this->note = [
            'title' => $this->faker->sentence,
            'text' => $this->faker->paragraph
        ];
        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }


    public function test_create_note(): void
    {
        $endpoint = $this->apiUrl . '/note';
        $response = $this->post($endpoint, $this->note);
        $response->assertStatus(200);
        $this->current_note_id = $response->json('id');
    }


    public function test_get_all_notes(): void
    {
        // make sure there is a note
        $this->test_create_note();

        // get all notes
        $endpoint = $this->apiUrl . '/notes';
        $response = $this->get($endpoint);
        $response->assertStatus(200);

        // check if the note is in the response
        $response->assertJsonFragment($this->note);
    }


    public function test_get_note(): void
    {
        // make sure there is a note
        $this->test_create_note();

        // get the note
        $endpoint = $this->apiUrl . '/note/' . $this->current_note_id;
        $response = $this->get($endpoint);
        // check if the note is in the response
        $response->assertStatus(200);

        // check if the note is in the response
        $response->assertJsonFragment($this->note);
    }

    public function test_update_note(): void
    {
        // make sure there is a note
        $this->test_create_note();

        // update the note
        $this->note['title'] = $this->faker->sentence;
        $this->note['text'] = $this->faker->paragraph;
        $endpoint = $this->apiUrl . '/note/' . $this->current_note_id;
        $response = $this->put($endpoint, $this->note);
        $response->assertStatus(200);

        // check if the note is updated
        $response->assertJsonFragment($this->note);
    }


    public function test_delete_note(): void
    {
        // make sure there is a note
        $this->test_create_note();

        // delete the note
        $endpoint = $this->apiUrl . '/note/' . $this->current_note_id;
        $response = $this->delete($endpoint);
        $response->assertStatus(200);

        // check if the note is deleted
        $response->assertJsonFragment(['message' => 'Note deleted']);
    }
}
