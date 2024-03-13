<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Examen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
      'type',
      'heureD',
      'heureF',
      'dateE',
      'user_id',
      'vehicule_id',
];
public function resultat()
{
    return $this->hasOne(Resultat::class);
}
public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
