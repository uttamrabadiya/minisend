<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return url('/storage'.$this->path);
    }
}
