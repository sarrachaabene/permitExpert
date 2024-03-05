<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
      'message_id', // Ajoutez message_id à la liste des attributs fillable
      'message_description',
      'sender_msg',
      'receptient_msg',
  ];}
