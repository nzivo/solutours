<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'tour_id',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tours::class);
    }

    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'booking_id'); // Ensure the foreign key is correct
    }
}
