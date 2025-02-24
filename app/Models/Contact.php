<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'email',
        'years_in_home',
        'possible_equity',
        'rough_credit_score',
        'zillow_estimate'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    // protected $casts = [
    //     'date_start' => 'datetime',
    //     'date_end' => 'datetime',
    //     'allDay' => 'boolean',
    // ];

    /**
     * Get the user that owns the appointment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
