<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

   protected $fillable = ['user_id', 'folder_id', 'file_name', 'file_path'];
   
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function files()
{
    return $this->hasMany(File::class);
}
}
