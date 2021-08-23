<?php
namespace Ogilo\Gallery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Album extends Model
{
    /**
     * The pictures that belong to the Album
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pictures(): BelongsToMany
    {
        return $this->belongsToMany(Picture::class, 'album_picture', 'album_id', 'picture_id');
    }
}
