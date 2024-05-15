<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeInscription extends Model
{
    use HasFactory;
    protected $fillable = [
      'nomEcole',
      'aderesseEcole',
      'descriptionEcole',
      'imageEcole',
    'user_nameA',
      'emailA',
      'cin',
      'numTel',
      'imageA',
      'dateNaissance',
      'status',
];

public function user()
{
    return $this->belongsTo(User::class);
}
}
