<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

/** Keeps the editorial rich-text subset while removing executable HTML. */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = ['a', 'b', 'blockquote', 'br', 'div', 'em', 'figcaption', 'figure', 'h2', 'h3', 'h4', 'hr', 'i', 'img', 'li', 'ol', 'p', 'span', 'strong', 'table', 'tbody', 'td', 'th', 'thead', 'tr', 'u', 'ul'];
    private const ALLOWED_ATTRIBUTES = ['alt', 'class', 'colspan', 'height', 'href', 'loading', 'rel', 'rowspan', 'src', 'target', 'title', 'width'];

    public function sanitize(?string $html): string
    {
        if ($html === null || $html === '') return $html ?? '';

        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<div id="daily-adin-content">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $root = $document->getElementById('daily-adin-content');
        if (!$root) return '';

        $this->sanitizeChildren($root);
        $output = '';
        foreach (iterator_to_array($root->childNodes) as $node) $output .= $document->saveHTML($node);
        return $output;
    }

    private function sanitizeChildren(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if (!$child instanceof DOMElement) continue;
            if (!in_array(strtolower($child->tagName), self::ALLOWED_TAGS, true)) {
                while ($child->firstChild) $node->insertBefore($child->firstChild, $child);
                $node->removeChild($child);
                continue;
            }

            foreach (iterator_to_array($child->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                $value = trim($attribute->value);
                if (!in_array($name, self::ALLOWED_ATTRIBUTES, true)
                    || str_starts_with($name, 'on')
                    || (($name === 'href' || $name === 'src') && !$this->isSafeUrl($value, $name === 'src'))) {
                    $child->removeAttribute($attribute->name);
                }
            }
            if (strtolower($child->tagName) === 'a' && $child->getAttribute('target') === '_blank') {
                $child->setAttribute('rel', 'noopener noreferrer');
            }
            $this->sanitizeChildren($child);
        }
    }

    private function isSafeUrl(string $url, bool $isImage): bool
    {
        if ($url === '' || str_starts_with($url, '/') || str_starts_with($url, '#')) return true;
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        return in_array($scheme, $isImage ? ['http', 'https'] : ['http', 'https', 'mailto', 'tel'], true);
    }
}
