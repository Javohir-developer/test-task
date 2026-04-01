<?php

namespace Modules\Api\app\Http\Requests\V1\Report;

use Illuminate\Foundation\Http\FormRequest;

class RemainingQuantitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
        ];
    }
}
