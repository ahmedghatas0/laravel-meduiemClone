<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail , HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'image',
        'bio',
        'email',
        'password',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
    $this->addMediaConversion('avatar')
        ->width(128)
        ->height(128)
        ->crop('crop-center', 128, 128)
        ->performOnCollections('avatar');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }


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
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function following(){
        return $this->belongsToMany(User::class, 'followers','follower_id' , 'user_id' );
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'followers',  'user_id','follower_id' );
    }

public function imageUrl()
{
    $media = $this->getFirstMedia('avatar');

    if ($media) {
        // Try to get the conversion URL first, fallback to original if conversion doesn't exist
        return $media->hasGeneratedConversion('avatar')
            ? $media->getUrl('avatar')
            : $media->getUrl();
    }

    return null;
}

    public function isFollowedBy(?User $user ){
        if(! $user)
            return false;
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

public function hasClapped(Post $post)
{
    return $post->claps()->where('user_id', $this->id)->exists();
}
}
