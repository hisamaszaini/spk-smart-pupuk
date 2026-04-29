<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Livewire\Volt\layout;
use App\Models\AlokasiPupuk;
use App\Models\PeriodePupuk;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlokasiExport;

layout('layouts.app');

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $periode_id = '';
    public $sortField = 'id_alokasi';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'periode_id', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function with()
    {
        return [
            'alokasis' => AlokasiPupuk::query()
                ->with(['petani', 'periode'])
                ->when($this->periode_id, function ($q) {
                    $q->where('id_periode', $this->periode_id);
                })
                ->whereHas('petani', function ($q) {
                    $q->where('nama_petani', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15),
            'periodes' => PeriodePupuk::all(),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPeriodeId()
    {
        $this->resetPage();
    }

    public function export()
    {
        return Excel::download(new AlokasiExport($this->periode_id), 'laporan-distribusi-pupuk.xlsx');
    }
};
?>
@section('title', 'Hasil Alokasi')

<div class="space-y-6 animate-fade-in">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Hasil Alokasi Pupuk</h1>
        <p class="text-sm text-gray-500 mt-0.5">Laporan rincian jumlah pupuk yang dialokasikan ke setiap petani.</p>
    </div>

    <div class="card">
        <div class="card-header flex-wrap gap-3">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="search-wrapper max-w-xs w-full">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input wire:model.live="search" type="text" placeholder="Cari petani..." class="form-input" />
                </div>
                <select wire:model.live="periode_id" class="form-select max-w-[200px]">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $p)
                    <option value="{{ $p->id_periode }}">{{ $p->periode }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="export" class="btn-primary">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Excel
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Nama Petani</th>
                        <th wire:click="sortBy('nilai_preferensi')" class="sortable-th">
                            Preferensi
                            <span class="sort-icon {{ $sortField === 'nilai_preferensi' ? 'active' : '' }}">
                                @if($sortField === 'nilai_preferensi') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                            </span>
                        </th>
                        <th wire:click="sortBy('jumlah_pupuk')" class="sortable-th">
                            Jumlah Alokasi
                            <span class="sort-icon {{ $sortField === 'jumlah_pupuk' ? 'active' : '' }}">
                                @if($sortField === 'jumlah_pupuk') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alokasis as $alokasi)
                    <tr>
                        <td>
                            <span class="badge-info">{{ $alokasi->periode->periode }}</span>
                        </td>
                        <td class="font-semibold text-gray-900">{{ $alokasi->petani->nama_petani }}</td>
                        <td class="text-gray-700">{{ round($alokasi->nilai_preferensi, 4) }}</td>
                        <td>
                            <span class="text-lg font-bold text-emerald-700">{{ number_format($alokasi->jumlah_pupuk, 2) }} Kg</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-16 text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="size-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <span class="font-medium">Belum ada data alokasi pupuk</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $alokasis->links() }}
        </div>
    </div>
</div>