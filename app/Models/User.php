<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'google_id',
        'avatar',
        'phone',
        'role',
        'password',
        'jenis_kelamin',
        'profesi',
        'tanggal_lahir',
        'institusi',
        'alamat',
        'status_user',
    ];

    /**
     * Apakah user (login Google) masih perlu melengkapi profil (phone, alamat).
     */
    public function needsProfileCompletion(): bool
    {
        return $this->google_id !== null
            && (trim((string) $this->phone) === '' || trim((string) $this->alamat) === '' || trim((string) $this->password) === '');
    }

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

    public function joinedPackages()
    {
        return $this->hasMany(UserJoin::class, 'user_id');
    }

    public function transQuestions(): HasMany
    {
        return $this->hasMany(TransQuestion::class, 'id_user');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'id_user');
    }
}
