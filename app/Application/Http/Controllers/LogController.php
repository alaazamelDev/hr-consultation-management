<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Resources\ActionResource;
use App\Application\Http\Resources\AffectedUserResource;
use App\Application\Http\Resources\LogResource;
use App\Domain\Services\LogService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LogController extends Controller
{
    private LogService $LogService;

    public function __construct(LogService $LogService)
    {
        $this->LogService = $LogService;
    }

    public function getAllAction(): AnonymousResourceCollection
    {
        $logs = $this->LogService->getAllAction();
        return ActionResource::collection($logs);
    }

    public function getAllAffectedUser(): AnonymousResourceCollection
    {
        $logs = $this->LogService->getAllAffectedUser();
        return AffectedUserResource::collection($logs);
    }

    public function getAllUser(): AnonymousResourceCollection
    {
        $logs = $this->LogService->getAllUser();
        return AffectedUserResource::collection($logs);
    }

    public function getLog(): AnonymousResourceCollection
    {
        $logs = $this->LogService->getLog();
        return LogResource::collection($logs);
    }

}
