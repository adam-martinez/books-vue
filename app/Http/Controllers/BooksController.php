<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function lists()
    {
        return Inertia::render('Books/Lists');
    }
    
    public function list(Request $request, string $slug)
    {
        return Inertia::render('Books/Lists', compact('slug'));
    }
}
