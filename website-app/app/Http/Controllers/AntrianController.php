<?php

namespace App\Http\Controllers;

use App\Enums\StatusAntrian;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AntrianController extends Controller
{
    
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        try {
            $date = \Carbon\Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            $date = now()->toDateString();
        }

        $antrians = Antrian::with(['pendaftaran.pasien', 'cluster'])
            ->whereDate('tanggal', $date)
            ->orderBy('no_antrian')
            ->paginate(10);

        return view('dashboard.antrian.index', compact('antrians', 'date'));
    }

    public function update(Request $request, Antrian $antrian)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_column(StatusAntrian::cases(), 'value')),
        ]);

        try {
            $antrian->update(['status' => $request->status]);
            // Status sync handled by AntrianObserver
            return redirect()->route('antrian.index')->with('success', 'Status antrian nomor ' . $antrian->no_antrian . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Antrian update failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui status antrian.']);
        }
    }

    public function destroy()
    {
        // try {
        //     $no_antrian = $antrian->no_antrian;
        //     $antrian->delete();
        //     // Optionally update pendaftaran status to Dibatalkan
        //     $antrian->pendaftaran->update(['status' => StatusAntrian::DIBATALKAN]);
        //     return redirect()->route('antrian.index')->with('success', 'Antrian nomor ' . $no_antrian . ' berhasil dihapus.');
        // } catch (\Exception $e) {
        //     Log::error('Antrian deletion failed: ' . $e->getMessage());
        //     return redirect()->back()->withErrors(['error' => 'Gagal menghapus antrian.']);
        // }
    }
}