@extends('layouts.app')

@section('title', 'Detail Feedback')
@section('page_title', 'Detail Feedback')
@section('page_subtitle', 'Informasi lengkap feedback pasien')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Detail Feedback</h3>
            <a href="{{ route('feedback.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-4">Informasi Pasien</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama Pasien</p>
                            <p class="text-base">{{ $feedback->pasien->nama ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nomor RM</p>
                            <p class="text-base">{{ $feedback->pasien->no_rm ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">NIK</p>
                            <p class="text-base">{{ $feedback->pasien->nik ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-4">Detail Feedback</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Rating</p>
                            <div class="flex mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xl mr-1"></i>
                                @endfor
                                <span class="ml-2 text-base">{{ $feedback->rating }}/5</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Dikirim</p>
                            <p class="text-base">{{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Terkait Rekam Medis</p>
                            @if($feedback->id_medical_record)
                                <a href="{{ route('medical_record.show', $feedback->id_medical_record) }}" class="text-blue-600 hover:underline">
                                    Lihat Rekam Medis ({{ $feedback->medicalRecord->tanggal_kunjungan->format('d/m/Y') }})
                                </a>
                            @else
                                <p class="text-base text-gray-400">Tidak terkait dengan rekam medis</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-md font-medium text-gray-700 mb-4">Komentar</h4>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    @if($feedback->comment)
                        <p class="text-base text-gray-700 whitespace-pre-line">{{ $feedback->comment }}</p>
                    @else
                        <p class="text-base text-gray-400">Tidak ada komentar.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
