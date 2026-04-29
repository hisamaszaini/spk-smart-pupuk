<?php

use Livewire\Volt\Component;
use function Livewire\Volt\layout;
use App\Services\SmartService;
use App\Models\Petani;
use App\Models\PeriodePupuk;
use App\Models\Kriteria;
use App\Models\BobotKriteria;
use App\Models\AlokasiPupuk;
use App\Models\HasilPerankingan;

layout('layouts.app');

new class extends Component
{
    public $isCalculated = false;
    public $results = [];
    public $calculationData = [];
    public $maxPreference = 0;
    public $periode_id;
    public $activePeriode;

    public function mount()
    {
        $this->activePeriode = PeriodePupuk::latest()->first();
        if ($this->activePeriode) {
            $this->periode_id = $this->activePeriode->id_periode;
        }
    }

    public function with()
    {
        return [
            'totalPetani' => Petani::count(),
            'totalBobot' => BobotKriteria::sum('nilai_bobot'),
            'periodes' => PeriodePupuk::latest()->get(),
        ];
    }

    public function updatedPeriodeId($value)
    {
        $this->activePeriode = PeriodePupuk::find($value);
    }

    public function startCalculation()
    {
        if (!$this->activePeriode) {
            $this->dispatch('toast', variant: 'error', heading: 'Gagal', content: 'Silakan pilih periode terlebih dahulu.');
            return;
        }

        $service = new SmartService();
        $this->calculationData = $service->calculate($this->activePeriode->total_pupuk);
        $this->results = $this->calculationData['results'];
        $this->maxPreference = !empty($this->results) ? max(array_column($this->results, 'total_preference')) : 0;
        $this->isCalculated = true;

        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Perhitungan SMART selesai.');
    }

    public function saveResults()
    {
        if (!$this->isCalculated || !$this->activePeriode) return;

        foreach ($this->results as $res) {
            HasilPerankingan::updateOrCreate(
                ['id_petani' => $res['petani']->id_petani],
                [
                    'nilai_preferensi' => $res['total_preference'],
                    'peringkat' => $res['peringkat'],
                ]
            );

            AlokasiPupuk::updateOrCreate(
                [
                    'id_petani' => $res['petani']->id_petani,
                    'id_periode' => $this->activePeriode->id_periode,
                ],
                [
                    'nilai_preferensi' => $res['total_preference'],
                    'jumlah_pupuk' => $res['alokasi_pupuk'],
                ]
            );
        }

        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Hasil perhitungan telah disimpan.');
        return redirect()->route('admin.ranking');
    }
};
?>
@section('title', 'Proses SPK')

<div class="space-y-6 animate-fade-in">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Proses Perhitungan SMART</h1>
        <p class="text-sm text-gray-500 mt-0.5">Lakukan perhitungan alokasi pupuk secara otomatis.</p>
    </div>

    @if(!$isCalculated)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="card p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-50 rounded-xl flex-shrink-0">
                            <svg class="size-6 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Jumlah Petani</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalPetani }} Orang</p>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-50 rounded-xl flex-shrink-0">
                            <svg class="size-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.97zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.97z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Bobot Kriteria</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalBobot }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Periode Selector --}}
            <div class="card p-6 space-y-4">
                <h2 class="text-base font-bold text-gray-800">Pilih Periode Perhitungan</h2>
                <div>
                    <label class="form-label">Periode Aktif</label>
                    <select wire:model.live="periode_id" class="form-select">
                        @foreach($periodes as $p)
                        <option value="{{ $p->id_periode }}">{{ $p->periode }} ({{ number_format($p->total_pupuk, 2) }} Kg)</option>
                        @endforeach
                    </select>
                </div>

                @if($activePeriode)
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Pupuk Tersedia:</span>
                        <span class="font-bold text-emerald-700">{{ number_format($activePeriode->total_pupuk, 2) }} Kg</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="badge-neutral">Siap Dihitung</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Start CTA --}}
        <div class="lg:col-span-1">
            <div class="bg-emerald-900 p-8 rounded-2xl text-white shadow-xl flex flex-col items-center justify-center text-center space-y-5 h-full relative overflow-hidden">
                <div class="absolute -top-8 -right-8 size-32 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-8 -left-6 size-24 rounded-full bg-white/5"></div>
                <div class="relative p-4 bg-white/15 rounded-2xl backdrop-blur-sm">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.638 4.5 4.564v17.472h15V4.564c0-.926-.807-1.864-1.907-1.992A48.507 48.507 0 0012 2.25z" />
                    </svg>
                </div>
                <div class="relative">
                    <h3 class="text-xl font-bold text-white">Mulai Perhitungan</h3>
                    <p class="text-emerald-100/80 mt-2 text-sm leading-relaxed">
                        Sistem akan menghitung alokasi pupuk secara proporsional berdasarkan preferensi SMART.
                    </p>
                </div>
                <button wire:click="startCalculation" class="btn-white w-full relative text-base py-3">
                    Mulai Hitung Sekarang
                </button>
            </div>
        </div>
    </div>

    @else
    {{-- Results --}}
    <div class="space-y-8 animate-fade-in">
        <div class="flex items-center justify-between py-4">
            <h2 class="text-xl font-bold text-gray-900">Proses Perhitungan SMART
                <span class="text-emerald-700">({{ $activePeriode->periode }})</span>
            </h2>
            <div class="flex gap-2">
                <button wire:click="$set('isCalculated', false)" class="btn-ghost">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Ulangi
                </button>
                <button wire:click="saveResults" class="btn-primary">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Simpan Alokasi
                </button>
            </div>
        </div>

        {{-- 1. Matriks Keputusan --}}
        <div class="space-y-3 py-4">
            <h3 class="text-lg font-semibold text-gray-800">1. Matriks Keputusan (X)</h3>
            <div class="card">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">No</th>
                                <th>Nama Petani</th>
                                @foreach($calculationData['kriterias'] as $k)
                                <th class="text-center">{{ $k->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $res)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-gray-700">{{ $res['petani']->nama_petani }}</td>
                                @foreach($calculationData['kriterias'] as $k)
                                <td class="text-center">{{ $res['raw_values'][$k->id_kriteria] ?? 0 }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 2. Bobot & Normalisasi --}}
        <div class="space-y-3 py-4">
            <h3 class="text-lg font-semibold text-gray-800">2. Bobot & Normalisasi (Wj)</h3>
            <div class="card">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th class="text-center">Nilai Bobot</th>
                                <th class="text-center">Bobot Relatif (Wj)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calculationData['kriterias'] as $k)
                            <tr>
                                <td>{{ $k->nama_kriteria }} ({{ $k->kode_kriteria }})</td>
                                <td class="text-center">{{ $k->bobot->nilai_bobot }}</td>
                                <td class="text-center font-mono">{{ number_format($calculationData['normalized_weights'][$k->id_kriteria] ?? 0, 4) }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-semibold">
                                <td>Total Bobot</td>
                                <td class="text-center text-emerald-700">{{ $calculationData['total_weight'] }}</td>
                                <td class="text-center">1.0000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 3. Nilai Utility (U) --}}
        <div class="space-y-3 py-4">
            <h3 class="text-lg font-semibold text-gray-800">3. Nilai Utility (U)</h3>
            <div class="card">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">No</th>
                                <th>Nama Petani</th>
                                @foreach($calculationData['kriterias'] as $k)
                                <th class="text-center">{{ $k->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $res)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-gray-700">{{ $res['petani']->nama_petani }}</td>
                                @foreach($calculationData['kriterias'] as $k)
                                <td class="text-center font-mono">{{ number_format($res['utilities'][$k->id_kriteria] ?? 0, 4) }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 4. Hasil Akhir & Perankingan --}}
        <div class="space-y-3 py-4">
            <h3 class="text-lg font-semibold text-gray-800">4. Hasil Akhir & Perankingan (V)</h3>
            <div class="card">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">Rank</th>
                                <th>Nama Petani</th>
                                <th class="text-center">Total Preferensi</th>
                                <th class="text-right pr-8">Alokasi Pupuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $res)
                            <tr class="{{ $res['peringkat'] <= 3 ? 'bg-emerald-50/50' : '' }}">
                                <td>
                                    <span class="badge-rank {{ $res['peringkat'] <= 3 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $res['peringkat'] }}
                                    </span>
                                </td>
                                <td class="text-gray-700">{{ $res['petani']->nama_petani }}</td>
                                <td class="text-center font-mono text-emerald-700">{{ number_format($res['total_preference'], 4) }}</td>
                                <td class="text-right pr-8 font-semibold text-gray-700">{{ number_format($res['alokasi_pupuk'], 2) }} Kg</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>