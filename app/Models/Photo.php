<?php

namespace App\Models;

use App\Enums\PhotoUsage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $table = 'photos';

    protected $fillable = [
        'title',
        'description',
        'usage',
        'filename',
        'path',
        'mime_type',
        'size',
        'photoable_id',
        'photoable_type',
    ];

    protected $appends = ['url'];

    protected $casts = [
        'usage' => PhotoUsage::class,
    ];

    public function getUrlAttribute()
    {
        if (! $this->path) {
            return null;
        }

        return Storage::url($this->path);
    }

    public function photoable(): MorphTo
    {
        return $this->morphTo();
    }
}
