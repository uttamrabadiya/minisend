<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_status()
    {
        $response = $this->get('/api/v1/activities');
        $response->assertStatus(200);
    }

    public function test_activity_values()
    {
        $response = $this->get('/api/v1/activities');

        if(empty($response->json('data'))) {
            return;
        }
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'from',
                    'reply_to',
                    'subject',
                    'text',
                    'html',
                    'tags',
                    'created_at',
                    'status'
                ]
            ]
        ]);
    }

    public function test_activity_sorted_by_latest()
    {
        $response = $this->get('/api/v1/activities');

        if(empty($response->json('data'))) {
            return;
        }

        $lastId = INF;
        foreach($response->json('data') as $mail) {
            if($lastId > $mail['id']) {
                $lastId = $mail['id'];
            } else {
                $this->assertTrue($lastId > $mail['id'], 'Invalid sort order in activities.');
                return;
            }
        }
        $this->assertTrue(true);
    }

    public function test_verify_activity_value_type()
    {
        $response = $this->get('/api/v1/activities');

        if(empty($response->json('data'))) {
            return;
        }

        $response->assertJson(function(AssertableJson $json) {
            $json->has('data',function(AssertableJson $emails) {
                $emails->each(function(AssertableJson $email) {
                    $email->whereAllType([
                       'id' => ['integer'],
                       'from' => ['string'],
                       'reply_to' => ['string', 'null'],
                       'subject' => ['string'],
                       'text' => ['string'],
                       'html' => ['string'],
                       'tags' => ['array'],
                       'recipients' => ['array'],
                       'status' => ['string'],
                       'created_at' => ['string'],
                   ])->etc();
                   $this->assertTrue(in_array($email->toArray()['status'], ['Sent', 'Posted', 'Failed']), 'Invalid value for status');
                });
            })->has('meta')->has('links');
        });
    }
}
