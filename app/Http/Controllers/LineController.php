<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LineController extends Controller
{
    public function handle()
    {
        \Log::info('called');
        // TODO: process word input
        return ['success' => true];
    }
}
