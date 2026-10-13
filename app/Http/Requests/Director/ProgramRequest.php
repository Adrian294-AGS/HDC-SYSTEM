<?php

namespace App\Http\Requests\Director;

use App\Models\College;
use App\Models\Program;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramRequest extends FormRequest
{
    /**
     * Error bag, so the "add program" form on a college page shows its own errors.
     *
     * @var string
     */
    protected $errorBag = 'program';

    public function authorize(): bool
    {
        return $this->user()->isDirector();
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['code' => trim((string) $this->input('code'))]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'college_id' => ['required', 'integer', Rule::exists(College::class, 'id')],
            'code' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9-]+$/', Rule::unique(Program::class)->ignore($this->route('program'))],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.unique' => 'Another program already uses this code.',
            'code.regex' => 'Use letters, numbers and dashes only (e.g. BSIT).',
        ];
    }
}
