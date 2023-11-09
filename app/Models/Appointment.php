<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Models\Client;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['formatted_start_time', 'formatted_end_time'];

    protected $casts = [
        'status' => AppointmentStatus::class,
    ];

    public function formattedEndTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->end_time->format('Y-m-d h:i A')
        );
    }

    public function formattedStartTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start_time->format('Y-m-d h:i A')
        );
    }

    /**
     * Get the client that owns the Appointment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
