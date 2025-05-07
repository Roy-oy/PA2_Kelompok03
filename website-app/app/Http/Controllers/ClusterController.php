<?php
namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    /**
     * Tampilkan daftar cluster.
     */
    public function index()
{
    $clusters = Cluster::latest()->paginate(5); // Menampilkan 10 data per halaman
    return view('dashboard.klaster.index', compact('clusters'));
}

    /**
     * Tampilkan form untuk menambahkan cluster baru.
     */
    public function create()
    {
        return view('dashboard.klaster.create');
    }

    /**
     * Simpan cluster baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|in:Klaster 1,Klaster 2,Klaster 3,Klaster 4,Klaster 5',
            'description' => 'required|string',
        ]);

        Cluster::create($request->all());

        return redirect()->route('klaster.index')
            ->with('success', 'Cluster berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit cluster.
     */
    public function edit($id)
    {
        $cluster = Cluster::findOrFail($id);
        return view('dashboard.klaster.edit', compact('cluster'));
    }

    /**
     * Perbarui data cluster di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|in:Klaster 1,Klaster 2,Klaster 3,Klaster 4,Klaster 5',
            'description' => 'required|string',
        ]);

        $cluster = Cluster::findOrFail($id);
        $cluster->update($request->all());

        return redirect()->route('klaster.index')
            ->with('success', 'Cluster berhasil diperbarui.');
    }

    /**
     * Hapus cluster dari database.
     */
    public function destroy($id)
    {
        $cluster = Cluster::findOrFail($id);
        $cluster->delete();

        return redirect()->route('klaster.index')
            ->with('success', 'Cluster berhasil dihapus.');
    }
}