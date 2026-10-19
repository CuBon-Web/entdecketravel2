<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class BannerAds extends Model
{
    protected $table = "banner_ads";
    public function saveBanner($request)
    {
    	$id = $request->id;
        if($id != ""){
            $query = BannerAds::where([
                'id' => $id
             ])->first();
            if ($query) {
                $query->name = $request->name;
                $query->slug = to_slug(is_string($request->name) ? $request->name : '');
                $query->content = json_encode($request->content);
                $query->description = json_encode($request->description);
                $query->status = $request->status;
                if ($request->filled('status_show')) {
                    $query->status_show = $request->status_show;
                }
                $query->image = $request->image;
                $query->image_detail = $request->image_detail;
                $query->save();
            }else{
                $query = new BannerAds();
                $query->name = $request->name;
                $query->slug = to_slug(is_string($request->name) ? $request->name : '');
                $query->content = json_encode($request->content);
                $query->description = json_encode($request->description);
                $query->status = $request->status;
                $query->status_show = $request->status_show ?: 'banner_ads';
                $query->image = $request->image;
                $query->image_detail = $request->image_detail;
                $query->save();
            }
            
        }else{
                $query = new BannerAds();
                $query->name = $request->name;
                $query->slug = to_slug(is_string($request->name) ? $request->name : '');
                $query->content = json_encode($request->content);
                $query->description = json_encode($request->description);
                $query->status = $request->status;
                $query->status_show = $request->status_show ?: 'banner_ads';
                $query->image = $request->image;
                $query->image_detail = $request->image_detail;
                $query->save();
            
        }
        return $query;
    }
}
