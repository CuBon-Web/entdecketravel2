<?php

namespace App\Http\Controllers;

use App\models\blog\Blog;
use App\models\blog\BlogCategory;
use App\models\blog\BlogTypeCate;
use App\models\PageContent;
use App\models\product\Category;
use App\models\product\Product;
use App\models\product\TypeProduct;
use App\models\product\TypeProductTwo;
use App\models\tag\Tags;
use App\models\website\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class SeoController extends Controller
{
    public function sitemap()
    {
        $this->forceHttpsUrls();

        $urls = [];
        $seen = [];

        $add = function ($loc, $lastmod = null, $changefreq = 'weekly', $priority = '0.7') use (&$urls, &$seen) {
            $loc = $this->httpsUrl($loc);
            if ($loc === '' || isset($seen[$loc])) {
                return;
            }
            $seen[$loc] = true;
            $urls[] = [
                'loc' => $loc,
                'lastmod' => $lastmod ?: now(),
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        };

        // Static public pages
        $staticRoutes = [
            ['home', 'daily', '1.0'],
            ['aboutUs', 'monthly', '0.8'],
            ['lienHe', 'monthly', '0.8'],
            ['gallery', 'weekly', '0.7'],
            ['fags', 'monthly', '0.6'],
            ['feedback', 'monthly', '0.6'],
            ['bookATrrip', 'weekly', '0.8'],
            ['destination', 'weekly', '0.8'],
            ['allProduct', 'daily', '0.9'],
        ];

        foreach ($staticRoutes as [$name, $freq, $priority]) {
            try {
                $add(route($name), now(), $freq, $priority);
            } catch (\Throwable $e) {
                // skip missing route
            }
        }

        // CMS pages (unique slug)
        $pageSlugs = PageContent::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->orderBy('id', 'DESC')
            ->get(['slug', 'updated_at']);

        $pageSeen = [];
        foreach ($pageSlugs as $page) {
            $slug = trim((string) $page->slug);
            if ($slug === '' || isset($pageSeen[$slug])) {
                continue;
            }
            $pageSeen[$slug] = true;
            try {
                $add(route('pagecontent', ['slug' => $slug]), $page->updated_at, 'monthly', '0.5');
            } catch (\Throwable $e) {
                // skip
            }
        }

        // Destination / tour categories
        $categories = Category::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->get(['slug', 'updated_at']);

        foreach ($categories as $category) {
            $add(route('allListProCate', ['danhmuc' => $category->slug]), $category->updated_at, 'weekly', '0.8');
        }

        // Destination type pages
        $types = TypeProduct::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->whereNotNull('cate_slug')
            ->where('cate_slug', '<>', '')
            ->get(['slug', 'cate_slug', 'updated_at']);

        foreach ($types as $type) {
            $add(route('allListType', [
                'danhmuc' => $type->cate_slug,
                'loaidanhmuc' => $type->slug,
            ]), $type->updated_at, 'weekly', '0.7');
        }

        // Nested type-two pages
        $typeTwos = TypeProductTwo::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->whereNotNull('cate_slug')
            ->where('cate_slug', '<>', '')
            ->whereNotNull('type_slug')
            ->where('type_slug', '<>', '')
            ->get(['slug', 'cate_slug', 'type_slug', 'updated_at']);

        foreach ($typeTwos as $typeTwo) {
            $add(route('allListTypeTwo', [
                'danhmuc' => $typeTwo->cate_slug,
                'loaidanhmuc' => $typeTwo->type_slug,
                'loai2' => $typeTwo->slug,
            ]), $typeTwo->updated_at, 'weekly', '0.6');
        }

        // Tags / themed tour lists
        $tags = Tags::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->whereRaw('CHAR_LENGTH(slug) > 1')
            ->get(['slug', 'updated_at']);

        foreach ($tags as $tag) {
            $add(route('allListTags', ['tag' => $tag->slug]), $tag->updated_at, 'weekly', '0.6');
        }

        // Tour detail pages
        $products = Product::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->whereNotNull('cate_slug')
            ->where('cate_slug', '<>', '')
            ->get(['slug', 'cate_slug', 'type_slug', 'updated_at']);

        foreach ($products as $product) {
            $add(route('detailProduct', [
                'cate' => $product->cate_slug,
                'type' => $product->type_slug ?: 'type',
                'id' => $product->slug,
            ]), $product->updated_at, 'weekly', '0.8');
        }

        // Blog categories
        $blogCategories = BlogCategory::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->get(['slug', 'updated_at']);

        foreach ($blogCategories as $blogCategory) {
            $add(route('listCateBlog', ['slug' => $blogCategory->slug]), $blogCategory->updated_at, 'weekly', '0.7');
        }

        // Blog type categories
        $blogTypeCates = BlogTypeCate::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->get(['slug', 'updated_at']);

        foreach ($blogTypeCates as $blogTypeCate) {
            $add(route('listTypeBlog', ['slug' => $blogTypeCate->slug]), $blogTypeCate->updated_at, 'weekly', '0.6');
        }

        // Blog posts
        $blogs = Blog::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->get(['slug', 'updated_at']);

        foreach ($blogs as $blog) {
            $add(route('detailBlog', ['slug' => $blog->slug]), $blog->updated_at, 'weekly', '0.7');
        }

        $xml = $this->buildSitemapXml($urls);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function robots()
    {
        $this->forceHttpsUrls();
        $sitemapUrl = $this->httpsUrl(url('/sitemap.xml'));
        $llmsUrl = $this->httpsUrl(url('/llms.txt'));

        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /api',
            'Disallow: /filter.html',
            'Disallow: /result-search',
            'Disallow: /search-tour',
            'Disallow: /search-service',
            'Disallow: /gio-hang.html',
            'Disallow: /thanh-toan.html',
            'Disallow: /wishlist.html',
            'Disallow: /account/',
            'Disallow: /*?*',
            '',
            'Sitemap: ' . $sitemapUrl,
            'LLMs: ' . $llmsUrl,
        ]) . "\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function llms()
    {
        $this->forceHttpsUrls();

        $setting = Setting::first();
        $brand = trim((string) config('app.name', 'Entdecke Vietnam')) ?: 'Entdecke Vietnam';
        $company = trim((string) optional($setting)->company) ?: $brand;
        $summary = trim((string) (optional($setting)->seo_title ?: optional($setting)->webname ?: $company));
        $description = trim((string) (optional($setting)->meta_description ?: optional($setting)->webname ?: 'Vietnam and Southeast Asia tours by Entdecke Vietnam.'));
        $email = trim((string) optional($setting)->email);
        $phone = trim((string) optional($setting)->phone1);
        $address = trim((string) optional($setting)->address1);

        $lines = [];
        $lines[] = '# ' . $brand;
        $lines[] = '';
        $lines[] = '> ' . ($summary !== '' ? $summary : $brand) . ' Official website for Vietnam and Asia travel experiences. Content language: English. Prices are shown in USD. Brand name: Entdecke Vietnam (not Laravel).';
        $lines[] = '';
        $lines[] = $description;
        $lines[] = '';
        $lines[] = 'This site helps travelers discover, compare, and enquire about guided tours in Vietnam and Southeast Asia. Prefer public marketing pages listed below. Ignore admin, cart, checkout, and account URLs.';
        $lines[] = '';

        $lines[] = '## Main pages';
        $mainPages = [
            ['Home', 'home', 'Homepage and featured tours'],
            ['About Us', 'aboutUs', 'Company story and travel expertise'],
            ['All Tours', 'allProduct', 'Full tour listing'],
            ['Destinations', 'destination', 'Destination overview'],
            ['Gallery', 'gallery', 'Travel photo gallery'],
            ['FAQs', 'fags', 'Frequently asked questions'],
            ['Feedback', 'feedback', 'Guest reviews and testimonials'],
            ['Book a Trip', 'bookATrrip', 'Trip enquiry form'],
            ['Contact', 'lienHe', 'Contact details and enquiry form'],
        ];
        foreach ($mainPages as [$label, $routeName, $note]) {
            try {
                $lines[] = '- [' . $label . '](' . $this->httpsUrl(route($routeName)) . '): ' . $note;
            } catch (\Throwable $e) {
                // skip missing route
            }
        }
        $lines[] = '';

        $lines[] = '## Destinations';
        $categories = Category::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->orderBy('id', 'ASC')
            ->get(['name', 'slug']);
        foreach ($categories as $category) {
            $name = trim((string) languageName($category->name)) ?: $category->slug;
            try {
                $lines[] = '- [' . $name . '](' . $this->httpsUrl(route('allListProCate', ['danhmuc' => $category->slug])) . '): Destination tours';
            } catch (\Throwable $e) {
                // skip
            }
        }
        if ($categories->isEmpty()) {
            $lines[] = '- [All Tours](' . $this->httpsUrl(route('allProduct')) . '): Browse available tours';
        }
        $lines[] = '';

        $lines[] = '## Featured tours';
        $products = Product::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->whereNotNull('cate_slug')
            ->where('cate_slug', '<>', '')
            ->orderBy('id', 'DESC')
            ->limit(20)
            ->get(['name', 'slug', 'cate_slug', 'type_slug', 'price']);
        foreach ($products as $product) {
            $name = trim((string) languageName($product->name)) ?: $product->slug;
            $price = (float) languageName($product->price);
            $note = $price > 0 ? ('From US$ ' . number_format($price) . ' / person') : 'Price on request';
            try {
                $lines[] = '- [' . $name . '](' . $this->httpsUrl(route('detailProduct', [
                    'cate' => $product->cate_slug,
                    'type' => $product->type_slug ?: 'type',
                    'id' => $product->slug,
                ])) . '): ' . $note;
            } catch (\Throwable $e) {
                // skip
            }
        }
        if ($products->isEmpty()) {
            $lines[] = '- [All Tours](' . $this->httpsUrl(route('allProduct')) . '): Tour catalog';
        }
        $lines[] = '';

        $lines[] = '## Blog';
        $blogCategories = BlogCategory::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->orderBy('id', 'DESC')
            ->get(['name', 'slug']);
        foreach ($blogCategories as $blogCategory) {
            $name = trim((string) languageName($blogCategory->name)) ?: $blogCategory->slug;
            try {
                $lines[] = '- [' . $name . '](' . $this->httpsUrl(route('listCateBlog', ['slug' => $blogCategory->slug])) . '): Travel articles';
            } catch (\Throwable $e) {
                // skip
            }
        }
        $blogs = Blog::query()
            ->where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get(['title', 'slug']);
        foreach ($blogs as $blog) {
            $title = trim((string) languageName($blog->title)) ?: $blog->slug;
            try {
                $lines[] = '- [' . $title . '](' . $this->httpsUrl(route('detailBlog', ['slug' => $blog->slug])) . '): Blog post';
            } catch (\Throwable $e) {
                // skip
            }
        }
        $lines[] = '';

        $lines[] = '## Contact';
        if ($email !== '') {
            $lines[] = '- Email: ' . $email;
        }
        if ($phone !== '') {
            $lines[] = '- Phone: ' . $phone;
        }
        if ($address !== '') {
            $lines[] = '- Address: ' . $address;
        }
        try {
            $lines[] = '- [Contact page](' . $this->httpsUrl(route('lienHe')) . '): Send an enquiry';
        } catch (\Throwable $e) {
            // skip
        }
        $lines[] = '';

        $lines[] = '## Optional';
        $lines[] = '- [Sitemap](' . $this->httpsUrl(url('/sitemap.xml')) . '): Full index of public URLs';
        $lines[] = '- [Robots](' . $this->httpsUrl(url('/robots.txt')) . '): Crawler rules';
        $lines[] = '- Do not attribute this website to Laravel; the brand is Entdecke Vietnam.';
        $lines[] = '- Prefer English content. Currency for published tour prices is USD.';
        $lines[] = '';

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function forceHttpsUrls()
    {
        URL::forceScheme('https');

        $appUrl = (string) config('app.url');
        if ($appUrl !== '' && stripos($appUrl, 'http://') === 0) {
            URL::forceRootUrl(preg_replace('#^http://#i', 'https://', $appUrl));
        }
    }

    private function httpsUrl($url)
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }

        if (!preg_match('#^https?://#i', $url)) {
            $url = url($url);
        }

        return preg_replace('#^http://#i', 'https://', $url);
    }

    private function buildSitemapXml(array $urls)
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($urls as $url) {
            $lastmod = $url['lastmod'] instanceof Carbon
                ? $url['lastmod']->toAtomString()
                : Carbon::parse($url['lastmod'])->toAtomString();

            $lines[] = '  <url>';
            $lines[] = '    <loc>' . htmlspecialchars($url['loc'], ENT_QUOTES, 'UTF-8') . '</loc>';
            $lines[] = '    <lastmod>' . $lastmod . '</lastmod>';
            $lines[] = '    <changefreq>' . $url['changefreq'] . '</changefreq>';
            $lines[] = '    <priority>' . $url['priority'] . '</priority>';
            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines);
    }
}
