<?php

namespace App\Http\Controllers\API;

use App\Enums\ScrollTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\BnScroll;

class BnScrollController extends Controller
{
    /**
     * Display a listing of Breaking News.
     */
    public function index()
    {
        // TODO:: Add manage position of breaking news

        // Step 1: Check if BREAKING (type = 2) exists
        $breakingExists = BnScroll::where('type', ScrollTypeEnum::BREAKING->value)
            ->exists();

        $typeToFetch = $breakingExists
            ? ScrollTypeEnum::BREAKING
            : ScrollTypeEnum::GENERAL_SCROLL;

        // Step 2: Fetch only ONE type
        $scrolls = BnScroll::where('type', $typeToFetch->value)
            ->latest('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $scrolls->map(fn ($item) => [
                'id'            => $item->id,
                'heading'       => $item->heading,
                'type'          => $item->type,
                'type_label'    => $item->type_label,
            ]),
        ]);
    }
}
