<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    protected $fillable = [
        'file_id',
        'uuid',
        'expires_at',
        'download_limit',
        'download_count',
        'password_hash',
        'is_revoked',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_revoked' => 'boolean',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }
}
