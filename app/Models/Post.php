<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'category',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $this->cover_image)) {
            return $this->cover_image;
        }

        return asset(ltrim($this->cover_image, '/'));
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function resources()
    {
        return $this->morphMany(Resource::class, 'linkable');
    }

    public function contentBlocksWithAnchors(): array
    {
        $blocks = $this->contentBlocks();
        $anchorUsage = [];

        foreach ($blocks as $index => $block) {
            if ($block['type'] !== 'heading') {
                continue;
            }

            $base = Str::slug($block['text']) ?: 'section';
            $anchorUsage[$base] = ($anchorUsage[$base] ?? 0) + 1;
            $blocks[$index]['anchor'] = $anchorUsage[$base] === 1
                ? $base
                : $base.'-'.$anchorUsage[$base];
        }

        return $blocks;
    }

    public function contentBlocks(): array
    {
        $decoded = json_decode((string) $this->content, true);

        if (is_array($decoded) && isset($decoded['blocks']) && is_array($decoded['blocks'])) {
            return $this->normalizeBlocks($decoded['blocks']);
        }

        $text = trim((string) $this->content);
        if ($text === '') {
            return [];
        }

        return [
            [
                'type' => 'paragraph',
                'text' => $text,
            ],
        ];
    }

    private function normalizeBlocks(array $blocks): array
    {
        $normalized = [];

        foreach ($blocks as $block) {
            if (! is_array($block) || ! isset($block['type'])) {
                continue;
            }

            $type = (string) $block['type'];

            if ($type === 'paragraph') {
                $text = trim((string) ($block['text'] ?? ''));
                if ($text !== '') {
                    $normalized[] = ['type' => 'paragraph', 'text' => $text];
                }
                continue;
            }

            if ($type === 'heading') {
                $text = trim((string) ($block['text'] ?? ''));
                if ($text === '') {
                    continue;
                }

                $level = (int) ($block['level'] ?? 2);
                if (! in_array($level, [2, 3, 4], true)) {
                    $level = 2;
                }

                $normalized[] = [
                    'type' => 'heading',
                    'text' => $text,
                    'level' => $level,
                ];
                continue;
            }

            if ($type === 'tiktok') {
                $url = trim((string) ($block['url'] ?? ''));
                $videoId = $this->extractTikTokVideoId($url);
                if ($videoId === null) {
                    continue;
                }

                $normalized[] = [
                    'type' => 'tiktok',
                    'url' => $url,
                    'video_id' => $videoId,
                ];
            }
        }

        return $normalized;
    }

    private function extractTikTokVideoId(string $value): ?string
    {
        if (preg_match('#/video/(\d+)#', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^\d{8,}$/', $value)) {
            return $value;
        }

        return null;
    }
}
