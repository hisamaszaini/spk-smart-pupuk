<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Livewire\Volt\layout;
use App\Models\Kriteria;

layout('layouts.app');

new class extends Component
{
    use WithPagination;

    public $kode_kriteria, $nama_kriteria, $jenis_kriteria;
    public $kriteria_id;
    public $isEdit = false;

    public $search = '';
    public $sortField = 'kode_kriteria';
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
            'kriterias' => Kriteria::query()
                ->when($this->search, function ($query) {
                    $query->where('nama_kriteria', 'like', '%' . $this->search . '%')
                        ->orWhere('jenis_kriteria', 'like', '%' . $this->search . '%');
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
        $this->kode_kriteria = '';
        $this->nama_kriteria = '';
        $this->jenis_kriteria = '';
        $this->kriteria_id = null;
        $this->isEdit = false;
    }

    public function create()
    {
        $this->resetFields();
        $this->dispatch('open-modal', name: 'kriteria');
    }

    public function store()
    {
        $this->validate([
            'kode_kriteria' => 'required|unique:tabel_kriteria,kode_kriteria',
            'nama_kriteria' => 'required',
            'jenis_kriteria' => 'required',
        ]);

        Kriteria::create([
            'kode_kriteria' => $this->kode_kriteria,
            'nama_kriteria' => $this->nama_kriteria,
            'jenis_kriteria' => $this->jenis_kriteria,
        ]);

        $this->dispatch('close-modal', name: 'kriteria');
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Kriteria berhasil ditambahkan.');
        $this->resetFields();
    }

    public function edit($id)
    {
        $k = Kriteria::findOrFail($id);
        $this->kriteria_id = $id;
        $this->kode_kriteria = $k->kode_kriteria;
        $this->nama_kriteria = $k->nama_kriteria;
        $this->jenis_kriteria = $k->jenis_kriteria;
        $this->isEdit = true;

        $this->dispatch('open-modal', name: 'kriteria');
    }

    public function update()
    {
        $this->validate([
            'kode_kriteria' => 'required|unique:tabel_kriteria,kode_kriteria,' . $this->kriteria_id,
            'nama_kriteria' => 'required',
            'jenis_kriteria' => 'required',
        ]);

        $k = Kriteria::findOrFail($this->kriteria_id);
        $k->update([
            'kode_kriteria' => $this->kode_kriteria,
            'nama_kriteria' => $this->nama_kriteria,
            'jenis_kriteria' => $this->jenis_kriteria,
        ]);

        $this->dispatch('close-modal', name: 'kriteria');
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Kriteria berhasil diupdate.');
        $this->resetFields();
    }

    public function delete($id)
    {
        Kriteria::find($id)->delete();
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Kriteria berhasil dihapus.');
    }
}; ?>

<div>
    @section('title', 'Data Kriteria')
    <div class="space-y-6 animate-fade-in">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Data Kriteria</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola kriteria penilaian untuk metode SMART.</p>
            </div>
            <button wire:click="create" class="btn-primary">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Kriteria
            </button>
        </div>

        {{-- Table Card --}}
        <div class="card">
            <div class="card-header">
                <div class="search-wrapper max-w-sm w-full">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input wire:model.live="search" type="text" placeholder="Cari kriteria..." class="form-input" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('kode_kriteria')" class="sortable-th">
                                Kode Kriteria
                                <span class="sort-icon {{ $sortField === 'kode_kriteria' ? 'active' : '' }}">
                                    @if($sortField === 'kode_kriteria') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </th>
                            <th wire:click="sortBy('nama_kriteria')" class="sortable-th">
                                Nama Kriteria
                                <span class="sort-icon {{ $sortField === 'nama_kriteria' ? 'active' : '' }}">
                                    @if($sortField === 'nama_kriteria') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </th>
                            <th wire:click="sortBy('jenis_kriteria')" class="sortable-th">
                                Jenis
                                <span class="sort-icon {{ $sortField === 'jenis_kriteria' ? 'active' : '' }}">
                                    @if($sortField === 'jenis_kriteria') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </th>
                            <th class="th-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kriterias as $k)
                        <tr>
                            <td class="text-gray-600">{{ $k->kode_kriteria }}</td>
                            <td class="text-gray-600">{{ $k->nama_kriteria }}</td>
                            <td>
                                @if($k->jenis_kriteria === 'benefit')
                                <span class="badge-success">Benefit</span>
                                @else
                                <span class="badge-warning">Cost</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-1">
                                    <button wire:click="edit({{ $k->id_kriteria }})"
                                        class="btn-ghost btn-sm btn-icon text-gray-400 hover:text-emerald-600">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $k->id_kriteria }})"
                                        wire:confirm="Yakin ingin menghapus kriteria ini?"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span class="font-medium">Belum ada data kriteria</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $kriterias->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit Kriteria --}}
    <div id="modal-kriteria" class="modal-overlay" onclick="closeModalOnBackdrop(event, 'kriteria')">
        <div class="modal-box">
            <button onclick="closeModal('kriteria')" class="modal-close-btn">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="modal-header">
                <h3 class="modal-title">{{ $isEdit ? 'Edit Kriteria' : 'Tambah Kriteria' }}</h3>
                <p class="modal-subtitle">Tentukan kriteria penilaian dan kategorinya (Benefit/Cost).</p>
            </div>

            <form wire:submit="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">
                    <div>
                        <label class="form-label">Kode Kriteria</label>
                        <input wire:model="kode_kriteria" type="text" placeholder="Contoh: C1" class="form-input" />
                        @error('kode_kriteria') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Nama Kriteria</label>
                        <input wire:model="nama_kriteria" type="text" placeholder="Contoh: Luas Lahan" class="form-input" />
                        @error('nama_kriteria') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Jenis Kriteria</label>
                        <select wire:model="jenis_kriteria" class="form-select">
                            <option value="">Pilih Jenis</option>
                            <option value="benefit">Benefit (Makin Besar Makin Baik)</option>
                            <option value="cost">Cost (Makin Kecil Makin Baik)</option>
                        </select>
                        @error('jenis_kriteria') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('kriteria')" class="btn-ghost">Batal</button>
                    <button type="submit" class="btn-primary">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kriteria' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>