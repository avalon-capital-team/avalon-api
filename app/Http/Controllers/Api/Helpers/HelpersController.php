<?php

namespace App\Http\Controllers\Api\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Data\DataCountryResource;
use App\Http\Resources\Data\DataGenreResource;
use App\Http\Resources\Data\DataPlanResource;
use App\Http\Resources\Data\DataPrivacyTypeOptionResource;
use App\Http\Resources\Data\DataPrivacyTypeResource;

class HelpersController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPlans()
    {
        return response()->json([
            'status'  => true,
            'plans' => (new DataPlanResource())->getAll()->toArray()
        ]);
    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCountries()
    {
        return response()->json([
            'status'  => true,
            'countries' => (new DataCountryResource())->getAll()->toArray()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPrivacyOption()
    {
        return response()->json([
            'status'  => true,
            'options' => (new DataPrivacyTypeOptionResource())->getAll()->toArray()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPrivacyType()
    {
        return response()->json([
            'status'  => true,
            'types' => (new DataPrivacyTypeResource())->getAll()->toArray()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPrivacyTypeWithOption()
    {
        return response()->json([
            'status'  => true,
            'data' => (new DataPrivacyTypeResource())->getAllWithOptions()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllGenres()
    {
        return response()->json([
            'status'  => true,
            'genres' => (new DataGenreResource())->getAll()->toArray()
        ]);
    }
}
