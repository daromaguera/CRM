<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'subtitle',
        'created_at',
        'isSeen',
        'user_id',
        'img',
    ];

    // Make the id and user_id fields nullable
    protected $casts = [
        'id' => 'string',
        'user_id' => 'integer',
    ];
}
