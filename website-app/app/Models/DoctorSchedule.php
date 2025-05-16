<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class DoctorSchedule extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'doctor_schedules';

    protected $fillable = [
        'doctor_id',
        'schedule_day',
        'start_time',
        'end_time',
        'cluster_id',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Relationship with Doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Relationship with Cluster.
     */
    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }
}