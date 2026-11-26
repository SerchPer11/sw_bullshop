<?php

namespace App\Http\Controllers\Bussiness;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bussiness\WiteListEntry;

class WaitListController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'item_id' => 'required|exists:products,id',
            'item_type' => 'required|string',
        ]);

        WiteListEntry::create([
            'email' => $request->email,
            'item_id' => $request->item_id,
            'item_type' => $request->item_type,
        ]);

        return back()->with('success', '¡Listo! Te avisaremos en cuanto los envíos estén disponibles.');
    }
}
