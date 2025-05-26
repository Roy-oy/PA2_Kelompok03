<?php

namespace App\Observers;

use App\Models\Antrian;

class AntrianObserver
{
    public function updated(Antrian $antrian)
    {
        if ($antrian->isDirty('status')) {
            $antrian->pendaftaran->update(['status' => $antrian->status]);
        }
    }
}