<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CargoTallyPermissionService;
use Illuminate\Http\Request;

class CargoTallyPermissionController extends Controller
{
    public function __invoke(Request $request, CargoTallyPermissionService $service)
    {
        $data = $service->build($request->user());

        \Log::info('Cargo tally permission response', [
            'user_id' => $request->user()->id,
            'permissions' => $data,
        ]);

        return response()->json([
            'data' => $data,
        ]);
    }
}
