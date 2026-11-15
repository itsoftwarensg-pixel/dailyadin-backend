<?php

namespace App\Http\Resources;

use App\Helpers\BengaliDateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BnContentResource extends JsonResource
{
    protected $forceMinimal = false;
    protected $forceWriter = false;

    public function __construct($resource, $forceMinimal = false, $forceWriter = false)
    {
        parent::__construct($resource);
        $this->forceMinimal = $forceMinimal;
        $this->forceWriter = $forceWriter;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $includes = explode(',', $request->include ?? '');
        $actionMethod = $request->route()?->getActionMethod();

        $isMultiple = $this->forceMinimal
            || in_array($actionMethod, [
                'getContents',
                'getArchive',
                'contentSearch',
                'index'
            ]) || in_array('contents', $includes) || in_array('position_contents', $includes);

        if ($isMultiple) {
            // Only process description if we are in a multiple context
            $truncatedText = '';
            if (!empty($this->content_brief)) {
                $contentBrief = preg_replace('/\[\d+\]/', '', $this->content_brief);
                $truncatedText = Str::words(strip_tags($contentBrief), 100, '...');
            } elseif (!empty($this->content_details)) {
                $plainText = strip_tags($this->content_details);
                $plainText = preg_replace('/\[\d+\]/', '', $plainText);
                $plainText = str_replace(['&nbsp;', '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;'], '', $plainText);
                $plainText = preg_replace('/\s+/u', ' ', $plainText);
                $truncatedText = Str::words($plainText, 100, '...');
            }

            $data = [
                'id' => $this->id,
                'sub_heading' => $this->sub_heading,
                'heading' => $this->heading,
                'description' => $truncatedText,
                'created_at' => $this->created_at,
                'created_at_bangla' => BengaliDateHelper::formatEnglishToBnDate($this->created_at),
                'full_image_path' => $this->bg_image_path ? image_url(config('helper.uploadPath') . $this->bg_image_path) : null,
                'full_url' => rtrim(config('app.frontend_url'), '/') . '/' . ($this->category->alias ?? 'news') . '/' . $this->id,
            ];

            if ($actionMethod === 'contentSearch') {
                $data['categoryName'] = $this->category?->name;
            }

            if (in_array('writer', $includes) || $this->forceWriter) {
                $data['writer'] = $this->formatWriter();
            }

            return $data;
        }

        $data = [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'sub_heading' => $this->sub_heading,
            'heading' => $this->heading,
            'writer' => $this->when(in_array('writer', $includes) || $this->relationLoaded('writer'), function () {
                return $this->formatWriter();
            }),
            'meta_keywords' => $this->meta_keywords,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'content_brief' => $this->content_brief,
            'content_details' => $this->parseContentDetails($this->content_details),
            'initial' => $this->initial,
            // 'sm_image_path' => $this->sm_image_path,
            // 'bg_image_path' => $this->bg_image_path,
            // 'webp_image_path' => $this->webp_image_path,
            'bg_image_caption' => $this->bg_image_caption,
            // 'bg_image_alt_text' => $this->bg_image_alt_text,
            'all_tags' => $this->all_tags,
            'tag_ids' => $this->tag_ids,
            'sound_path' => $this->sound_path,
            'video_path' => $this->video_path,
            'is_timeline' => $this->is_timeline,
            'share_count' => $this->share_count,
            'related_content' => $this->related_content,
            'content_url' => $this->content_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_bangla' => BengaliDateHelper::formatBengaliDateTime($this->created_at),
            'updated_at_bangla' => BengaliDateHelper::formatBengaliDateTime($this->updated_at),
            'full_image_path' => $this->bg_image_path ? image_url(config('helper.uploadPath') . $this->bg_image_path) : null,
            'full_url' => rtrim(config('app.frontend_url'), '/') . '/' . ($this->category->alias ?? 'news') . '/' . $this->id,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'category' => $this->when(in_array('category', $includes), new BnCategoryResource($this->category)),
            'next_ids' => $this->when(in_array('next_ids', $includes), function () {
                return $this->getRelatedContents();
            }),
        ];

        return $data;
    }

    /**
     * Format writer object
     */
    private function formatWriter()
    {
        $writer = $this->writer;
        if ($writer) {
            $data = (new WriterResource($writer))->toArray(request());
            $data['custom_writer'] = $this->writers ?: null;
            return $data;
        }

        return [
            'id' => null,
            'name' => $this->writers ?: 'Staff Reporter',
            'alias' => null,
            'photo' => null,
            'full_url' => null,
            'custom_writer' => $this->writers ?: null,
        ];
    }

    /**
     * Parse content_details for [news_id] slugs and replace with HTML inner boxes
     */
    private function parseContentDetails($content)
    {
        if (empty($content)) return $content;

        return preg_replace_callback('/\[(\d+)\]/', function ($matches) {
            $id = $matches[1];
            $news = \App\Models\BnContent::with('category')->find($id);

            if (!$news) return $matches[0];

            $url = rtrim(config('app.frontend_url'), '/') . '/' . ($news->category->alias ?? 'news') . '/' . $news->id;
            $imagePath = $news->bg_image_path ? image_url(config('helper.uploadPath') . $news->bg_image_path) : null;
            $heading = $news->heading;

            return "
                <div class='max-w-[360px] bg-gray-100 mx-auto my-2'>
                  <a class='block group overflow-hidden' href='{$url}'>
                    <div class='flex gap-2 items-center'>
                      <div class='w-[120px] shrink-0'>
                        <div class='aspect-16/10'>
                          <img alt='{$heading}' loading='lazy' width='160' height='100' src='{$imagePath}' class='h-full w-full object-cover' style='margin:0'/>
                        </div>
                      </div>
                      <div class='flex-1'>
                        <h4 class='group-hover:text-blue-700 font-semibold leading-6 mb-0'>{$heading}</h4>
                      </div>
                    </div>
                  </a>
                </div>
            ";
        }, $content);
    }

    /**
     * Get related contents based on tags or category
     */
    private function getRelatedContents()
    {
        $tag = null;
        if (!empty($this->all_tags)) {
            $tagsArray = array_map('trim', explode(',', $this->all_tags));
            $tag = $tagsArray[0] ?? null;
        }

        $query = \App\Models\BnContent::with(['category', 'writer'])
            ->where('id', '!=', $this->id)
            ->where('show_content', 1)
            ->latest()
            ->limit(4);

        if ($tag) {
            $query->where('all_tags', 'LIKE', '%' . $tag . '%');
        } else {
            $query->where('category_id', $this->category_id);
        }

        return $query->get()->map(fn($item) => new BnContentResource($item, true));
    }
}
