<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Livewire\Volt\layout;
use App\Models\HasilPerankingan;

layout('layouts.app');

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'peringkat';
    public $sortDirection = 'asc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

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
            'rankings' => HasilPerankingan::query()
                ->with('petani')
                ->whereHas('petani', function ($q) {
                    $q->where('nama_petani', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
};
?>

<div>
    @section('title', 'Hasil Perangkingan')
    <div class="space-y-6 animate-fade-in">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Hasil Perangkingan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Urutan prioritas petani berdasarkan nilai preferensi SMART.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="search-wrapper max-w-sm w-full">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input wire:model.live="search" type="text" placeholder="Cari nama petani..." class="form-input" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th wire:click="sortBy('peringkat')" class="sortable-th">
                            Peringkat
                            <span class="sort-icon {{ $sortField === 'peringkat' ? 'active' : '' }}">
                                @if($sortField === 'peringkat') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                            </span>
                        </th>
                        <th>Nama Petani</th>
                        <th wire:click="sortBy('nilai_preferensi')" class="sortable-th">
                            Nilai Preferensi
                            <span class="sort-icon {{ $sortField === 'nilai_preferensi' ? 'active' : '' }}">
                                @if($sortField === 'nilai_preferensi') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                            </span>
                        </th>
                        <th class="th-end">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankings as $rank)
                    <tr>
                        <td>
                            <span class="badge-rank {{ $rank->peringkat <= 3 ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                @if($rank->peringkat === 1)
                                    🥇
                                @elseif($rank->peringkat === 2)
                                    🥈
                                @elseif($rank->peringkat === 3)
                                    🥉
                                @else
                                    {{ $rank->peringkat }}
                                @endif
                            </span>
                        </td>
                        <td class="font-semibold text-gray-900">{{ $rank->petani->nama_petani }}</td>
                        <td class="text-gray-700 font-medium">{{ round($rank->nilai_preferensi, 4) }}</td>
                        <td class="text-right">
                            @if($rank->peringkat <= 3)
                                <span class="badge-success">Prioritas Tinggi</span>
                            @else
                                <span class="badge-neutral">Reguler</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-16 text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="size-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                                </svg>
                                <span class="font-medium">Belum ada data hasil perhitungan</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $rankings->links() }}
        </div>
    </div>
    </div>
</div>