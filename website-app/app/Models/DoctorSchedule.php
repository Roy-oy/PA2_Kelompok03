<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class DoctorSchedule extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'doctor_schedules';

    protected $fillable = [
        'doctor_id',
        'schedule_date',
        'start_time',
        'end_time',
        'cluster_id',
        'status',
    ];

    protected $casts = [
        'schedule_date' => 'date',
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

    /**
     * Accessor to get the name of the day from schedule_date.
     */
    public function getDayNameAttribute()
    {
        return Carbon::parse($this->schedule_date)->locale('en')->dayName;
    }

    /**
     * Accessor to get formatted date (e.g. "Monday, 6 May 2025").
     */
    public function getFormattedScheduleDateAttribute()
    {
        return Carbon::parse($this->schedule_date)->translatedFormat('l, j F Y');
    }
}
