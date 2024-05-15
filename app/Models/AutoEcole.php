<?php
namespace App\Models;
use App\Models\User;
use App\Models\AutoEcole;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoEcole extends Model
{
    use HasFactory;
    protected $fillable = [
      "nom" ,"adresse","description",
];
public function users(): HasMany
{
    return $this->hasMany(User::class);
}
public function user()
{
    return $this->hasOne(User::class);
}

public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
