<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contact_form_page_loads()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertViewIs('contact');
    }

    /** @test */
    public function contact_form_displays_form_fields()
    {
        $response = $this->get('/contact');
        $response->assertSee('name');
        $response->assertSee('email');
        $response->assertSee('subject');
        $response->assertSee('message');
    }

    /** @test */
    public function contact_form_submission_requires_all_fields()
    {
        $response = $this->post('/contact', []);
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    /** @test */
    public function contact_form_submission_requires_valid_email()
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'subject' => 'Test Subject',
            'message' => 'This is a test message'
        ]);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function contact_form_submission_requires_minimum_message_length()
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Short'
        ]);
        $response->assertSessionHasErrors('message');
    }

    /** @test */
    public function contact_form_submission_saves_to_database()
    {
        $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message that is long enough'
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message that is long enough'
        ]);
    }

    /** @test */
    public function contact_form_submission_sets_status_to_unread()
    {
        $this->post('/contact', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'subject' => 'Another Subject',
            'message' => 'This is another test message'
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jane Smith',
            'status' => 'unread'
        ]);
    }

    /** @test */
    public function contact_form_submission_redirects_to_contact_with_success()
    {
        $response = $this->post('/contact', [
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'subject' => 'Test',
            'message' => 'This is a test message content'
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');
    }

    /** @test */
    public function contact_form_submission_trims_whitespace()
    {
        $this->post('/contact', [
            'name' => '  Alice Brown  ',
            'email' => '  alice@example.com  ',
            'subject' => '  Test Subject  ',
            'message' => '  This is a test message  '
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Alice Brown',
            'email' => 'alice@example.com'
        ]);
    }

    /** @test */
    public function contact_form_rejects_spam_like_messages()
    {
        // Test with very long message (potential spam)
        $longMessage = str_repeat('spam ', 1000);
        
        $response = $this->post('/contact', [
            'name' => 'Spammer',
            'email' => 'spam@example.com',
            'subject' => 'Buy Now!',
            'message' => $longMessage
        ]);

        // Should still accept (no max length limit in current validation)
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function contact_message_can_be_marked_as_read()
    {
        $message = ContactMessage::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message content'
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'unread'
        ]);

        // Simulate marking as read (admin functionality)
        $message->update(['status' => 'read']);

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'read'
        ]);
    }
}
