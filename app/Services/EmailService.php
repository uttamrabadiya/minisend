<?php

namespace App\Services;

use App\Http\Requests\SendEmailRequest;
use App\Models\Email;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Email Services.
 */
class EmailService
{
    /**
     * @param SendEmailRequest $request
     * @return Email
     */
    public function storeEmail(SendEmailRequest $request): Email
    {
        $emailData = $request->only(['from', 'reply_to', 'subject', 'tags']);
        $emailData['text'] = $this->getTextFromHtml($request->get('content'));
        $emailData['html'] = $request->get('content');
        $email = Email::create($emailData);
        $this->storeRecipients($email, $request);
        $this->storeAttachments($email, $request);
        return $email;
    }


    /**
     * @param string $html
     * @return string
     */
    protected function getTextFromHtml($html): string
    {
        return strip_tags($html);
    }

    /**
     * @param Email $email
     * @param SendEmailRequest $request
     * @return void
     */
    protected function storeRecipients(Email $email, SendEmailRequest $request): void
    {
        $recipients = [];
        $to = $request->get('to');
        foreach($to as $val) {
            if(!isset($recipients[$val])) {
                $recipients[$val] = [
                    'email' => $val,
                    'type' => 1
                ];
            }
        }
        $cc = $request->get('cc', []);
        foreach($cc as $val) {
            if(!isset($recipients[$val])) {
                $recipients[$val] = [
                    'email' => $val,
                    'type' => 2
                ];
            }
        }
        $bcc = $request->get('bcc', []);
        foreach($bcc as $val) {
            if(!isset($recipients[$val])) {
                $recipients[$val] = [
                    'email' => $val,
                    'type' => 3
                ];
            }
        }
        $recipients = array_values($recipients);
        $email->recipients()->createMany($recipients);
    }


    /**
     * @param Email $email
     * @param Request $request
     * @return void
     */
    protected function storeAttachments(Email $email, Request $request): void
    {
        if(empty($request->attachments)) {
            return;
        }
        $attachments = [];
        \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/attachments/'.$email->id));
        foreach($request->attachments as $attachment) {
            if(!$attachment instanceof UploadedFile) {
                continue;
            }
            $attachment->storeAs('/public/attachments/'.$email->id, $attachment->getClientOriginalName());
            $attachments[] = [
                'filename' => $attachment->getClientOriginalName(),
                'path' => '/attachments/'.$email->id.'/'.$attachment->getClientOriginalName()
            ];
        }
        $email->attachments()->createMany($attachments);
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getSentEmails(Request $request): LengthAwarePaginator
    {
        $emails = Email::with('recipients');
        $search = $request->get('search');
        if(!empty($search)) {
            $emails->where('subject', 'LIKE', "%$search%")
                ->orWhereHas('recipients', function($query) use ($search) {
                    $query->where('email', 'LIKE', "%$search%");
                })
                ->orWhereJsonContains('tags', $search)
                ->orWhere('from', 'LIKE', "%$search%")

            ;
        }
        return $emails->orderBy('id', 'desc')
                ->paginate(10);
    }

    /**
     * @param int $id
     * @return null|Model
     */
    public function getEmail(int $id): null|Model
    {
        return Email::with(['recipients', 'attachments'])->where('id', $id)->first();
    }
}
