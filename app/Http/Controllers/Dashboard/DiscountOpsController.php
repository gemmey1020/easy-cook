<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DiscountOpsOverviewService;
use App\Services\Dashboard\DiscountOpsRelationshipQuery;
use Illuminate\View\View;

class DiscountOpsController extends Controller
{
    public function __construct(
        private readonly DiscountOpsOverviewService $overviewService,
        private readonly DiscountOpsRelationshipQuery $relationshipQuery
    ) {
    }

    public function index(): View
    {
        return view('dash.discount-ops.index', [
            'overview' => $this->overviewService->overview(),
        ]);
    }

    public function partners(): View
    {
        return view('dash.discount-ops.partners', [
            'partnerRows' => $this->relationshipQuery->partnerRows(),
        ]);
    }
}
