<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreorderRequest;
use App\Services\PreorderRepositoryService;
use Illuminate\Support\Facades\Response;

class PreorderController extends Controller
{

    private PreorderRepositoryService $preorderRepositoryService;

    public function __construct(PreorderRepositoryService $preorderRepositoryService)
    {
        $this->preorderRepositoryService = $preorderRepositoryService;
    }

    public function __invoke(PreorderRequest $request)
    {
        try {
            $newPreorder = $this->preorderRepositoryService->createPreorder($request->validated());

            return Response::json($newPreorder, 201);
        } catch (\Exception $e) {
            return Response::make(['error' => $e->getMessage()], 400);
        }
    }
}
