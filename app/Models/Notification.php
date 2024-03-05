<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['message_id',  'receptient_msg','message_description','sender_msg'];
}
