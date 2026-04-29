<?php

use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use App\Models\Kriteria;
use App\Models\BobotKriteria;

layout('layouts.app');

new class extends Component
{
    public $bobots = [];

    public function mount()
    {
        $kriterias = Kriteria::with('bobot')->get();
        foreach ($kriterias as $k) {
            $this->bobots[$k->id_kriteria] = $k->bobot->nilai_bobot ?? 0;
        }
    }

    public function with()
    {
        $kriterias = Kriteria::with('bobot')->get();
        $totalRaw = array_sum($this->bobots);

        return [
            'kriterias' => $kriterias,
            'totalRaw' => $totalRaw,
        ];
    }

    public function save()
    {
        foreach ($this->bobots as $id => $nilai) {
            BobotKriteria::updateOrCreate(
                ['id_kriteria' => $id],
                ['nilai_bobot' => $nilai]
            );
        }

        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Bobot kriteria berhasil disimpan.');
    }
};
?>

<div>
    @section('title', 'Bobot Kriteria')
    <div class="space-y-6 animate-fade-in">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Bobot Kriteria</h1>
                <p class="text-sm text-gray-500 mt-0.5">Atur nilai bobot untuk setiap kriteria. Sistem akan melakukan normalisasi secara otomatis.</p>
            </div>
            <button wire:click="save" class="btn-primary">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Simpan Perubahan
            </button>
        </div>

        {{-- Info Card --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-start gap-3">
            <svg class="size-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
            <p class="text-sm text-blue-700 font-medium">
                Bobot raw akan dinormalisasi otomatis (Wj = nilai / total). Pastikan total bobot raw tidak nol sebelum proses perhitungan.
            </p>
        </div>

        {{-- Table Card --}}
        <div class="card">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Kriteria</th>
                            <th>Jenis</th>
                            <th>Input Bobot (Raw)</th>
                            <th>Normalisasi (Wj)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriterias as $k)
                        <tr>
                            <td class="text-gray-600">{{ $k->nama_kriteria }}</td>
                            <td>
                                @if($k->jenis_kriteria === 'benefit')
                                <span class="badge-success">Benefit</span>
                                @else
                                <span class="badge-warning">Cost</span>
                                @endif
                            </td>
                            <td class="w-40">
                                <input type="number" step="0.1" wire:model.live="bobots.{{ $k->id_kriteria }}"
                                    class="form-input bg-gray-50 w-36" />
                            </td>
                            <td class="text-emerald-700 text-base">
                                @if($totalRaw > 0)
                                {{ round(($bobots[$k->id_kriteria] ?? 0) / $totalRaw, 4) }}
                                @else
                                <span class="text-gray-400 font-normal">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Bobot Raw</span>
                <span class="text-2xl font-bold text-emerald-700">{{ $totalRaw }}</span>
            </div>
        </div>
    </div>
</div>