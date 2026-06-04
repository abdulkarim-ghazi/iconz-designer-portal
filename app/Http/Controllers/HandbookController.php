<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HandbookController extends Controller
{
    public function plan(): View
    {
        return view('plan.show');
    }

    public function handbook(): View
    {
        return view('handbook.show');
    }
}
