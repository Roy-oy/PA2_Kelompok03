@extends('layouts.app')

@section('title', 'Feedback Pasien')
@section('page_title', 'Feedback Pasien')
@section('page_subtitle', 'Daftar semua feedback dari pasien')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Daftar Feedback</h3>
            <p class="mt-1 text-sm text-gray-500">
                Feedback yang telah diberikan oleh pasien.
            </p>
        </div>

        <div class="mb-4 flex justify-between items-center">
            <div class="flex space-x-2">
                <a href="{{ route('feedback.index', ['filter' => 'all']) }}" class="px-4 py-2 text-sm {{ request('filter', 'all') === 'all' ? 'bg-green-100 text-green-800 font-semibold' : 'bg-gray-100 text-gray-700' }} rounded-md">
                    Semua Feedback
                </a>
                <a href="{{ route('feedback.index', ['filter' => 'medical']) }}" class="px-4 py-2 text-sm {{ request('filter') === 'medical' ? 'bg-green-100 text-green-800 font-semibold' : 'bg-gray-100 text-gray-700' }} rounded-md">
                    Feedback Rekam Medis
                </a>
            </div>

            <form action="{{ route('feedback.index') }}" method="GET" class="flex">
                <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari feedback..." class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rekam Medis</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($feedbacks as $index => $feedback)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $feedback->pasien->nama ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $feedback->pasien->no_rm ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Str::limit($feedback->comment, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($feedback->id_medical_record)
                                    <a href="{{ route('medical_record.show', $feedback->id_medical_record) }}" class="text-blue-600 hover:underline">
                                        Lihat Rekam Medis
                                    </a>
                                @else
                                    <span class="text-gray-400">Tidak terkait</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feedback->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('feedback.show', $feedback->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                                Tidak ada data feedback.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($feedbacks instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $feedbacks->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
