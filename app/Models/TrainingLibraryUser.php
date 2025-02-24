<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingLibraryUser extends Model
{

    protected $table = 'training_library_user';

    protected $fillable = ['user_id', 'training_library_id', 'is_favorite', 'is_completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingLibrary()
    {
        return $this->belongsTo(TrainingLibrary::class);
    }
}
