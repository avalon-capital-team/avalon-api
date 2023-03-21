<?php

namespace App\Observers\User;

use App\Models\Data\DataNotificationChannel;
use App\Models\Data\DataNotificationType;
use App\Models\Data\DataPrivacyType;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if ($user->type != 'admin') {
            # Profile
            $user->profile()->create();

            # Address
            $user->address()->create();

            # Security
            $user->security()->create();

            # Token Device
            $user->tokenDevice()->create();

            # Notification
            foreach (DataNotificationType::get() as $notificationType) {
                $user->userNotifications()->create([
                    'notification_type_id' => $notificationType->id
                ]);

                foreach (DataNotificationChannel::get() as $notificationChannel) {
                    $user->userNotifications()->create([
                        'notification_type_id' => $notificationType->id,
                        'notification_channel_id' => $notificationChannel->id
                    ]);
                }
            }

            # Onboarding
            $user->onboarding()->create(['step_id' => 1]);

            # Compliance
            $user->compliance()->create(['status_id' => 1]);

            # Privacy
            foreach (DataPrivacyType::get() as $privacyType) {
                $user->privacy()->create([
                    'privacy_type_id' => $privacyType->id,
                    'privacy_type_option_id' => $privacyType->privacyTypeOptionConnected()->where('is_default', 1)->first()->privacyTypeOption->id
                ]);
            }

            # Plan
            $user->financial()->create(['type' => 'pix']);
            $user->financial()->create(['type' => 'bank']);
            $user->financial()->create(['type' => 'crypto']);

            # Withdrawal
            $user->plan()->create();
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
