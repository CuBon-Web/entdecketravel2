<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\blog\Blog;
use App\models\product\Category;
use App\models\blog\BlogCategory;
use App\models\blog\BlogTypeCate;
use App\models\construction\Construction;
use App\models\product\Product;
use App\models\website\AlbumAffter;
use Session;

class BlogController extends Controller
{
    public function listCateBlog($slug)
    {
        $cate = BlogCategory::where('slug', $slug)->first(['name','avatar','slug','seo_title','meta_description']);
        if (!$cate) {
            abort(404);
        }

        $data['blog'] = Blog::with(['cate'])->where(['status'=>1,'category'=>$slug])
        ->orderBy('id','DESC')
        ->select(['id','title','image','description','created_at','slug'])
        ->paginate(12);
        $data['blognew'] = Blog::where(['status'=>1])->orderBy('id','DESC')->limit(5)->get();
        $data['gallery'] = AlbumAffter::where('status',1)->limit(6)->get();
        $data['title_page'] = languageName($cate->name);
        $data['seo_title'] = $cate->seo_title ?? '';
        $data['meta_description'] = $cate->meta_description ?? '';
        $data['bannerPage'] = $cate->avatar ?? '';
        return view('blog.listBlogCate',$data);
    }
    public function listTypeBlog($slug)
    {
        $cate = BlogTypeCate::where('slug', $slug)->first(['name']);
        if (!$cate) {
            abort(404);
        }

        $data['blog'] = Blog::where(['status'=>1,'type_cate'=>$slug])
        ->orderBy('id','DESC')
        ->select(['id','title','image','description','created_at','slug'])
        ->paginate(9);
        $data['title_page'] = languageName($cate->name);
        return view('blog.listBlogCate',$data);
    }
    public function detailBlog($slug)
    {
        $data['blog_detail'] = Blog::with('cate')->where(['slug' => $slug])->first();
        if (!$data['blog_detail']) {
            abort(404);
        }

        $data['blognew'] = Blog::where(['status' => 1])->orderBy('id','DESC')->limit(5)->get();
        $data['gallery'] = AlbumAffter::where('status',1)->limit(6)->get();
        $data['bloglq'] = Blog::where(['status' => 1,'category'=>$data['blog_detail']->category])->limit(4)->get();
        return view('blog.detail',$data);
    }
    public function search_blog(Request $request)
    {
        $keyword = $request->keyword;
        $code = Session::get('locale');
        $arr = [];
        $arrb = [];
        $arrOpt = [];
        //search option
        $productOpt =  Blog::where('status',1)
        ->get(['title','image','description','created_at','slug'])
        ->toArray();
        foreach($productOpt as $key => $item){
            $fielName = json_decode($item['title']);
            foreach($fielName as $i){
                if(strpos(strtolower(stripVN($i->content)), strtolower(stripVN($keyword))) !== false && $i->lang_code == $code){
                    array_push($arr,$productOpt[$key]);
                }
            }
        }
        $data['keyword'] = $request->keyword;
        $data['countproduct'] = count($arr);
        $data['resultPro'] = $arr;
        return view('search_result',$data);
    }
}
