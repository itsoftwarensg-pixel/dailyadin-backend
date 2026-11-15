<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\BnCategory;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu = Menu::first();
        return view('admin.menu-management.index', [
            'categories' => $this->getCategories(),
            'menu' => $menu
        ]);
    }

    /**
     * All categories with subcategories
     */
    public function getCategories()
    {
        $categories = BnCategory::orderBy('sorting', 'asc')
            ->with('subcategories')
            ->where('status', StatusEnum::ACTIVE->value)
            ->get();

        return $categories;
    }

    public function saveMenu(Request $request)
    {
        $request->validate([
            'menuItems' => 'required',
        ]);

        $menu = Menu::first(); // only one record
        if (!$menu) {
            $menu = new Menu();
        }

        $menu->menuItems = $request->menuItems; // send JSON string directly
        $menu->save();


        return redirect(route('menu.index'))->with('message', 'Menu updated successfully.');
    }
}
