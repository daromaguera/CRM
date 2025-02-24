<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactsAppointed extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts_appointeds';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'contacts_id',
        'appointment_id',
    ];

    /**
     * Get the user that owns the appointment.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * Get the user that owns the appointment.
     */
    public function appoint(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
