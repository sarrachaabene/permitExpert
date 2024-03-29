<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;
    protected $fillable = [
      'type',
      'heureD',
      'heureF',
      'dateS',
      'status',
      'vehicule_id',
      'candidat_id',
      'moniteur_id',
      'candidat_status',
      'moniteur_status',

];
  public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

}
