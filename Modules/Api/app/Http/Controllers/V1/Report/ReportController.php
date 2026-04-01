<?php

namespace Modules\Api\app\Http\Controllers\V1\Report;

use Modules\Api\Http\Controllers\BaseApiController;
use Modules\Api\app\Http\Requests\V1\Report\RemainingQuantitiesRequest;
use Modules\Api\app\Services\ReportService;

class ReportController extends BaseApiController
{
    public function __construct(private ReportService $reportService) {}

    public function remainingQuantities(RemainingQuantitiesRequest $request)
    {
        $quantities = $this->reportService->getRemainingQuantities($request->input('date'));

        return $this->sendResponse([
            'date' => $request->input('date'),
            'data' => $quantities
        ]);
    }

    public function batchProfit()
    {
        $profits = $this->reportService->getProfitPerBatch();

        return $this->sendResponse($profits);
    }
}
