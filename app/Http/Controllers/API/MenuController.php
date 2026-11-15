<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu = Menu::first();

        if (!$menu) {
            return response()->json([
                'success' => false,
                'data' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => json_decode($menu->menuItems, true),
        ]);
    }
}
