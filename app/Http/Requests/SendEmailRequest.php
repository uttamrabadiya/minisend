<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from' => 'required|email|max:100',
            'reply_to' => 'nullable|email|max:100',
            'to' => 'required|array',
            'to.*' => 'required|email',
            'cc' => 'nullable|array',
            'cc.*' => 'sometimes|email',
            'bcc' => 'nullable|array',
            'bcc.*' => 'sometimes|email',
            'subject' => 'required|string|max:200',
            'content' => 'required',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file',
            'tags' => 'nullable|array',
        ];
    }

    public function attributes()
    {
        return [
            'to' => 'recipient'
        ];
    }
}
