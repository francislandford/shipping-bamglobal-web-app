<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatementOfFactsPermissionService;
use Illuminate\Http\Request;

class StatementOfFactPermissionController extends Controller
{
    public function __invoke(Request $request, StatementOfFactsPermissionService $service)
    {
        return response()->json([
            'data' => $service->build($request->user()),
        ]);
    }
}
