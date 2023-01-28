<?php

namespace App\Http\Resources\User;

use App\Helpers\FileUploadHelper;
use App\Http\Requests\Onboarding\StepFourRequest;
use App\Http\Requests\Onboarding\StepOneRequest;
use App\Http\Requests\Onboarding\StepTwoRequest;
use App\Models\User;
use Illuminate\Support\Arr;

class UserOnboardingResource
{
    /**
     * @param  \App\Models\User $user
     *
     * @return int
     */
    public function getActualStep(User $user)
    {
        return $user->onboarding->step_id;
    }

    /**
     * @param  \App\Http\Requests\Onboarding\StepOneRequest $request
     * @return bool
     * @throws \Exception
     */
    public function updateStepOne(StepOneRequest $request)
    {

        $validated = $request->validated();
        # Profile
        $profile = Arr::only($validated, array('bio', 'is_private'));
        $request->user()->profile()->update($profile);

        # Avatar
        if ($validated['avatar']) {

            $avatar_url = (new FileUploadHelper())->storeFile($validated['avatar'], 'avatars');
            $request->user()->profile()->update([
                'avatar' => $avatar_url
            ]);
        }

        $request->user()->onboarding->update([
            'step_id' => 2
        ]);

        return true;
    }

    /**
     * @param  \App\Http\Requests\Onboarding\StepTwoRequest $request
     *
     * @return bool
     * @throws \Exception
     */
    public function updateStepTwo(StepTwoRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated['interest'] as $interestSelected) {
            $request->user()->interests()->create([
                'interest_id' => $interestSelected
            ]);
        }

        $request->user()->onboarding->update([
            'step_id' => 3
        ]);

        return true;
    }

    /**
     * @param  \App\Http\Requests\Onboarding\StepFourRequest $request
     *
     * @return bool
     * @throws \Exception
     */
    public function updateStepFour(StepFourRequest $request)
    {
        $validated = $request->validated();

        $request->user()->profile->is_private = $validated['is_private'];
        $request->user()->profile->save();

        $request->user()->onboarding->update([
            'step_id' => 4
        ]);

        return true;
    }
}
