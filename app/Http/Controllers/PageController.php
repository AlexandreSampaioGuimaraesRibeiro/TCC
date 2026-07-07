<?php

namespace App\Http\Controllers;

use App\Models\Category;

class PageController extends Controller
{
    public function landing()
    {
        return view('landing', [
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function privacy()
    {
        return view('legal.privacy');
    }

    public function terms()
    {
        return view('legal.terms');
    }
}
