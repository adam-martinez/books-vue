<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BookListsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function lists()
    {
        return Cache::remember('lists', 60 * 5, function () {
            $response = Http::nyt()
                ->get('lists/names.json');

            return $response->collect('results')->map(function (array $listObj) {
                return [
                    'name' => $listObj['display_name'],
                    'slug' => $listObj['list_name_encoded'],
                ];
            });
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function list(string $slug)
    {
        return Cache::remember('list-'.$slug, 60 * 5, function () use ($slug) {
            $response = Http::nyt()
                ->get("lists/current/{$slug}.json");

            $list = $response->object()->results;

            return [
                'name' => $list->display_name,
                'slug' => $list->list_name_encoded,
                'books' => collect($list->books)->map(function ($bookObj) {
                    return [
                        'title' => str($bookObj->title)->title(),
                        'author' => $bookObj->author,
                        'description' => $bookObj->description,
                        'url' => $bookObj->amazon_product_url,
                        'book_image' => $bookObj->book_image,
                        'rank' => $bookObj->rank,
                    ];
                }),
            ];
        });
    }
}
