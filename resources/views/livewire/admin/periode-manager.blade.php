<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Livewire\Volt\layout;
use App\Models\PeriodePupuk;

layout('layouts.app');

new class extends Component
{
    use WithPagination;

    public $total_pupuk, $periode;
    public $periode_id;
    public $isEdit = false;

    public $search = '';
    public $sortField = 'periode';
    public $sortDirection = 'desc';

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
            'periodes' => PeriodePupuk::query()
                ->when($this->search, function ($query) {
                    $query->where('periode', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->total_pupuk = '';
        $this->periode = '';
        $this->periode_id = null;
        $this->isEdit = false;
    }

    public function create()
    {
        $this->resetFields();
        $this->dispatch('open-modal', name: 'periode');
    }

    public function store()
    {
        $this->validate([
            'total_pupuk' => 'required|numeric',
            'periode' => 'required',
        ]);

        PeriodePupuk::create([
            'total_pupuk' => $this->total_pupuk,
            'periode' => $this->periode,
        ]);

        $this->dispatch('close-modal', name: 'periode');
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Periode berhasil ditambahkan.');
        $this->resetFields();
    }

    public function edit($id)
    {
        $p = PeriodePupuk::findOrFail($id);
        $this->periode_id = $id;
        $this->total_pupuk = $p->total_pupuk;
        $this->periode = $p->periode;
        $this->isEdit = true;

        $this->dispatch('open-modal', name: 'periode');
    }

    public function update()
    {
        $this->validate([
            'total_pupuk' => 'required|numeric',
            'periode' => 'required',
        ]);

        $p = PeriodePupuk::findOrFail($this->periode_id);
        $p->update([
            'total_pupuk' => $this->total_pupuk,
            'periode' => $this->periode,
        ]);

        $this->dispatch('close-modal', name: 'periode');
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Periode berhasil diupdate.');
        $this->resetFields();
    }

    public function delete($id)
    {
        PeriodePupuk::find($id)->delete();
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Periode berhasil dihapus.');
    }
}; ?>

<div>
    @section('title', 'Data Periode')
    <div class="space-y-6 animate-fade-in">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Data Periode Pupuk</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola periode pembagian pupuk dan total kuota tersedia.</p>
            </div>
            <button wire:click="create" class="btn-primary">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Periode
            </button>
        </div>

        {{-- Table Card --}}
        <div class="card">
            <div class="card-header">
                <div class="search-wrapper max-w-sm w-full">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input wire:model.live="search" type="text" placeholder="Cari periode..." class="form-input" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('periode')" class="sortable-th">
                                Periode
                                <span class="sort-icon {{ $sortField === 'periode' ? 'active' : '' }}">
                                    @if($sortField === 'periode')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @else
                                    ↕
                                    @endif
                                </span>
                            </th>
                            <th wire:click="sortBy('total_pupuk')" class="sortable-th">
                                Total Pupuk (Kg)
                                <span class="sort-icon {{ $sortField === 'total_pupuk' ? 'active' : '' }}">
                                    @if($sortField === 'total_pupuk')
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                    @else
                                    ↕
                                    @endif
                                </span>
                            </th>
                            <th class="th-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodes as $p)
                        <tr>
                            <td class="text-gray-600 font-medium">{{ $p->periode }}</td>
                            <td class="text-gray-600">{{ number_format($p->total_pupuk, 2) }} Kg</td>
                            <td class="text-right">
                                <div class="flex justify-end gap-1">
                                    <button wire:click="edit({{ $p->id_periode }})"
                                        class="btn-ghost btn-sm btn-icon text-gray-400 hover:text-emerald-600">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $p->id_periode }})"
                                        wire:confirm="Yakin ingin menghapus data ini?"
                                        class="btn-ghost btn-sm btn-icon text-gray-400 hover:text-red-500">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-16 text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="size-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                                    </svg>
                                    <span class="font-medium">Belum ada data periode</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $periodes->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit Periode --}}
    <div id="modal-periode" class="modal-overlay" onclick="closeModalOnBackdrop(event, 'periode')">
        <div class="modal-box">
            <button onclick="closeModal('periode')" class="modal-close-btn">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="modal-header">
                <h3 class="modal-title">{{ $isEdit ? 'Edit Periode' : 'Tambah Periode' }}</h3>
                <p class="modal-subtitle">Tentukan nama periode and jumlah kuota pupuk yang tersedia.</p>
            </div>

            <form wire:submit="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">
                    <div>
                        <label class="form-label">Nama Periode</label>
                        <input wire:model="periode" type="text" placeholder="Contoh: Q1 2024" class="form-input" />
                        @error('periode') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Total Pupuk (Kg)</label>
                        <input wire:model="total_pupuk" type="number" placeholder="0" class="form-input" />
                        @error('total_pupuk') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('periode')" class="btn-ghost">Batal</button>
                    <button type="submit" class="btn-primary">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Periode' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>