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
];

public function moniteur()
{
    return $this->belongsTo(User::class, 'moniteur_id');
}

public function candidat()
{
    return $this->belongsTo(User::class, 'candidat_id');
}
}
