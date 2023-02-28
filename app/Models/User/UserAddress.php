<?php

namespace App\Models\User;

use App\Models\Data\DataCountry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'users_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'cep',
        'street',
        'neighborhood',
        'city',
        'state',
        'number',
        'country_id',
        'complement',
    ];

    /**
     * Get user
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the country from address
     *
     * @return \App\Models\Data\DataCountry
     */
    public function country()
    {
        return $this->hasOne(DataCountry::class, 'id', 'country_id');
    }
}
