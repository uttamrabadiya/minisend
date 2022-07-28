<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailRecipient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Possible values for: type
     * 1. Recipient
     * 2. CC
     * 3. BCC
     */

    public function getTypeAttribute($type)
    {
        if($type === '2') {
            return 'CC';
        }
        if($type === '3') {
            return 'BCC';
        }
        return 'Recipient';
    }
}
