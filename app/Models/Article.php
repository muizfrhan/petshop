<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'article_category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        $keyword = '%'.str_replace('%', '\%', $keyword).'%';

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('title', 'ilike', $keyword)->orWhere('excerpt', 'ilike', $keyword);
        });
    }

    public function thumbnailUrl(): string
    {
        if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        return asset('images/artikel-1.jpg');
    }

    /**
     * Sanitasi konten sebelum ditampilkan agar aman dari XSS.
     * Hanya tag markup yang memang dibutuhkan artikel yang diizinkan;
     * atribut event handler (onclick, onerror, dll) dibuang.
     */
    public function sanitizedContent(): string
    {
        $allowed = '<p><br><strong><em><b><i><ul><ol><li><h2><h3><h4><blockquote><a><code><pre><hr><br/>';
        $clean = strip_tags((string) $this->content, $allowed);

        // Buang seluruh atribut event handler + javascript: URL.
        $clean = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean);
        $clean = preg_replace('/(href|src)\s*=\s*(["\']?)\s*javascript:[^"\'>\s]*\2/i', '', $clean);

        return $clean;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}