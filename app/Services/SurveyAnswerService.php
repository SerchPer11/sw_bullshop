<?php

namespace App\Services;

use App\Models\Bussiness\Lead;
use App\Models\Survey\SurveyResponse;
use Illuminate\Support\Facades\DB;
use App\Mail\WelcomeToBullShop;
use Illuminate\Support\Facades\Mail;

class SurveyAnswerService
{
    public function processSubmission(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Crear o actualizar el Lead
            $lead = Lead::firstOrCreate(
                ['email' => $data['responses'][8]],
                [
                    'last_name' => $data['responses'][7] ?? null,
                    'name' => $data['responses'][6] ?? null ,
                    'source' => session('lead_source', $data['source'] ?? 'browser'),
                    'phone' => $data['responses'][9] ?? null,
                ]
            );

            // 2. Enviar correo de bienvenida
            if ($lead->wasRecentlyCreated) {
                Mail::to($lead->email)->send(new WelcomeToBullShop($lead, $data['responses'][2] ?? null));
            }

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
