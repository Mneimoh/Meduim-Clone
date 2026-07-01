<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\Clap;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasSlug;
    protected $fillable = [
        //'image',
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
        ->width(400);
        //->nonQueued();

        $this->addMediaConversion('large')
        ->width(1200);
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection('default')
            ->singleFile();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function claps()
    {
        return $this->hasMany(Clap::class);
    }

    public function readTime($wordsPerMinute = 100){
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / $wordsPerMinute);

        return max(1, $minutes);
    }

    public function imageUrl($conversionName = '') {
        /*if ($this->image) {
            return Storage::url($this->image);
        }

        return null; 
        */

        $media = $this->getFirstMedia();
        if (!$media) {
            return null; // or a default image path
        }
        if ($media->hasGeneratedConversion($conversionName)) {
            return $media->getUrl($conversionName);
        }
       //return $this->getFirstMedia('avatar')?->getUrl(); or we can still use the below
       //return $this->getFirstMediaUrl('avatar');
       return $media->getUrl();
    }

    public function getCreatedAt() {
        return $this->created_at->format('M d, Y');
    }

    public function getPublishedAt() {
        return $this->published_at?->format('M d, Y');
    }

}
