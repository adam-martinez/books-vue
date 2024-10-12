<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class BookCollection extends ResourceCollection
{
    public $list_name;

    public function __construct(Collection $collection)
    {
        $this->list_name = $collection->pluck('list_name')->first();

        parent::__construct($collection->map(function ($book) {

            $details = $book['book_details'][0];

            return [
                'author' => $details['author'],
                'title' => str($details['title'])->title(),
                'description' => $details['description'],
                'rank' => $book['rank'],
                'url' => $book['amazon_product_url'],
            ];
        }));
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'list_name' => $this->list_name,
            'books' => $this->collection,
        ];
    }
}
