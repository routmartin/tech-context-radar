<?php

namespace App\Http\Controllers;

use App\Models\ReadSignal;
use App\Models\Signal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReadSignalController extends Controller
{
    public function store(Request $request, Signal $signal): RedirectResponse
    {
        ReadSignal::updateOrCreate(['user_id' => $request->user()->id, 'signal_id' => $signal->id], ['created_at' => now()]);

        return back()->with('toast', 'Signal marked as read.');
    }
}
