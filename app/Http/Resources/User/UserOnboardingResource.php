<?php

namespace App\Http\Resources\User;

use App\Helpers\FileUploadHelper;
use App\Http\Requests\Onboarding\StepFourRequest;
use App\Http\Requests\Onboarding\StepOneRequest;
use App\Http\Requests\Onboarding\StepTwoRequest;
use App\Http\Requests\Onboarding\StepThreeRequest;
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
        $request->user()->profile->ong_id = $validated['ong_id'];
        $request->user()->profile->save();

        # Avatar
        // if ($validated['avatar']) {
        //     $avatar_url = (new FileUploadHelper())->storeFile($validated['avatar'], 'avatars');
        //     $request->user()->profile()->update([
        //         'avatar' => $avatar_url
        //     ]);
        // }

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

        # User
        $user = Arr::only(
            $validated,
            array(
                'document_type',
                'document',
                'name',
                'phone',
                'birth_date',
                'genre_id'
            )
        );
        $request->user()->update($user);

        $request->user()->onboarding->update([
            'step_id' => 3
        ]);

        return true;
    }

    /**
     * @param  \App\Http\Requests\Onboarding\StepThreeRequest $request
     *
     * @return bool
     * @throws \Exception
     */
    public function updateStepThree(StepThreeRequest $request)
    {
        $validated = $request->validated();

        $address = Arr::only(
            $validated,
            array(
                'cep',
                'street',
                'neighborhood',
                'city',
                'state',
                'number',
                'complement'
            )
        );
        $request->user()->address->update($address);

        $request->user()->onboarding->update([
            'step_id' => 4
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



        $request->user()->onboarding->update([
            'step_id' => 4
        ]);

        return true;
    }
}
