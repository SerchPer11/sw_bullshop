<?php

namespace App\Services;

use App\Models\Bussines\Lead;
use App\Models\Survey\SurveyResponse;
use Illuminate\Support\Facades\DB;

class SurveyAnswerService
{
    public function processSubmission(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear o actualizar el Lead
            $lead = Lead::firstOrCreate(
                ['email' => $data['responses'][8]],
                ['name' => $data['responses'][6] ?? null],
                ['last_name' => $data['responses'][7] ?? null],
                ['source' => $data['source'] ?? 'browser'],
                ['phone' => $data['responses'][6]['phone'] ?? null,]
            );

            $response = SurveyResponse::updateOrCreate(
                [
                    'survey_id' => $data['survey_id'],
                    'lead_id' => $lead->id,
                ],
                [
                    'responses' => $data['responses'],
                ]
            );

            return $response;
        });
    }
}
