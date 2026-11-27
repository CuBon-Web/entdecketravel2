<?php
if (!function_exists('handle')) {
    function handle($request)
    {
        $input = $request->all();
        if ($input) {
            array_walk_recursive($input, function (&$item) {
                $item = trim($item);
                $item = ($item == "") ? null : $item;
            });
            $request->merge($input);
        }
        return $request->all();
    }
}
if(!function_exists('uploadImage')){
    function uploadImage($img){
        $nameAvatar = rand().$img->getClientOriginalName();
        $img->move('uploads/customer/', $nameAvatar);
        return '/uploads/customer/'.$nameAvatar;
    }
}
if(!function_exists('stripVN')){
    function stripVN($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
    
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
}
if (!function_exists('getConfig')) {
    function getConfig($key)
    {
        $setting = new \App\models\Client\Setting();
        return $setting->getValue($key);
    }
}

if (!function_exists('getLanguage')) {
    function getLanguage($key)
    {
        $session =  session()->get('localelang');
        // dd($session);
        if($session != null){
            $code = session()->get('localelang');
        }else{
            session()->put('localelang', app()->getLocale());
        }
        $code = session()->get('localelang');
        $lang = new \App\models\frontend\LanguageStaticByLang();
        return $lang->getLanguageValue($key, $code);
    }
}
if(!function_exists('toArrayLanguage')){
    function toArrayLanguage($value){
        $session =  Session::get('localelang');
        if($session != null){
            $code = Session::get('localelang');
        }else{
            Session::put('localelang', app()->getLocale());
        }
        $code = Session::get('localelang');
        $arr = [];
        $obj = new stdClass();
        $obj->lang_code = $code;
        $obj->content = $value;
        $arr[] = $obj;
        return json_encode($arr);
    }
}
if(!function_exists('languageName')){
    function languageName($arrName){
        if (is_null($arrName) || $arrName === '') {
            return '';
        }

        if (is_numeric($arrName)) {
            return $arrName;
        }

        if (is_array($arrName) || is_object($arrName)) {
            $arr = $arrName;
        } else {
            $arr = json_decode($arrName);
        }

        if (!is_iterable($arr)) {
            return is_string($arrName) ? $arrName : '';
        }

        $code = Session::get('localelang');
        if ($code === null || $code === '') {
            $code = app()->getLocale() ?: 'vi';
            Session::put('localelang', $code);
        }

        $fallbackVi = null;
        $fallbackFirst = null;

        foreach ($arr as $item) {
            if (is_array($item)) {
                $itemLang = $item['lang_code'] ?? null;
                $itemContent = $item['content'] ?? null;
            } elseif (is_object($item)) {
                $itemLang = $item->lang_code ?? null;
                $itemContent = $item->content ?? null;
            } else {
                continue;
            }

            if ($itemLang !== null && $itemLang == $code) {
                return $itemContent !== null ? $itemContent : '';
            }

            if ($fallbackVi === null && $itemLang === 'vi' && $itemContent !== null) {
                $fallbackVi = $itemContent;
            }

            if ($fallbackFirst === null && $itemContent !== null) {
                $fallbackFirst = $itemContent;
            }
        }

        if ($fallbackVi !== null) {
            return $fallbackVi;
        }

        if ($fallbackFirst !== null) {
            return $fallbackFirst;
        }

        return is_string($arrName) ? $arrName : '';
    }
}
if(!function_exists('checkExistCart')){
    function checkExistCart($proid){
        $cart = Session::get('cart', []);
        foreach($cart as $item){
            if($item['idpro'] == $proid){
                return true;
            }else{
                return false;
            }
        }
    }
}
if (!function_exists('getMenu')) {
    function getMenu($position)
    {
        $data = [];
        $menu = new \App\models\Client\Menu();
        $menuGroup = new \App\models\Client\MenuGroup();
        $result = $menuGroup->getMenuPosition($position);
        foreach ($result as $i => $item) {
            $data[$i] = $menu->getTree(1, $item['id'], getConfig('config_logo'));
        }
        return $data;
    }
}

if (!function_exists('getActiveLanguages')) {
    function getActiveLanguages()
    {
        $language = new \App\models\frontend\Language();
        $data = $language->getLanguages();
        return $data;
    }
}

if (!function_exists('getCurrentLang')) {

    function getCurrentLang()
    {
        $locale = request()->cookie('locale');
        if (!isset($locale)) {
            $locale = getConfig('config_language');
        }
        return $locale;
    }
}

if (!function_exists('shorten_string')) {
    function shorten_string($string, $words = 50)
    {
        $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
        $string = str_replace("\n", " ", $string);
        $array = explode(" ", $string);
        if (count($array) <= $words) {
            $retval = $string;
        } else {
            array_splice($array, $words);
            $retval = implode(" ", $array) . " ...";
        }
        return $retval;
    }
}

if(!function_exists('to_slug')){
    function to_slug($str){
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}

if (!function_exists('resizeImage')) {
    function resizeImage($originalImagePath, $newImagePath, $width = 50, $height = 50)
    {
        $name = preg_split('/[\/]+/', $originalImagePath);
        if (!file_exists('cache/' . $name[0] . '/' . $name[1])) {
            mkdir('cache/' . $name[0] . '/' . $name[1], 0777, true);
        }
        if (file_exists($originalImagePath)) {
            if (!file_exists($newImagePath)) {
                $image = imagecreatefromstring(file_get_contents($originalImagePath));
                $thumb_width = $width;
                $thumb_height = $height;
                $width = imagesx($image);
                $height = imagesy($image);
                $original_aspect = $width / $height;
                $thumb_aspect = $thumb_width / $thumb_height;
                if ($original_aspect >= $thumb_aspect) {
                    $new_height = $thumb_height;
                    $new_width = $width / ($height / $thumb_height);
                } else {
                    $new_width = $thumb_width;
                    $new_height = $height / ($width / $thumb_width);
                }
                $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
                imagecopyresampled($thumb,
                    $image,
                    0 - ($new_width - $thumb_width) / 2,
                    0 - ($new_height - $thumb_height) / 2,
                    0, 0,
                    $new_width, $new_height,
                    $width, $height);
                imagejpeg($thumb, $newImagePath, 80);
            }
        }
    }
}

if (!function_exists('normalizeRedirectPath')) {
    function normalizeRedirectPath($urlOrPath)
    {
        $value = trim((string) $urlOrPath);
        if ($value === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $value)) {
            $parts = parse_url($value);
            $value = ($parts['path'] ?? '/');
            if (!empty($parts['query'])) {
                $value .= '?' . $parts['query'];
            }
        }

        $value = '/' . ltrim($value, '/');
        if ($value !== '/') {
            $value = rtrim($value, '/');
        }

        return $value === '' ? '/' : $value;
    }
}

if (!function_exists('recordUrlRedirect')) {
    /**
     * Store a permanent redirect when a public URL changes.
     */
    function recordUrlRedirect($fromUrl, $toUrl, $statusCode = 301)
    {
        $from = normalizeRedirectPath($fromUrl);
        $to = normalizeRedirectPath($toUrl);

        if ($from === '' || $to === '' || $from === $to || $from === '/') {
            return false;
        }

        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('url_redirects')) {
                return false;
            }

            // Collapse chains: anything that pointed to the old path now points to the new one.
            \App\models\website\UrlRedirect::where('to_path', $from)->update(['to_path' => $to]);

            $existing = \App\models\website\UrlRedirect::where('from_path', $from)->first();
            if ($existing) {
                $existing->to_path = $to;
                $existing->status_code = $statusCode;
                $existing->save();
            } else {
                \App\models\website\UrlRedirect::create([
                    'from_path' => $from,
                    'to_path' => $to,
                    'status_code' => $statusCode,
                    'hits' => 0,
                ]);
            }

            // Avoid redirect loops.
            \App\models\website\UrlRedirect::where('from_path', $to)->where('to_path', $from)->delete();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('buildFaqSchemaMainEntity')) {
    /**
     * Build FAQPage mainEntity list from settings footer_content.
     */
    function buildFaqSchemaMainEntity($footerContent)
    {
        $items = [];
        $groups = is_string($footerContent) ? (json_decode($footerContent) ?: []) : ($footerContent ?: []);

        foreach ($groups as $group) {
            foreach (($group->fag_detail ?? []) as $faq) {
                $question = trim(strip_tags((string) ($faq->name ?? '')));
                $answer = trim(strip_tags((string) ($faq->content ?? '')));
                if ($question === '' || $answer === '') {
                    continue;
                }
                $items[] = [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $answer,
                    ],
                ];
            }
        }

        return $items;
    }
}

if (!function_exists('seoTitle')) {
    /**
     * Prefer dedicated SEO title, fall back to display title.
     */
    function seoTitle($seoTitle, $fallback = '')
    {
        $seoTitle = trim(strip_tags((string) $seoTitle));
        if ($seoTitle !== '') {
            return $seoTitle;
        }

        return trim(strip_tags((string) $fallback));
    }
}

if (!function_exists('seoDescription')) {
    /**
     * Prefer dedicated meta description, fall back to short text.
     */
    function seoDescription($seoDescription, $fallback = '', $limit = 160)
    {
        $seoDescription = trim(strip_tags((string) $seoDescription));
        if ($seoDescription !== '') {
            return \Illuminate\Support\Str::limit($seoDescription, $limit, '');
        }

        $fallback = trim(strip_tags((string) $fallback));
        return \Illuminate\Support\Str::limit($fallback, $limit, '');
    }
}

if (!function_exists('formatTourPrice')) {
    /**
     * Format tour price as USD. Zero/empty => "Price on request".
     *
     * @param  mixed  $price
     * @param  bool   $withUnit  Append " / person" when price > 0
     * @return string
     */
    function formatTourPrice($price, $withUnit = true)
    {
        $amount = (float) $price;
        if ($amount <= 0) {
            return 'Price on request';
        }

        $formatted = 'US$ ' . number_format($amount);
        if ($withUnit) {
            $formatted .= ' / person';
        }

        return $formatted;
    }
}

if (!function_exists('tourCardPrice')) {
    /**
     * Pick display amount for tour cards (sale/discount first, then regular price).
     *
     * @param  mixed  $discount
     * @param  mixed  $price
     * @return float
     */
    function tourCardPrice($discount, $price)
    {
        $discountAmount = (float) $discount;
        $priceAmount = (float) $price;

        if ($discountAmount > 0) {
            return $discountAmount;
        }

        return $priceAmount;
    }
}
