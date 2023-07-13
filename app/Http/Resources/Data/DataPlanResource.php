<?php

namespace App\Http\Resources\Data;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Data\DataPlan;

class DataPlanResource
{
  /**
   * @return \App\Models\Data\DataPlan
   */
  public function getAll()
  {
    return DataPlan::get();
  }

  /**
   * @return \App\Models\Data\DataPlan
   */
  public function updatePlan($id, array $data)
  {
    $plan = DataPlan::find($id);

    if (!$plan) {
      return response()->json(['message' => 'Plan not found'], 404);
    }

    if (isset($data['name'])) {
      $plan->name = $data['name'];
    }

    if (isset($data['rescue'])) {
      $plan->rescue = $data['rescue'];
    }

    if (isset($data['porcent'])) {
      $plan->porcent = $data['porcent'];
    }

    if (isset($data['type'])) {
      $plan->type = $data['type'];
    }

    $plan->save();

    return $plan;
  }
}
