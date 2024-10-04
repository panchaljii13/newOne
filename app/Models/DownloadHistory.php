<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadHistory extends Model
{
    protected $fillable = ['user_id', 'folder_id', 'downloaded_at'];

    // Cast downloaded_at as a date
    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
