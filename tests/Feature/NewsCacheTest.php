<?php

namespace Tests\Feature;

use App\Services\NewsCache;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsCacheTest extends TestCase
{
    public function test_refresh_replaces_all_home_cache_keys_before_notifying_the_frontend(): void
    {
        $url = 'https://frontend.example/api/revalidate-news';
        config([
            'services.news_revalidation.url' => $url,
            'services.news_revalidation.secret' => 'test-secret',
        ]);
        $originalKeys = [NewsCache::homeKey('lead'), NewsCache::homeKey('recent')];
        Cache::put($originalKeys[0], 'old headline', 300);

        $keysAtNotification = null;
        Http::fake(function () use (&$keysAtNotification) {
            $keysAtNotification = [NewsCache::homeKey('lead'), NewsCache::homeKey('recent')];

            return Http::response(null, 204);
        });

        NewsCache::refresh(31743);

        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request) => $request->url() === $url
            && $request->hasHeader('Authorization', 'Bearer test-secret')
            && $request->data() === ['articleId' => 31743]);
        $this->assertSame([NewsCache::homeKey('lead'), NewsCache::homeKey('recent')], $keysAtNotification);
        $this->assertNotSame($originalKeys[0], $keysAtNotification[0]);
        $this->assertNotSame($originalKeys[1], $keysAtNotification[1]);
        $this->assertNull(Cache::get($keysAtNotification[0]));
    }
}
