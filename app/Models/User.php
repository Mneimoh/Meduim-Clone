<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'image',
        'bio',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('avatar')
        ->width(128)
        ->crop(128, 128);
        //->nonQueued();

    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function following(){
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id'); 
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function imageUrl() {
        /*if ($this->image) {
            return Storage::url($this->image);
        }

        return null; 
        */
        $media = $this->getFirstMedia('avatar');
        if (!$media) {
            return null; // or a default image path
        }
        if ($media->hasGeneratedConversion('avatar')) {
            return $media->getUrl('avatar');
        }
       //return $this->getFirstMedia('avatar')?->getUrl(); or we can still use the below
       //return $this->getFirstMediaUrl('avatar');
       return $media->getUrl();
    }

    public function isFollowedBy(?User $user) {
        if (!$user) {
            return false;
        }
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function hasClapped(Post $post) {
        return $post->claps()->where('user_id', $this->id)->exists();
    }
}
