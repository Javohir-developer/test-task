<?php

namespace Modules\Api\app\Http\Requests\V1\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class RefundProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'batch_id' => ['required', 'exists:batches,id'],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }
}
