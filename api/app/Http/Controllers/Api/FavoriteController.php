<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FavoriteController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return EventResource::collection(
            $request->user()->favorites()->latest('event_user.created_at')->paginate(
                $request->integer('per_page', 15)
            )->appends($request->query())
        );
    }

    public function store(Request $request, Event $event): JsonResponse
    {
        $request->user()->favorites()->syncWithoutDetaching($event);

        return response()->json(['message' => 'Event added to favorites.'], 201);
    }

    public function destroy(Request $request, Event $event): JsonResponse
    {
        $request->user()->favorites()->detach($event);

        return response()->json(['message' => 'Event removed from favorites.']);
    }
}
