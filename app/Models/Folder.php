<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    // Define the fillable properties
    protected $fillable = ['user_id', 'name', 'parent_id', 'is_public'];

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship for subfolders (self-referential)
    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relationship for files in the folder
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
