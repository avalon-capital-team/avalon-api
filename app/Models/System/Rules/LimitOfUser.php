<?php

namespace App\Models\System\Rules;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class LimitOfUser extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'deposit_fiat_user_not_validated',
        'withdrawal_fiat_user_not_validated',
        'deposit_fiat_user_validated',
        'withdrawal_fiat_user_validated',
    ];
}
