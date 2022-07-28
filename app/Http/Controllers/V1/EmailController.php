<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendEmailRequest;
use App\Http\Resources\ActivityTransformer;
use App\Http\Resources\EmailTransformer;
use App\Jobs\SendEmailJob;
use App\Services\EmailService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 *
 */
class EmailController extends Controller
{
    /**
     * @var EmailService
     */
    protected $service;

    /**
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->service = $emailService;
    }

    /**
     * Send Email
     * @param SendEmailRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function sendEmail(SendEmailRequest $request): Application|ResponseFactory|Response
    {
        $storedEmail = $this->service->storeEmail($request);
        SendEmailJob::dispatch($storedEmail->id);
        return response([], 201);
    }

    /**
     * Get a list of activities for sent emails.
     * @param Request $request
     * @return Application|Response|ResponseFactory|EmailTransformer
     */
    public function viewEmail(Request $request, $id): Application|ResponseFactory|Response|EmailTransformer
    {
        $email = $this->service->getEmail($id);
        if(!$email) {
            return response(['message' => 'Email not found!'], 404);
        }
        return new EmailTransformer($email);
    }
    /**
     * Get a list of activities for sent emails.
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function activities(Request $request): AnonymousResourceCollection
    {
        $activities = $this->service->getSentEmails($request);
        return ActivityTransformer::collection($activities);
    }
}
