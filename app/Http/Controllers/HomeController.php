<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;

class HomeController extends Controller
{
    public function homePage()
    {
        $categories = Category::orderBy('name')->get();

        $items = Item::with('sizes')
            ->orderBy('name')
            ->get();

        return view('home', compact(
            'categories',
            'items'
        ));
    }
}
