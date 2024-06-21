<?php

namespace App\Http\Requests;

class DocumentImportRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimetypes:application/json'],
        ];
    }
}
