<?php

namespace App\Application\Http\Controllers;

use App\Domain\Services\DashboardService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardController extends Controller
{
    private DashboardService $DashboardService;

    public function __construct(DashboardService $DashboardService)
    {
        $this->DashboardService = $DashboardService;
    }

    public function dashboard(): array
    {
        return $this->DashboardService->dashboard();
    }
}
