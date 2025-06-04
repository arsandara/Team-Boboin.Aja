<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;
    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'user_id',
        'room_id',
        'booking_reference',
        'guest_name',
        'guest_phone',
        'guest_email',
        'guest_count',
        'check_in',
        'check_out',
        'nights', 
        'duration', 
        'special_requests',
        'room_name',      
        'room_number',   
        'person',       
        'room_price',     
        'base_price',    
        'request_price', 
        'subtotal',
        'tax',          
        'tax_amount',
        'service_amount',
        'total_price',
        'early_checkin',  
        'late_checkout',
        'extra_bed',    
        'status',
        'payment_status',
        'payment_method',
        'payment_confirmed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'room_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'payment_confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Accessors & Mutators
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedCheckInAttribute()
    {
        return $this->check_in->format('d M Y');
    }

    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out->format('d M Y');
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->status, ['pending', 'confirmed', 'checked_in']);
    }

    public function getIsPaidAttribute()
    {
        return in_array($this->payment_status, ['paid', 'confirmed']) || 
               in_array($this->status, ['confirmed', 'checked_in']);
    }

    // Methods
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'payment_confirmed_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    public function checkIn()
    {
        $this->update([
            'status' => 'checked_in'
        ]);
    }

    public function checkOut()
    {
        $this->update([
            'status' => 'checked_out'
        ]);
    }
}