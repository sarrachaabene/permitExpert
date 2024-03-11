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
];
public function resultat()
{
    return $this->hasOne(Resultat::class);
}
}
