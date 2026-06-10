<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function akuns(): BelongsToMany
    {
        return $this->belongsToMany(Akuns::class, 'users_akses_akun', 'user_id', 'akun_id', 'id', 'id');
    }

    public function canAccessAkun($kodeAkun): bool
    {
        // Admin with full access can access all accounts
        if ($this->role->is_full_access) {
            return true;
        }

        // Check user-specific account access
        return $this->akuns()->where('kode_akun', $kodeAkun)->exists();
    }

    public function getAccessibleAkuns()
    {
        // Admin with full access gets all accounts
        if ($this->role->is_full_access) {
            return Akuns::where('aktif', true)->get(['kode_akun', 'nama_akun']);
        }

        // Regular user gets only their assigned accounts
        return $this->akuns()->get(['kode_akun', 'nama_akun']);
    }

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
}
