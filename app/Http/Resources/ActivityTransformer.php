<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $dbRecipients = $this->recipients->where('type', 'Recipient')
            ->pluck('email')
            ->toArray();
        return [
            'id' => $this->id,
            'from' => $this->from,
            'reply_to' => $this->reply_to,
            'subject' => $this->subject,
            'recipients' => $dbRecipients,
            'text' => $this->text,
            'html' => $this->html,
            'tags' => $this->tags ?? [],
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
