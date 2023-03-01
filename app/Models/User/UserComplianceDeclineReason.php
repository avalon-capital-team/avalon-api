<?php

namespace App\Models\User;

use App\Models\Data\DataComplianceDeclineReason;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserComplianceDeclineReason extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'users_compliance_decline_reasons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'verification_type',
        'user_compliance_id',
        'decline_reason_id',
    ];

    /**
     * Get user compliance
     *
     * @return App\Models\User\UserCompliance
     */
    public function userCompliance()
    {
        return $this->belongsTo(UserCompliance::class, 'user_compliance_id');
    }

    /**
     * Get decline reason message
     *
     * @return App\Models\Data\DataComplianceDeclineReason
     */
    public function declineReasonMessage()
    {
        return $this->belongsTo(DataComplianceDeclineReason::class, 'decline_reason_id');
    }
}
