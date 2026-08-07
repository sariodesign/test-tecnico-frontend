<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $userId = auth('api')->id();

        $events = QueryBuilder::for(Event::class)
            ->allowedFilters(
                'title',
                'location',
                AllowedFilter::exact('category'),
                AllowedFilter::exact('is_published'),
            )
            ->allowedSorts('title', 'starts_at', 'created_at')
            ->defaultSort('starts_at')
            ->when($userId, fn ($query) => $query->withExists([
                'favoritedBy as is_favorite' => fn ($q) => $q->whereKey($userId),
            ]))
            ->paginate(request()->integer('per_page', 15))
            ->appends(request()->query());

        return EventResource::collection($events);
    }

    public function categories(): JsonResponse
    {
        $categories = Event::query()
            ->where('is_published', true)
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return response()->json(['data' => $categories]);
    }

    public function show(string $slug): EventResource
    {
        $userId = auth('api')->id();

        $event = Event::where('slug', $slug)
            ->when($userId, fn ($query) => $query->withExists([
                'favoritedBy as is_favorite' => fn ($q) => $q->whereKey($userId),
            ]))
            ->firstOrFail();

        return new EventResource($event);
    }
}
