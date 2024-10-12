<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCollection;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BooksController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function lists(Request $request)
    {
        $params = collect([
            'api-key' => config('services.nyt.key'),
        ])
        ->filter();

        $response = Http::baseUrl(config('services.nyt.url'))
            ->withQueryParameters($params->toArray())
            ->get('lists/names.json');
        
        $lists = $response->collect('results')->pluck('display_name', 'list_name_encoded');

        return Inertia::render('Books/Lists', compact('lists'));
    }
    
    public function list(Request $request, string $name)
    {
        $params = collect([
            'api-key' => config('services.nyt.key'),
            'list' => $name,
        ])
        ->filter();

        $response = Http::baseUrl(config('services.nyt.url'))
            ->withQueryParameters($params->toArray())
            ->get('lists.json');
        
        $books = $response->collect('results')->pipeInto(BookCollection::class);

        return Inertia::render('Books/List', compact('books'));
    }
}
