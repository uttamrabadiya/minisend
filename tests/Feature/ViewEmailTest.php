<?php

namespace Tests\Feature;

use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ViewEmailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_email_id()
    {
        $email = Email::all()->random()->first();
        if (!$email) {
            return;
        }
        $response = $this->get('/api/v1/email/' . $email->id);

        $response->assertStatus(200);
    }

    public function test_invalid_email_id()
    {
        $randomEmailId = rand(1000, 250000);
        $response = $this->get('/api/v1/email/' . $randomEmailId);
        $response->assertStatus(404);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
    }

    public function test_email_response_value_type()
    {
        $email = Email::all()->random()->first();
        if (!$email) {
            return;
        }
        $response = $this->get('/api/v1/email/' . $email->id);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('data', function (AssertableJson $email) {
                $email->whereAllType([
                    'id' => ['integer'],
                    'from' => ['string'],
                    'reply_to' => ['string', 'null'],
                    'subject' => ['string'],
                    'text' => ['string'],
                    'html' => ['string'],
                    'tags' => ['array'],
                    'status' => ['string'],
                    'created_at' => ['string'],
                    'recipients' => ['array'],
                    'cc' => ['array'],
                    'bcc' => ['array'],
                    'attachments' => ['array'],
                ]);
                $this->assertTrue(in_array($email->toArray()['status'], ['Sent', 'Posted', 'Failed']), 'Invalid value for status');
            });
        });
    }
}
