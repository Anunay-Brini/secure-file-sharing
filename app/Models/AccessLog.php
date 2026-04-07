<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'share_link_id',
        'ip_address',
        'user_agent',
        'status',
    ];

    public function shareLink()
    {
        return $this->belongsTo(ShareLink::class);
    }
}
