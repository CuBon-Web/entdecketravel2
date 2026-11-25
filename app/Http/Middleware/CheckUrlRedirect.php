<?php

namespace App\Http\Middleware;

use App\models\website\UrlRedirect;
use Closure;
use Illuminate\Support\Facades\Schema;

class CheckUrlRedirect
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('get') || $request->isMethod('head')) {
            try {
                if (Schema::hasTable('url_redirects')) {
                    $path = $this->normalizePath($request->getPathInfo());
                    if ($path !== '' && $path !== '/') {
                        $redirect = UrlRedirect::where('from_path', $path)->first();
                        if ($redirect && !empty($redirect->to_path) && $redirect->to_path !== $path) {
                            UrlRedirect::where('id', $redirect->id)->increment('hits');
                            $target = $redirect->to_path;
                            if (!preg_match('#^https?://#i', $target)) {
                                $target = url($target);
                            }
                            return redirect($target, (int) ($redirect->status_code ?: 301));
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Never block the request if redirect lookup fails.
            }
        }

        return $next($request);
    }

    private function normalizePath($path)
    {
        $path = '/' . ltrim((string) $path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return $path === '' ? '/' : $path;
    }
}
