<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected  $fillable= [
        'title',
        'content',
        'website_id',
    ];
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function sentNotifications()
    {
        return $this->hasMany(SentNotification::class);
    }


}
