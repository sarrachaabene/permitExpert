<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Message;
use App\Models\AutoEcole;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'role',
        'cin',
        'numTel',
        'dateNaissance',
        'verification_code',
        'user_image',
        'auto_ecole_id',
        'autorite_de_delivrance'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function autoEcole()
    {
        return $this->belongsTo(AutoEcole::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function notifications()
    {
        return $this->hasManyThrough(Notification::class, Message::class, 'recipient_id', 'message_id', '');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id');
    }

    
    public function demandeInscriptions()
    {
        return $this->hasMany(DemandeInscription::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    public function examens()
    {
        return $this->hasMany(Examen::class);
    }

    public function resources()
    {
        return $this->belongsToMany(Resource::class);
    }

}
