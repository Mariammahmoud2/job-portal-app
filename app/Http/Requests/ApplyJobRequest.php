<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resume_file' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'resume_file.required' => 'Please upload your CV/Resume.',
            'resume_file.mimes'    => 'The resume must be a PDF file.',
            'resume_file.max'      => 'The resume file size must not exceed 5MB.',
        ];
    }
}