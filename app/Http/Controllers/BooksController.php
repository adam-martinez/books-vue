<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BooksController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function lists()
    {
        $response = Http::nyt()
            ->get('lists/overview.json');

        $lists_array = $response->collect('results')->get('lists');
        
        $overview = collect($lists_array)->map(function ($listObj) {
                return [
                    'name' => $listObj['display_name'],
                    'slug' => $listObj['list_name_encoded'],
                    'books' => collect($listObj['books'])->map(fn ($book) => [
                        'title' => str($book['title'])->title,
                        'author' => $book['author'],
                        'description' => $book['description'],
                        'book_image' => $book['book_image'],
                        'url' => $book['amazon_product_url'],
                    ]),
                ];
            });

        return Inertia::render('Books/Lists', compact('overview'));
    }
    
    public function list(Request $request, string $slug)
    {
        return Inertia::render('Books/Lists', compact('slug'));
    }
}
