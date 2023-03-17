<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserVerificationCode extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'users_verification_code';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'code',
        'code',
        'type',
        'log',
        'expires_at',
        'used',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'log' => 'array',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($verificationCode) {
            if ($verificationCode->expires_at === null) {
                $verificationCode->expires_at = now()->addMinutes(30);
            }

            if (Hash::needsRehash($verificationCode->code)) {
                $verificationCode->code = Hash::make($verificationCode->code);
            }
        });

        static::created(function ($verificationCode) {
            $maxCodes = 100;
            $oldVerificationCodeIds = self::for($verificationCode->type)
                ->orderByDesc('expires_at')
                ->orderByDesc('id')
                ->skip($maxCodes)
                ->take(PHP_INT_MAX)
                ->pluck('id');

            self::whereIn('id', $oldVerificationCodeIds)->delete();
        });
    }

    /**
     * Scope a query to only include verification codes for the provided type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFor($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include verification codes that have not expired.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', '>=', now());
    }

    /**
     * Scope a query to only include verification codes that have not used.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotUsed($query)
    {
        return $query->where('used', 0);
    }

    /**
     * Get user of security
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
