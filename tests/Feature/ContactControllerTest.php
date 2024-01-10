<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMailSubmitted;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_submits_form_and_sends_email()
    {
        // Arrange
        Mail::fake(); // Mock the Mail facade

        $formData = [
            'name' => 'John Doe',
            'email' => 'test@test.com',
            'subject' => 'Test subject',
            'message' => 'Test message',
        ];

        // Act
        $response = $this->json('POST', '/api/contact', $formData); // Update the endpoint here

        // Assert
        $response->assertJson(['message' => 'Form submitted successfully']);
        $response->assertStatus(200);

        // Assert that the ContactMailSubmitted Mailable was sent with the correct data
        Mail::assertSent(ContactMailSubmitted::class, function ($mail) use ($formData) {
            return $mail->name === $formData['name'] &&
                   $mail->email === $formData['email'] &&
                   $mail->subject === $formData['subject'] &&
                   $mail->message === $formData['message'];
        });
    }
}
