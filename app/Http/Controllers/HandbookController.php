<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HandbookController extends Controller
{
    public function show(): View
    {
        return view('handbook.show');
    }
}
