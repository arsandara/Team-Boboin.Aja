<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'room_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_name',
        'room_number',
        'description',
        'capacity',
        'price',
        'rating',
        'image_booking',
        'breakfast_included',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'capacity' => 'integer',
        'breakfast_included' => 'boolean',
    ];

    /**
     * Get all reservations for this room.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'room_id', 'room_id');
    }

    /**
     * Format the price to rupiah format.
     *
     * @return string
     */
    public function getPriceFormatted()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }
}