<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $dbRecipients = $this->recipients;
        $recipients = $dbRecipients->where('type', 'Recipient')->pluck('email')->toArray();
        $cc = $dbRecipients->where('type', 'CC')->pluck('email')->toArray();
        $bcc = $dbRecipients->where('type', 'BCC')->pluck('email')->toArray();

        $attachments = $this->attachments->map(function($attachment) {
            return [
                'filename' => $attachment->filename,
                'url' => $attachment->url,
            ];
        });

        return [
            'id' => $this->id,
            'from' => $this->from,
            'reply_to' => $this->reply_to,
            'subject' => $this->subject,
            'recipients' => $recipients,
            'cc' => $cc,
            'bcc' => $bcc,
            'text' => $this->text,
            'html' => $this->html,
            'tags' => $this->tags ?? [],
            'status' => $this->status,
            'attachments' => $attachments,
            'created_at' => $this->created_at,
        ];
    }
}
