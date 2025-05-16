@extends('layouts.app')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview statistik dan informasi penting')

@section('content')
    <div class="grid grid-cols-3 gap-6">
        <a href="/pasien">
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-lg font-semibold">Jumlah Pasien</h3>
                <p class="text-2xl font-bold text-green-600">120</p>
            </div>
        </a>
        <a href="/antrian">
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-lg font-semibold">Antrian Hari Ini</h3>
                <p class="text-2xl font-bold text-yellow-500">34</p>
            </div>
        </a>
        <a href="/dokter">
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-lg font-semibold">Dokter Aktif</h3>
                <p class="text-2xl font-bold text-blue-500">8</p>
            </div>
        </a>    
    </div>

    {{-- Chart Section --}}
    <div class="bg-white rounded-xl shadow p-6 mt-6">
        <h3 class="text-lg font-semibold mb-4">Statistik Kunjungan Bulanan</h3>
        <canvas id="kunjunganChart" height="100"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('kunjunganChart').getContext('2d');
        const kunjunganChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: [120, 90, 140, 100, 180, 150],
                    backgroundColor: 'rgba(34,197,94,0.2)',
                    borderColor: 'rgba(34,197,94,1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection
