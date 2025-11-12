<?php

namespace App\Api;

class StaticPageController extends Controller
{
    public function apiDocs()
    {
        return view('documentation', [
            'docName' => 'swagger.json',
        ]);
    }
}
