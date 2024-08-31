<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tours extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'description',
        'price',
        'slots'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
