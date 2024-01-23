<?php

namespace App\Http\Controllers;

use App\Http\Requests\SwapRequest;
use App\Services\SwapService;
use Illuminate\Http\JsonResponse;

class SwapController extends Controller
{

    public function __construct(
        private readonly SwapService $service
    )
    {
    }

    public function swap(SwapRequest $request): JsonResponse
    {
        $response = $this->service->swap(params: $request->safe()->toArray());

        return response()->json($response, $response['code']);
    }
}
