<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LineController extends Controller
{
    public function handle(Request $request)
    {
        \Log::info('called');
        \Log::info(print_r($request->input(), true));
        // TODO: process word input
        return ['success' => true];
    }
}
