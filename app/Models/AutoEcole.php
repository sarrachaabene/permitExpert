<?php
namespace App\Models;
use App\Models\User;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoEcole extends Model
{
    use HasFactory;
    protected $fillable = [
      "nom" ,"adresse"
];
public function user()
{
    return $this->hasOne(User::class);
}
}
