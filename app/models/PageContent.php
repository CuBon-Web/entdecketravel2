<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\language\Language;

class PageContent extends Model
{
    protected $table = "page_contents";
    public function rule()
    {
        
    }
    protected function encodeImage($image)
    {
        if (is_array($image)) {
            return json_encode(array_values(array_filter($image)));
        }
        return $image;
    }
    public function savePageContent($request)
    {
        $quiz_id = $request->quiz_id;
        $language = $request->language;
        $nextId = PageContent::max('id') + 1;
        if($quiz_id != "" && $language != ""){
            $query = PageContent::where([
                'quiz_id' => $quiz_id,
                'language'=> $language
             ])->first();
            if ($query) {
                $query->title = $request->title;
                $query->content = $request->content;
                $query->status = $request->status;
                $query->type = $request->type;
                $query->image = $this->encodeImage($request->image);
                $query->description = $request->description;
                $query->save();
            }else{
                $query = new PageContent();
                $query->quiz_id = $quiz_id;
                $query->language = $language;
                $query->title = $request->title;
                $query->content = $request->content;
                $query->status = $request->status;
                $query->type = $request->type;
                $query->image = $this->encodeImage($request->image);
                $query->slug = to_slug($request->title);
                $query->description = $request->description;
                $query->save();
            }
            
        }else{
            $listLanguage = Language::get()->toArray();
            foreach ($listLanguage as $item) {
                $query = new PageContent();
                $query->quiz_id = $nextId;
                $query->language = $item['code'];
                $query->title = $request->title;
                $query->content = $request->content;
                $query->status = $request->status;
                $query->type = $request->type;
                $query->image = $this->encodeImage($request->image);
                $query->slug = to_slug($request->title);
                $query->description = $request->description;
                $query->save();
            }
        }
        return $query;
        
    }
}
