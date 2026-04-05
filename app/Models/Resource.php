<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['name', 'image_url', 'product_url', 'linkable_type', 'linkable_id'];

    public function getImageUrlAttribute(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $value = trim($value);

        if (preg_match('/^(https?:)?\/\//i', $value)) {
            $normalizedPath = $this->extractResourceUploadPathFromUrl($value);
            if ($normalizedPath !== null) {
                return asset($normalizedPath);
            }

            return $value;
        }

        return asset(ltrim($value, '/'));
    }

    private function extractResourceUploadPathFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            return null;
        }

        $trimmedPath = ltrim($path, '/');

        if (preg_match('#(?:^|/)(images/uploads/resources/[^?#]+)$#i', $trimmedPath, $matches) !== 1) {
            return null;
        }

        return $matches[1];
    }

    public function getRawImagePath(): ?string
    {
        return $this->getRawOriginal('image_url');
    }

    public function linkable()
    {
        return $this->morphTo();
    }
}
