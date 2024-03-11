<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    

    protected $fillable = ['dateT', 'typeT', 'montantT','description'];

    public function autoecole(): BelongsTo
    {
        return $this->belongsTo(Autoecoles::class);
    }

    
    public function vehicules(): HasMany
    {
        return $this->hasMany(Vehicule::class);
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
