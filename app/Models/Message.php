<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Message;
use App\Models\User;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['dateM', 'description', 'sender_id', 'recipient_id'];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

}
