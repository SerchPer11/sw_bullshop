<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePreRegistrationRequest;
use App\Models\Survey\Survey;
use App\Services\SurveyAnswerService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class PreRegisterController extends Controller
{
    protected string $source;

    protected SurveyAnswerService $service;

    public function __construct(SurveyAnswerService $service)
    {
        $this->source = 'Survey/PreRegistration/Pages/';
        $this->service = $service;
    }

    public function create()
    {
        // Cacheamos la encuesta activa para optimizar el rendimiento
        // se cachea por 24 para reducir las consultas a bd diarias
        $survey = Cache::remember('active_surbey', 86000 , function () {
            // Traemos la encuesta activa con sus preguntas ordenadas
            return Survey::with(['questions' => function ($query) {
                $query->orderBy('order');
            }])->where('is_active', true)->firstOrFail();
        });

        $source = request()->query('source', 'browser');

        // Mandamos a Vue/Inertia
        return Inertia::render("{$this->source}Index", [
            'survey' => $survey,
            'source' => $source,
        ]);
    }

    public function store(StorePreRegistrationRequest $request)
    {
        try {

            $this->service->processSubmission($request->validated());

            return redirect()->back()
                ->with('success', '¡Ahora eres parte del club! Nos pondremos en contacto contigo pronto.');

        } catch (\Throwable $e) {
            Log::error('Error al procesar la preinscripción.', [
                'exception' => $e,
                'survey_id' => $request->input('survey_id'),
            ]);

            return redirect()->back()
                ->withErrors([
                    'form' => 'Ocurrió un error al procesar tu solicitud. Por favor, inténtalo de nuevo.',
                ]);
        }
    }
}
