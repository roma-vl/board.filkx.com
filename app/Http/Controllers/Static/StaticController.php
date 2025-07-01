<?php

namespace App\Http\Controllers\Static;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class StaticController extends Controller
{
    public function contact(): Response
    {
        return Inertia::render('Static/Contact');
    }

    public function faq(): Response
    {
        return Inertia::render('Static/FAQ');
    }
}
