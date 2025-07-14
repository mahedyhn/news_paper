<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Newspaper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $news)
    {
        $news = Newspaper::latest()->take(3)->get(['title','description','image','created_at']);
        $categories =  Category::all();
        return view('frontend.home.index',['categories' => $categories],['news'=> $news]);
    }
}
