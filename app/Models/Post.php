<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'color', 'content', 'tags', 'published', 'thumbnail', 'category_id'];

    protected $casts = [
        'tags' => 'array',
    ];
    protected $table = 'post';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function authors()
    {
        return $this->belongsToMany(User::class, 'post_user')->withTimestamps();
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
