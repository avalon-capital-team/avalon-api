<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Jobs\Credit\CreateCreditBalance;
use App\Models\Data\DataGenre;
use App\Models\User\UserAddress;
use App\Models\User\UserCompliance;
use App\Models\User\UserNotification;
use App\Models\User\UserOnboarding;
use App\Models\User\UserPrivacy;
use App\Models\User\UserProfile;
use App\Models\User\UserSecurity;
use App\Models\User\UserStatus;
use App\Models\User\UserTokenDevice;
use App\Models\User\UserPlan;
use App\Models\User\UserWithdrawalInfo;
use App\Models\Credit\CreditBalance;
use App\Models\Plan\Plan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use AuthenticationLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'document_type',
        'document',
        'phone',
        'sponsor_id',
        'birth_date',
        'status_id',
        'genre_id',
        'verification_code'
    ];

    /**
     * boot of mode
     */
    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            CreateCreditBalance::dispatch()->delay(Carbon::now()->addSeconds(rand(10, 20)));
        });
    }


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
     *s
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return array
     */
    public function routeNotificationForOneSignal()
    {
        return [
            'tags' => [
                'key' => 'userId',
                'relation' => '=',
                'value' => (string)$this->id
            ]
        ];
    }



    /**
     * Get the profile from user
     *
     * @return \App\Models\User\UserProfile
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * Get the address from user
     *
     * @return \App\Models\User\UserAddress
     */
    public function address()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id');
    }

    /**
     * Get the notifications from user
     *
     * @return \App\Models\User\UserNotification
     */
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class, 'user_id', 'id');
    }

    /**
     * Get the security from user
     *
     * @return \App\Models\User\UserSecurity
     */
    public function security()
    {
        return $this->hasOne(UserSecurity::class, 'user_id', 'id');
    }

    /**
     * Get the onboarding from user
     *
     * @return \App\Models\User\UserOnboarding
     */
    public function onboarding()
    {
        return $this->hasOne(UserOnboarding::class, 'user_id', 'id');
    }

    /**
     * Get the genre from user
     *
     * @return \App\Models\Data\DataGenre
     */
    public function genre()
    {
        return $this->hasOne(DataGenre::class, 'id', 'genre_id');
    }

    /**
     * Get the compliance from user
     *
     * @return \App\Models\User\UserCompliance
     */
    public function compliance()
    {
        return $this->hasOne(UserCompliance::class, 'user_id', 'id');
    }

    /**
     * Get the privacy from user
     *
     * @return \App\Models\User\UserPrivacy
     */
    public function privacy()
    {
        return $this->hasMany(UserPrivacy::class, 'user_id', 'id');
    }

    /**
     * Get status
     *
     * @return \App\Models\User\UserStatus
     */
    public function status()
    {
        return $this->belongsTo(UserStatus::class, 'status_id');
    }

    /**
     * Get token device
     *
     * @return \App\Models\User\UserTokenDevice
     */
    public function tokenDevice()
    {
        return $this->hasOne(UserTokenDevice::class, 'user_id', 'id');
    }

    /**
     * Get balance
     *
     * @return \App\Models\User\UserTokenDevice
     */
    public function creditBalance()
    {
        return $this->hasMany(CreditBalance::class, 'user_id', 'id');
    }

    /**
     * Get the financial data from user
     *
     * @return \App\Models\User\UserWithdrawalInfo
     */
    public function financial()
    {
        return $this->hasMany(UserWithdrawalInfo::class, 'user_id', 'id');
    }

    /**
     * Get the plan from user
     *
     * @return \App\Models\User\UserPlan
     */
    public function userPlan()
    {
        return $this->hasOne(UserPlan::class, 'user_id', 'id');
    }

    /**
     * Get the plans detains
     *
     * @return \App\Models\Data\DataPlan
     */
    public function plan()
    {
        return $this->hasMany(Plan::class, 'user_id', 'id');
    }

    /**
     * Get all data of user
     *
     * @return \App\Models\User\UserPlan
     */
    public function allData()
    {
      $users = User::get();
      foreach($users as $user){
        $user->profile;
        $user->address;
        $user->security;
        $user->onboarding;
        $user->compliance;
        $user->status;
        $user->financial;
        $user->creditBalance;
        $user->plan;
      }
        return $users;
    }
}
