<?php

namespace App\Models;

use App\Enums\StatusAntrian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Antrian extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'antrians';

    protected $fillable = [
        'pendaftaran_id',
        'cluster_id',
        'no_antrian',
        'tanggal',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => StatusAntrian::class,
    ];

    /**
     * Relasi ke model Pendaftaran
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Relasi ke model Cluster
     */
    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    /**
     * Generate the next unique no_antrian for the given date.
     *
     * @param \DateTimeInterface|string $date
     * @return string
     */
    public static function generateNoAntrian($date): string
    {
        $date = \Carbon\Carbon::parse($date)->toDateString();
        
        // Find the last no_antrian for the given date
        $lastAntrian = self::where('tanggal', $date)
            ->orderBy('no_antrian', 'desc')
            ->first();

        if (!$lastAntrian) {
            return 'A01';
        }

        $lastNo = $lastAntrian->no_antrian;
        preg_match('/([A-Z])(\d+)/', $lastNo, $matches);
        $letter = $matches[1];
        $number = (int) $matches[2];

        if ($number < 99) {
            // Increment number
            return $letter . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
        }

        // Move to next letter (e.g., A99 -> B01)
        $nextLetter = chr(ord($letter) + 1);
        return $nextLetter . '01';
    }
}