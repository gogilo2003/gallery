<?php
namespace Ogilo\Gallery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Picture extends Model
{
    protected $table = 'pictures';
    protected $appends = ['picture'];

    /**
     * The albums that belong to the Picture
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class, 'album_picture', 'picture_id', 'album_id');
    }

    public function getPictureAttribute()
    {
        // return new PictureUrl(asset('images/pictures'),$this->name);
        return new class($this->name){
            function __construct($name)
            {
                $this->filename = $name;
                $this->url = asset(config('gallery.dir','images/pictures/').$name);
                $this->thumbnail = asset(config('gallery.dir','images/pictures/').'thumbnails/'.$name);
                $this->original = asset(config('gallery.dir','images/pictures/').'original/'.$name);
            }
        };
    }

}
