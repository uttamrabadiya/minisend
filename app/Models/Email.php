<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tags' => 'array',
    ];

    public function attachments()
    {
        return $this->hasMany(EmailAttachment::class);
    }

    public function recipients()
    {
        return $this->hasMany(EmailRecipient::class);
    }

    public function getStatusAttribute($status)
    {
        if($status === 0) {
            return 'Posted';
        }
        if($status === 2) {
            return 'Failed';
        }
        return 'Sent';
    }
}
