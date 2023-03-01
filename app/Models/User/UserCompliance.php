<?php

namespace App\Models\User;

use App\Http\Resources\User\UserComplianceResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class UserCompliance extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'users_compliance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'user_id',
        'status_id',
        'message',
        'applicant_id',
        'form_id',
        'form_url',
        'verification_id',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'documents' => 'array',
        'last_callback' => 'array'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::updated(function ($model) {
            if ($model->wasChanged('status_id')) {
                if ($model->status_id == 2) {
                    (new UserComplianceResource())->notifyApproveDocuments($model);
                } elseif ($model->status_id == 3) {
                    (new UserComplianceResource())->notifyDeclineDocuments($model);
                }
            }
        });
    }

    /**
     * Get documents from json
     *
     * @return array
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Get user of compliance
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get status of compliance
     *
     * @return \App\Models\User\UserComplianceStatus
     */
    public function status()
    {
        return $this->hasOne(UserComplianceStatus::class, 'id', 'status_id');
    }

    /**
     * Get decline reasons
     *
     * @return \App\Models\User\UserComplianceDeclineReason
     */
    public function declineReasons()
    {
        return $this->hasMany(UserComplianceDeclineReason::class, 'user_compliance_id', 'id');
    }
}
