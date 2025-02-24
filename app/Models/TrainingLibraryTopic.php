<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingLibraryTopic extends Model
{
    use HasFactory;

    protected $table = 'training_library_topic';

    protected $fillable = ['topic'];

    public function trainingLibraries()
    {
        return $this->hasMany(TrainingLibrary::class, 'topic_id');
    }
}
