<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $service
    )
    {
    }

    public function index(): JsonResponse
    {
        $response = $this->service->index();

        return response()->json($response, $response['code']);
    }
}
