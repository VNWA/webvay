<?php

namespace App\Models;

use App\Support\PublicMediaUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'cover_image',
    'author_avatar',
    'body',
    'published_at',
    'is_published',
    'author_id',
    'author_display_name',
    'author_title',
])]
class BlogPost extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @param  Builder<BlogPost>  $query
     * @return Builder<BlogPost>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function coverImageUrl(): ?string
    {
        return PublicMediaUrl::resolve($this->cover_image);
    }

    public function authorAvatarUrl(): ?string
    {
        return PublicMediaUrl::resolve($this->author_avatar)
            ?? $this->author?->avatarUrl();
    }

    public function displayAuthorName(): string
    {
        return $this->author_display_name
            ?? $this->author?->name
            ?? config('app.name', 'FinVay');
    }

    public function displayAuthorTitle(): ?string
    {
        return $this->author_title;
    }

    public function authorInitials(): string
    {
        return self::initialsFromName($this->displayAuthorName());
    }

    public function readingTimeMinutes(): int
    {
        $text = strip_tags((string) $this->body);
        $chars = mb_strlen(preg_replace('/\s+/u', '', $text) ?? '');

        return max(1, (int) ceil($chars / 900));
    }

    public static function initialsFromName(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($parts === []) {
            return 'FV';
        }

        if (count($parts) === 1) {
            return mb_strtoupper(mb_substr($parts[0], 0, 2));
        }

        return mb_strtoupper(mb_substr($parts[0], 0, 1).mb_substr($parts[array_key_last($parts)], 0, 1));
    }

    public static function uniqueSlugFromTitle(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title, '-', 'vi') ?: 'bai-viet';
        $slug = $base;
        $n = 1;
        while (static::query()
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }
}
