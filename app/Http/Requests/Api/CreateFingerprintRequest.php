<?php

namespace App\Http\Requests\Api;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class CreateFingerprintRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_token' => 'required|size:'.Project::TOKEN_LENGTH,
            'visitor_hash' => 'required',
            // 'local_id' => '',
            'ip' => 'ip',
            // 'user_agent' => '',
            // 'language' => '',
            // 'platform' => '',
            // 'screen' => '',
            // 'color_depth' => '',
            // 'pixel_ratio' => '',
            // 'timezone' => '',
            // 'referrer' => '',
            // 'connection_type' => '',
            // 'memory' => '',
            // 'cores' => '',
            'webdriver' => 'boolean',
            'time_to_submit' => 'integer|min:0',
        ];
    }
}
