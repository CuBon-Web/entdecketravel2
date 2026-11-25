<?php

namespace App\models\blog;

use Illuminate\Database\Eloquent\Model;
use App\models\language\Language;
use App\models\blog\Blog;
use App\models\blog\BlogTypeCate;
class BlogCategory extends Model
{
    protected $table = "blog_category";
    public function listBlog()
    {
        return $this->hasMany(Blog::class,'category','slug')->orderBy('id','DESC');
    }
    public function typeCate()
    {
        return $this->hasMany(BlogTypeCate::class,'category_slug','slug');
    }
    public function saveCate($request)
    {
        $id = $request->id;
        if($id != "" ){
            $query = BlogCategory::where([
                'id' => $id
             ])->first();
            if ($query) {
                // $file= str_replace('http://localhost:8080','',$query->avatar);
                // $filename = public_path().$file;
                // if(file_exists( public_path().$file ) ){
                //     \File::delete($filename);
                // }
                $oldSlug = $query->slug;
                $oldUrl = null;
                if (!empty($oldSlug)) {
                    try {
                        $oldUrl = route('listCateBlog', ['slug' => $oldSlug]);
                    } catch (\Throwable $e) {
                        $oldUrl = null;
                    }
                }
                $query->name = json_encode($request->name);
                $query->path = $request->path;
                $requestedSlug = trim((string) ($request->slug ?? ''));
                if ($requestedSlug !== '') {
                    $query->slug = to_slug($requestedSlug);
                } elseif (empty($query->slug)) {
                    $query->slug = to_slug($request->name[0]['content']);
                }
                $query->seo_title = $request->seo_title ?? null;
                $query->meta_description = $request->meta_description ?? null;
                $query->status = $request->status;
                $query->avatar = $request->avatar;
                $query->save();
                if ($oldUrl && !empty($query->slug) && $oldSlug !== $query->slug) {
                    try {
                        recordUrlRedirect($oldUrl, route('listCateBlog', ['slug' => $query->slug]));
                        Blog::where('category', $oldSlug)->update(['category' => $query->slug]);
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }else{
                $query = new BlogCategory();
                $query->name = json_encode($request->name);
                $query->path = $request->path;
                $query->status = $request->status;
                $query->slug = to_slug($request->name[0]['content']);
                $query->seo_title = $request->seo_title ?? null;
                $query->meta_description = $request->meta_description ?? null;
                $query->avatar = $request->avatar;
                $query->save();
            }
            
        }else{
                $query = new BlogCategory();
                $query->name = json_encode($request->name);
                $query->path = $request->path;
                $query->slug = to_slug($request->name[0]['content']);
                $query->seo_title = $request->seo_title ?? null;
                $query->meta_description = $request->meta_description ?? null;
                $query->status = $request->status;
                $query->avatar = $request->avatar;
                $query->save();
        }
        return $query;
    }
}
