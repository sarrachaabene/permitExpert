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
      'nomA',
      'prenomA',
      'emailA',
      'passwordA',
      'cin',
      'numTel',
      'imageA',
      'dateNaissance',
      
];
}
