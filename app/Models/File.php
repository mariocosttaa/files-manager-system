<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'original_name',
        'path',
        'mime_type',
        'size',
        'download_count',
        'expires_at',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            if (empty($file->unique_id)) {
                $file->unique_id = \Illuminate\Support\Str::uuid()->toString();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'unique_id';
    }

    protected $casts = [
        'expires_at' => 'datetime',
        'size' => 'integer',
        'download_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
