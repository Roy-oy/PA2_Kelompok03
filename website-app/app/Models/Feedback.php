<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_feedback',
        'rating',
        'comment',
        'created_at',
        'updated_at',
        'id_medical_record',
        'pasien_id',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_medical_record');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
}
