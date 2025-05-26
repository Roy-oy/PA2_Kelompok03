<?php

namespace App\Enums;

enum StatusAntrian: string
{
    case BELUM_DIPANGGIL = 'Belum Dipanggil';
    case SEDANG_DILAYANI = 'Sedang Dilayani';
    case SELESAI = 'Selesai';
    case DIBATALKAN = 'Dibatalkan';
}