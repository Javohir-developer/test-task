<?php

namespace Modules\Api\Http\Controllers\V1\Purchase;

use Modules\Api\Http\Controllers\BaseApiController;
use Modules\Api\Http\Requests\V1\Purchase\PurchaseRequest;
use Modules\Api\Http\Requests\V1\Purchase\RefundProviderRequest;
use Modules\Api\Services\PurchaseService;

class PurchaseController extends BaseApiController
{
    public function __construct(private PurchaseService $purchaseService) {}

    public function purchase(PurchaseRequest $request)
    {
        $batch = $this->purchaseService->purchase(
            $request->input('provider_id'),
            $request->input('storage_id'),
            $request->input('products')
        );

        return $this->sendResponse([
            'message' => 'Products purchased and stored successfully.',
            'batch_id' => $batch->id
        ]);
    }

    public function refundProvider(RefundProviderRequest $request)
    {
        try {
            $this->purchaseService->refundProvider(
                $request->input('batch_id'),
                $request->input('products')
            );

            return $this->sendResponse([
                'message' => 'Products refunded to provider successfully.'
            ]);
        } catch (\Exception $e) {
            return $this->sendError([$e->getMessage()], 400);
        }
    }
}
