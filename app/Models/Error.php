<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;

    protected $fillable = [
        'error_message',
        'file',
        'line',
        ];
    protected $casts = [
        'date' => 'datetime',
    ];
}
