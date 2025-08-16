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
        // ❌ HAPUS 'username',
        'email',
        'password',
        'email_verified_at',
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke transaksi
     */
    public function transaksis()
    {
        return $this->hasMany(\App\Models\Transaksi::class);
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedAtAttribute()
    {
        // Pastikan created_at adalah Carbon instance dan tidak null
        if ($this->created_at instanceof \Carbon\Carbon) {
            return $this->created_at->format('d/m/Y H:i');
        }
        
        // Fallback jika created_at null atau bukan Carbon
        return 'Tidak diketahui';
    }

    /**
     * Get user status badge
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->email_verified_at) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>';
        }
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Unverified</span>';
    }
}