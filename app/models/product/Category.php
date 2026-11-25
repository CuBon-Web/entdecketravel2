<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use File;
use App\models\language\Language;
use App\models\product\Product;
use App\models\product\TypeProduct;
use App\models\tag\TagCate;

class Category extends Model
{
    protected $table = "product_category";
    public function rule()
    {
        return [
            
        ];
    }
    public function typeCate()
    {
        return $this->hasMany(TypeProduct::class,'cate_id','id')->where('status',1);
    }
    public function product()
    {
        return $this->hasMany(Product::class,'category','id')->where('home_status',1)->orderBy('id','DESC');
    }
    public function tagCate()
    {
        return $this->hasMany(TagCate::class,'cate_product_id','id');
    }
    public function saveCate($request)
    {
        $id = $request->id;
        if($id != "" ){
            $query = Category::where([
                'id' => $id
             ])->first();
            if ($query) {
                $oldSlug = $query->slug;
                $oldUrl = null;
                if (!empty($oldSlug)) {
                    try {
                        $oldUrl = route('allListProCate', ['danhmuc' => $oldSlug]);
                    } catch (\Throwable $e) {
                        $oldUrl = null;
                    }
                }
                $query->name = json_encode($request->name);
                $requestedSlug = trim((string) ($request->slug ?? ''));
                if ($requestedSlug !== '') {
                    $query->slug = to_slug($requestedSlug);
                } elseif (empty($query->slug)) {
                    $query->slug = to_slug($request->name[0]['content']);
                }
                $query->seo_title = $request->seo_title ?? null;
                $query->meta_description = $request->meta_description ?? null;
                $query->content = $request->content;
                $query->status = $request->status;
                $query->avatar = $request->avatar;
                $query->imagehome = $request->imagehome;
                $query->save();

                if ($oldUrl && !empty($query->slug) && $oldSlug !== $query->slug) {
                    try {
                        $newUrl = route('allListProCate', ['danhmuc' => $query->slug]);
                        recordUrlRedirect($oldUrl, $newUrl);
                        // Product detail URLs also embed cate_slug.
                        $products = Product::where('cate_slug', $oldSlug)->get(['id','slug','cate_slug','type_slug']);
                        foreach ($products as $product) {
                            $from = route('detailProduct', [
                                'cate' => $oldSlug,
                                'type' => $product->type_slug ?: 'type',
                                'id' => $product->slug,
                            ]);
                            $to = route('detailProduct', [
                                'cate' => $query->slug,
                                'type' => $product->type_slug ?: 'type',
                                'id' => $product->slug,
                            ]);
                            recordUrlRedirect($from, $to);
                            $product->cate_slug = $query->slug;
                            $product->save();
                        }
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }else{
                $query = new Category();
                $query->quiz_id = 0;
                $query->language = 0;
                $query->name = json_encode($request->name);
                $query->slug = to_slug($request->name[0]['content']);
                $query->seo_title = $request->seo_title ?? null;
                $query->meta_description = $request->meta_description ?? null;
                $query->content = $request->content;
                $query->status = $request->status;
                $query->avatar = $request->avatar;
                $query->imagehome = $request->imagehome;
                $query->save();
            }
            
        }else{
            $query = new Category();
            $query->quiz_id = 0;
            $query->language = 0;
            $query->name = json_encode($request->name);
            $query->slug = to_slug($request->name[0]['content']);
            $query->seo_title = $request->seo_title ?? null;
            $query->meta_description = $request->meta_description ?? null;
            $query->content = $request->content;
            $query->status = $request->status;
            $query->avatar = $request->avatar;
            $query->imagehome = $request->imagehome;
            $query->save();
            
        }
        return $query;
    }
}
