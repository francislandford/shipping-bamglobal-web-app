<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Cargo;
use App\Models\Pier;
use App\Models\Port;
use App\Models\Ship;

class MasterDataController extends Controller
{
    public function index()
    {
        return response()->json([
            'ships' => Ship::query()
                ->select('id', 'name', 'is_active')
                ->orderBy('name')
                ->get(),

            'agencies' => Agency::query()
                ->select('id', 'name', 'is_active')
                ->orderBy('name')
                ->get(),

            'ports' => Port::query()
                ->select('id', 'name', 'is_active')
                ->orderBy('name')
                ->get(),

            'piers' => Pier::query()
                ->select('id', 'port_id', 'name', 'is_active')
                ->orderBy('name')
                ->get(),

            'cargos' => Cargo::query()
                ->select('id', 'name', 'code', 'type', 'uom', 'is_active')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
