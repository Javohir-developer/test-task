<?php

namespace Modules\Api\Http\Controllers\V1\Product;

use Modules\Api\Http\Controllers\BaseApiController;
use Modules\Api\Services\InventoryService;

class ProductController extends BaseApiController
{
    public function __construct(private InventoryService $inventoryService) {}

    public function availableProducts()
    {
        $products = $this->inventoryService->getAvailableProducts();

        return $this->sendResponse($products);
    }
}
