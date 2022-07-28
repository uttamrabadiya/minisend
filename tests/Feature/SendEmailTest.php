<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    protected function apiHeaders() {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'multipart/form-data'
        ];
    }

    protected function randomTags() {
        $tags = ['B2B', 'B2C', 'first-email', 'marketing', 'spam-filter', 'dns-check', 'random-word'];
        return Arr::random($tags, 4);
    }

    /**
     * @return void
     */
    public function test_send_email_without_any_data()
    {
        $response = $this->post('/api/v1/email', [], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'from',
                'subject',
                'content',
                'to'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function test_send_email_with_required_fields()
    {
        $emailData = [
            'from' => $this->faker()->safeEmail,
            'subject' => $this->faker()->text(65),
            'content' => $this->faker()->realText(1500),
            'to' => [
                $this->faker()->safeEmail,
            ]
        ];
        $response = $this->post('/api/v1/email', $emailData, $this->apiHeaders());

        $response->assertStatus(201);
    }

    /**
     * @return void
     */
    public function test_send_email_with_attachments()
    {

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image(Str::slug($this->faker()->name).'.png', 500, 500);

        $emailData = [
            'from' => $this->faker()->safeEmail,
            'subject' => $this->faker()->text(65),
            'content' => $this->faker()->realText(1500),
            'to' => [
                $this->faker()->safeEmail
            ],
            'attachments' => [
                $file
            ]
        ];
        $response = $this->post('/api/v1/email', $emailData, $this->apiHeaders());
        $response->assertStatus(201);
    }

    /**
     * @return void
     */
    public function test_send_email_with_tags()
    {
        $emailData = [
            'from' => $this->faker()->safeEmail,
            'subject' => $this->faker()->text(65),
            'content' => $this->faker()->realText(1500),
            'to' => [
                $this->faker()->safeEmail
            ],
            'tags' => $this->randomTags()
        ];
        $response = $this->post('/api/v1/email', $emailData, $this->apiHeaders());
        $response->assertStatus(201);
    }

    /**
     * @return void
     */
    public function test_send_email_with_multiple_recipients()
    {
        $fakeEmails = [];
        for($start=1;$start<=10;$start++) {
            $fakeEmails[] = $this->faker()->safeEmail;
        }
        $emailData = [
            'from' => $this->faker()->safeEmail,
            'subject' => $this->faker()->text(65),
            'content' => $this->faker()->realText(1500),
            'to' => $fakeEmails,
            'tags' => $this->randomTags()
        ];
        $response = $this->post('/api/v1/email', $emailData, $this->apiHeaders());
        $response->assertStatus(201);
    }

    /**
     * @return void
     */
    public function test_send_email_with_all_possible_fields()
    {
        $fakeEmails = [];
        $ccEmails = [];
        $bccEmails = [];
        $attachments = [];

        for($start=1;$start<=rand(5,8);$start++) {
            $fakeEmails[] = $this->faker()->safeEmail;
            $ccEmails[] = $this->faker()->safeEmail;
            $bccEmails[] = $this->faker()->safeEmail;
        }

        for($start=1;$start<=rand(5,8); $start++) {
            Storage::fake('avatars');
            $attachments[] = UploadedFile::fake()->image(Str::slug($this->faker()->name).'.png', 500, 500);
        }

        $emailData = [
            'from' => $this->faker()->safeEmail,
            'reply_to' => $this->faker()->safeEmail,
            'subject' => $this->faker()->text(65),
            'content' => $this->faker()->realText(1500),
            'to' => $fakeEmails,
            'cc' => $ccEmails,
            'bcc' => $bccEmails,
            'tags' => $this->randomTags(),
            'attachments' => $attachments
        ];
        $response = $this->post('/api/v1/email', $emailData, $this->apiHeaders());
        $response->assertStatus(201);
    }
}
