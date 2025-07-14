<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Newspaper;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public static $path = 'news-images/';
    public function add()
    {
        $categories = Category::all();
        return view('backend.admin.add',['categories'=>$categories]);
    }
    public function store(Request $r)
    {
        $news = new Newspaper();
        $news->title = $r->title;
        $news->author = auth()->user()->name;
        $news->category_id = $r->category_id;
        $news->description = $r->description;
        $imgName = 'news-image'.time().'.'.$r->image->getClientOriginalExtension();
        $r->image->move(public_path(self::$path),$imgName);
        $news->image = self::$path.$imgName;
        $news->save();
        return back()->with('msg','News Added Successfully');
    }
    public function manage()
    {
        $categories = Category::all();
        $news = Newspaper::all();
        return view('backend.admin.manage',['news'=>$news],['categories'=>$categories]);
    }
    public function edit($id)
    {
        $news = Newspaper::find($id);
        return view('backend.admin.edit',['news'=>$news]);
    }
    public function update(Request $request)
    {
        $news = Newspaper::find($request->id);
        $news->title       = $request->title;
        $news->description = $request->description;
        if ($request->image) {
            if(file_exists($news->image)){
                unlink($news->image);
            }
            $imgName = 'blog-image'.time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path(self::$path),$imgName);
            $news->image = self::$path.$imgName;
        }
        $news->save();
        return redirect(route('manage'))->with('msg','News Updated  Successfully');

    }
    public function delete($nId)
    {
        $news = Newspaper::find($nId);
        if(file_exists($news->image)){
            unlink($news->image);
        }
        $news->delete();
        return redirect(route('manage'))->with('msg','News Deleted Successfully');

    }


}
