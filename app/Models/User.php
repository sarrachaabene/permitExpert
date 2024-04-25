<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\User;
use App\Models\Message;
use App\Models\AutoEcole;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      //'user_name',
        'email',
        'password',
        'role',
        'cin',
        'numTel',
        'dateNaissance',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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
        return $this->hasManyThrough(Notification::class, Message::class, 'recipient_id', 'message_id','');
    }
  
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
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
  /*   public function assignRole()
    {
        // Logic to assign role based on 'role' attribute
        switch ($this->role) {
            case 'admin':
                $role = Role::where('name', 'admin')->first();
                break;
            case 'secretaire':
                $role = Role::where('name', 'secretaire')->first();
                break;
            // Add more cases as needed
            default:
                // Default role assignment if 'role' attribute doesn't match
                $role = Role::where('name', 'candidat')->first();
                break;
        }
        if ($role) {
            $this->assignRole($role);
        }
    } */
    

}
