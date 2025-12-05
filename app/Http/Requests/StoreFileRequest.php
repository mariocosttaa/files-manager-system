<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
        $maxSize = config('files.max_file_size', 2048); // Default to 2MB
        $allowedMimes = config('files.allowed_mime_types', []);

        $rules = [
            'file' => [
                'required',
                'file',
                'max:' . $maxSize, // Configurable max size in KB
            ],
            'expires_at' => 'nullable|string',
        ];

        // Add MIME type validation if configured
        if (!empty($allowedMimes)) {
            $rules['file'][] = 'mimes:' . implode(',', config('files.allowed_extensions', []));
            $rules['file'][] = 'mimetypes:' . implode(',', $allowedMimes);
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // Log what we're receiving
        \Illuminate\Support\Facades\Log::info('StoreFileRequest prepareForValidation', [
            'has_file' => $this->hasFile('file'),
            'has_file_key' => $this->has('file'),
            'all_files' => array_keys($this->allFiles()),
            'request_method' => $this->method(),
            'content_type' => $this->header('Content-Type'),
        ]);

        // Check if file upload failed at HTTP level
        if ($this->has('file') && !$this->hasFile('file')) {
            \Illuminate\Support\Facades\Log::warning('File upload failed at HTTP level', [
                'post_max_size' => ini_get('post_max_size'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $maxSize = config('files.max_file_size', 2048);
        $maxSizeMB = round($maxSize / 1024, 1);
        $phpLimit = ini_get('upload_max_filesize');

        $allowedTypes = config('files.allowed_extensions', []);
        $allowedTypesList = !empty($allowedTypes) ? implode(', ', array_map('strtoupper', $allowedTypes)) : 'common file types';

        return [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The file failed to upload. This may be due to PHP upload limits (current limit: ' . $phpLimit . '). Please try a smaller file or contact your administrator.',
            'file.max' => 'The file may not be greater than ' . $maxSizeMB . 'MB.',
            'file.mimes' => 'The file must be one of the following types: ' . $allowedTypesList . '.',
            'file.mimetypes' => 'The file type is not allowed. Please upload a valid file.',
            'expires_at.after' => 'The expiration date must be in the future.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Log::warning('File upload validation failed', [
            'errors' => $validator->errors()->all(),
            'input' => $this->except(['file', '_token'])
        ]);

        parent::failedValidation($validator);
    }
}
