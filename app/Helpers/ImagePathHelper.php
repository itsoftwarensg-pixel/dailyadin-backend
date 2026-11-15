<?php

if (!function_exists('image_url')) {
    /**
     * Get image URL with CDN support for API requests
     *
     * @param string|null $path
     * @return string|null
     */
    function image_url($path)
    {
        if (!$path) {
            return null;
        }

        $cdnUrl = config('app.cdn_url');
        if ($cdnUrl) {
            return rtrim($cdnUrl, '/') . '/' . ltrim($path, '/');
        }

        return asset($path);
    }
}
