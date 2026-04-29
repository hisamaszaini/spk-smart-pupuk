<?php

use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use App\Models\Petani;
use App\Models\Kriteria;
use App\Models\AlokasiPupuk;

layout('layouts.app');

new class extends Component
{
    public function with()
    {
        return [
            'totalPetani' => Petani::count(),
            'totalKriteria' => Kriteria::count(),
            'totalPupukDistributed' => AlokasiPupuk::sum('jumlah_pupuk'),
            'latestAllocations' => AlokasiPupuk::with('petani', 'periode')->latest()->take(5)->get(),
        ];
    }
};
?>
@section('title', 'Dashboard')

<div class="space-y-6 animate-fade-in">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard Overview</h1>
        <p class="text-sm text-gray-500 mt-0.5">Ringkasan status sistem SMART Pupuk saat ini.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="card p-6 flex items-center gap-4 overflow-hidden relative">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent pointer-events-none"></div>
            <div class="bg-emerald-100 p-3.5 rounded-2xl flex-shrink-0">
                <svg class="size-7 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Petani</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-0.5">{{ $totalPetani }}</h3>
            </div>
        </div>

        <div class="card p-6 flex items-center gap-4 overflow-hidden relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent pointer-events-none"></div>
            <div class="bg-blue-100 p-3.5 rounded-2xl flex-shrink-0">
                <svg class="size-7 text-blue-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Jumlah Kriteria</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-0.5">{{ $totalKriteria }}</h3>
            </div>
        </div>

        <div class="card p-6 flex items-center gap-4 overflow-hidden relative">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-transparent pointer-events-none"></div>
            <div class="bg-orange-100 p-3.5 rounded-2xl flex-shrink-0">
                <svg class="size-7 text-orange-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pupuk Terdistribusi</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalPupukDistributed, 2) }} Kg</h3>
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
        {{-- Recent Allocations Table --}}
        <div class="lg:col-span-3 card">
            <div class="card-header">
                <h2 class="text-base font-bold text-gray-800">Alokasi Terbaru</h2>
                <a href="{{ route('admin.allocation') }}" wire:navigate
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition-colors">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Petani</th>
                            <th>Alokasi</th>
                            <th>Periode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestAllocations as $alloc)
                        <tr>
                            <td class="font-semibold text-gray-900">{{ $alloc->petani->nama_petani }}</td>
                            <td class="font-bold text-emerald-700">{{ number_format($alloc->jumlah_pupuk, 2) }} Kg</td>
                            <td class="text-gray-500 text-sm">{{ $alloc->periode->periode }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-gray-400">
                                <div class="flex flex-col items-center gap-1">
                                    <svg class="size-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <span class="text-sm font-medium">Belum ada data alokasi</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CTA Card --}}
        <div class="lg:col-span-2 bg-emerald-900 rounded-2xl p-8 text-white shadow-xl flex flex-col justify-center space-y-5 relative overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-8 -right-8 size-40 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-10 -left-6 size-32 rounded-full bg-white/5"></div>

            <div class="relative">
                <div class="inline-flex p-3 bg-white/15 rounded-2xl mb-4">
                    <svg class="size-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.638 4.5 4.564v17.472h15V4.564c0-.926-.807-1.864-1.907-1.992A48.507 48.507 0 0012 2.25z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Siap melakukan perhitungan?</h2>
                <p class="text-emerald-100/80 mt-2 text-sm leading-relaxed">
                    Sistem SMART siap memproses data petani dan kriteria untuk menghasilkan alokasi pupuk yang adil dan transparan.
                </p>
                <a href="{{ route('admin.spk') }}" wire:navigate class="btn-white mt-5 inline-flex relative">
                    Mulai Perhitungan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>