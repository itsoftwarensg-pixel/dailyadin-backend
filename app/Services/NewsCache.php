<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsCache
{
    private const HOME_REVISION = 'api:home:revision';

    public static function homeKey(string $includes): string
    {
        $revision = Cache::get(self::HOME_REVISION, 'initial');

        return 'api:home:' . $revision . ':' . md5($includes);
    }

    public static function refresh(?int $articleId = null): void
    {
        // Rotate the namespace so an in-flight rebuild cannot restore stale data.
        rescue(fn () => Cache::forever(self::HOME_REVISION, (string) Str::uuid()));

        $url = config('services.news_revalidation.url');
        $secret = config('services.news_revalidation.secret');

        if (!$url || !$secret) {
            return;
        }

        // A frontend outage must not turn a committed editorial change into a 500.
        rescue(fn () => Http::withToken($secret)
            ->connectTimeout(1)
            ->timeout(3)
            ->withoutRedirecting()
            ->post($url, ['articleId' => $articleId])
            ->throw());
    }
}
