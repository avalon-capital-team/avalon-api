<?php

namespace App\Http\Resources\Onboarding;

use App\Models\Onboarding\OnboardingStep;

class OnboardingResource
{
    /**
     * @return \App\Models\Onboarding\OnboardingStep
     */
    public function getAllSteps()
    {
        return OnboardingStep::get();
    }
}
