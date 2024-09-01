<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'ticket_number',
    ];

    public function booking()
    {
        return $this->belongsTo(Bookings::class);
    }
}
