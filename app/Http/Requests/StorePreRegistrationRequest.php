<?php

namespace App\Http\Requests;

use App\Models\Survey\Survey;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePreRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'survey_id' => 'required|exists:surveys,id',
            'source' => 'nullable|string:max:50',
            'responses' => 'required|array',
        ];

        if (! $this->input('survey_id')) {
            return $rules;
        }

        if ($this->input('survey_id')) {
            $survey = Survey::with('questions')->find($this->input('survey_id'));
            if ($survey) {
                foreach ($survey->questions as $question) {
                    $rules["responses.{$question->id}"] = $question->validation_rules;
                }
            }
        }
        /*
        // Cargamos la encuesta para construir las reglas dinámicas
        $survey = Survey::with('questions')->find($this->input('survey_id'));

        if ($survey) {
            foreach ($survey->questions as $question) {
                if ($question->type === 'title') {
                    continue;
                }

                $key = "responses.{$question->id}";

                if ($question->type === 'repeater') {
                    $rules[$key] = $question->is_required
                        ? 'required|array|min:1'
                        : 'nullable|array';

                    // Si existe un registro, cada campo de ese registro es obligatorio.
                    $rules["{$key}.*.*"] = 'required_with:'.$key.'|string';
                } elseif ($question->is_required) {
                    $rules[$key] = 'required';
                }
            }
        }*/

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'email.required' => 'Necesitamos tu correo para avisarte del lanzamiento.',
            'email.email' => 'El formato del correo no es válido.',
        ];

        if ($this->input('survey_id')) {
            $survey = Survey::with('questions')->find($this->input('survey_id'));

            if ($survey) {
                foreach ($survey->questions as $question) {
                    if ($question->type === 'title') {
                        continue;
                    }

                    $key = "responses.{$question->id}";

                    if ($question->type === 'repeater') {
                        if ($question->is_required) {
                            $messages["{$key}.required"] = 'Debes registrar al menos un bulldog.';
                            $messages["{$key}.min"] = 'Debes registrar al menos un bulldog.';
                        }

                        $messages["{$key}.*.*.required_with"] = 'Completa este campo o elimina el registro.';
                    } elseif ($question->is_required) {
                        $messages["{$key}.required"] = 'Esta pregunta es obligatoria.';
                    }
                }
            }
        }

        return $messages;
    }
}
