<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'folder_id' ,  'url', 'created_at', 'updated_at'];

    public function files()
{
    return $this->hasMany(File::class); // Make sure the class name is correct
}

public function urls()
{
    return $this->hasMany(Url::class); // Make sure the class name is correct
}
public function subUrls() // Renaming to avoid confusion
{
    return $this->hasMany(Url::class, 'folder_id'); // This establishes the relationship
}
public function user()
    {
        return $this->belongsTo(User::class); // Adjust this according to your User model
    }
}
