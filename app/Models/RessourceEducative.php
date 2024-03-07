<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RessourceEducative extends Model
{    use HasFactory;
    use HasFactory;
    protected $fillable = [
      'titreR',
      'descriptionR',
      'typeR',
      'dateD',
];
}
