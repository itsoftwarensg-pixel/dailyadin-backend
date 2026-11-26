<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_removes_script_svg_event_handlers_and_javascript_urls(): void
    {
        $html = (new HtmlSanitizer())->sanitize('<p onclick="alert(1)">News</p><script>alert(1)</script><svg onload="alert(1)"></svg><a href="javascript:alert(1)">bad</a><img src="javascript:alert(1)">');

        $this->assertStringContainsString('<p>News</p>', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('<svg', $html);
        $this->assertStringNotContainsString('onclick=', $html);
        $this->assertStringNotContainsString('javascript:', $html);
    }

    public function test_it_keeps_safe_editorial_links_and_hardens_new_tabs(): void
    {
        $html = (new HtmlSanitizer())->sanitize('<a href="https://dailyadin.com" target="_blank">Read</a>');

        $this->assertStringContainsString('href="https://dailyadin.com"', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
    }
}
