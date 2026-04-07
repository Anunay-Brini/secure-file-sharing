<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'stored_path',
        'mime_type',
        'iv',
        'size',
        'hash',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shareLinks()
    {
        return $this->hasMany(ShareLink::class);
    }
}
