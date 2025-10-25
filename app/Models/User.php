<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sys_types_user_id',
        'phone',
        'etat'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // العلاقات
    public function sysTypesUser()
    {
        return $this->belongsTo(SysTypesUser::class, 'sys_types_user_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'sys_user_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->sys_types_user_id == 1; // admin type
    }

    public function isTeacher()
    {
        return $this->sys_types_user_id == 2; // teacher type
    }
}
