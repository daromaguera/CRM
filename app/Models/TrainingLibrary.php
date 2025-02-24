<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingLibrary extends Model
{
    protected $table = 'training_library';

    protected $casts = [
        'tags' => 'array', // Automatically converts JSON string to array
    ];

    use HasFactory;

        /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [ 
        'title', 'tags', 'user_id', 'script', 'topic_id', 'description', 'tl_video_url', 'thumbnail_url', 'created_at', 'updated_at'
    ];

    // Relationship: Many users can interact with a single training
    public function userStatus()
    {
        return $this->hasMany(TrainingLibraryUser::class);
    }

    public function trainingLibraryUsers()
    {
        return $this->hasOne(TrainingLibraryUser::class, 'training_library_id');
    }

    public function topic()
    {
        return $this->belongsTo(TrainingLibraryTopic::class, 'topic_id');
    }
    


}
