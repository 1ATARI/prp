<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'name',
        'url',
        'description',
    ];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }



}
