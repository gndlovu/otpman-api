<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Otp extends Model
{
    use Notifiable;

    // use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'pin',
        'resent_count',
        'generated_at',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'generated_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at < Carbon::now();
    }
}
