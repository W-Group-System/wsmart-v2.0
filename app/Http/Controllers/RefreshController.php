<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;

class RefreshController extends Controller
{
    public function refreshInventory(Request $request)
    {
        $inventory = Inventory::findOrFail($request->inventory_id);
        
        return response()->json([
            'inventory' => $inventory
        ]);
    }
}
