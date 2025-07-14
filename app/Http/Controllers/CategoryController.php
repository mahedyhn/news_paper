<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Newspaper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('backend.category.index');
    }
    public function store(Request $r)
    {
        $category = new Category();
        $category->name = $r->name;
        $category->desc = $r->desc;
        $category->save();
        return back()->with('msg','Category Added Successfully!');
    }
    public function blogsByCategory($id)
    {
        $news = Newspaper::where('category_id' , $id)->latest()->take(3)->get(['id','title','description','image','created_at']);
        return view('frontend.category-news.index',['categories' => Category::all(),'news'=>$news]);
    }

}
