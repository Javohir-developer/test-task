<?php

namespace Modules\Api\app\Http\Requests\V1\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider_id' => ['required', 'exists:providers,id'],
            'storage_id' => ['required', 'exists:storages,id'],
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.qty' => ['required', 'integer', 'min:1'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
