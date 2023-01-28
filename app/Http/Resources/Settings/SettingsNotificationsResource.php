<?php

namespace App\Http\Resources\Settings;

use App\Http\Requests\Settings\SettingsNotificationsRequest;
use App\Http\Resources\Data\DataNotificationChannelResource;
use App\Http\Resources\Data\DataNotificationTypeResource;
use App\Models\User;

class SettingsNotificationsResource
{
    /**
     * Data user notifications
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function data(User $user)
    {
        $array = [];
        foreach($user->userNotifications()->whereNull('notification_channel_id')->get() as $userNotification){
            $arrayChannels = [];
            foreach($user->userNotifications()->where('notification_type_id', $userNotification->notification_type_id)->whereNotNull('notification_channel_id')->get() as $userNotificationChannel){
                $arrayChannels[] = [
                    'code' => $userNotificationChannel->notificationChannel->code,
                    'name' => $userNotificationChannel->notificationChannel->name,
                    'status' => $userNotification->status,
                ];
            }

            $array[] = [
                'code' => $userNotification->notificationType->code,
                'name' => $userNotification->notificationType->name,
                'status' => $userNotification->status,
                'channels' => $arrayChannels
            ];
        }

        return $array;
    }

    /**
     * @param  \App\Http\Requests\Settings\SettingsNotificationsRequest $request
     * @return bool
     * @throws \Exception
     */
    public function update(SettingsNotificationsRequest $request)
    {
        $validated = $request->validated();

        $notificationType = (new DataNotificationTypeResource())->findByCode($validated['code']);
        if (!$notificationType) {
            throw new \Exception('Tipo de notificação selecionado inválido.', 400);
        }

        if($validated['code_channel']){
            $notificationChannel = (new DataNotificationChannelResource())->findByCode($validated['code_channel']);
            if (!$notificationChannel) {
                throw new \Exception('Tipo de canal de notificação selecionado inválido.', 400);
            }

            return $request->user()->userNotifications()->where('notification_type_id', $notificationType->id)->where('notification_channel_id', $notificationChannel->id)->update([
                'status' => $validated['status'],
            ]);
        }

        if($validated['status'] == false){
            foreach($request->user()->userNotifications()->where('notification_type_id', $notificationType->id)->whereNotNull('notification_channel_id')->get() as $userNotificationChannel){
                $userNotificationChannel->update([
                    'status' => false,
                ]);
            }
        }

        return $request->user()->userNotifications()->where('notification_type_id', $notificationType->id)->whereNull('notification_channel_id')->update([
            'status' => $validated['status'],
        ]);
    }
}
