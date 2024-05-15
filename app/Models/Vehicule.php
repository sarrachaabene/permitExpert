<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Vehicule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
      "immatricule" ,"kilometrage","marque","typeV",'auto_ecole_id',

];
public function transaction(): BelongsTo
{
    return $this->belongsTo(Transaction::class);
}
public function examens()
{
    return $this->hasMany(Examen::class);
}
public function seances()
{
    return $this->hasMany(Seance::class);
}
}
