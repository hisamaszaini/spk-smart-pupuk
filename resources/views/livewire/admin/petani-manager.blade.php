<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use function Livewire\Volt\layout;
use App\Models\Petani;

layout('layouts.app');

new class extends Component
{
    use WithPagination;

    public $nama_petani, $luas_lahan, $produktivitas_lahan, $status_kepemilikan_lahan;
    public $petani_id;
    public $isEdit = false;

    public $search = '';
    public $sortField = 'nama_petani';
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
            'petanis' => Petani::query()
                ->when($this->search, function ($query) {
                    $query->where('nama_petani', 'like', '%' . $this->search . '%')
                        ->orWhere('status_kepemilikan_lahan', 'like', '%' . $this->search . '%');
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
        $this->nama_petani = '';
        $this->luas_lahan = '';
        $this->produktivitas_lahan = '';
        $this->status_kepemilikan_lahan = '';
        $this->petani_id = null;
        $this->isEdit = false;
    }

    public function create()
    {
        $this->resetFields();
        $this->dispatch('open-modal', name: 'petani');
    }

    public function store()
    {
        $this->validate([
            'nama_petani' => 'required',
            'luas_lahan' => 'required|numeric',
            'produktivitas_lahan' => 'required|numeric',
            'status_kepemilikan_lahan' => 'required',
        ]);

        Petani::create([
            'nama_petani' => $this->nama_petani,
            'luas_lahan' => $this->luas_lahan,
            'produktivitas_lahan' => $this->produktivitas_lahan,
            'status_kepemilikan_lahan' => $this->status_kepemilikan_lahan,
        ]);

        $this->dispatch('close-modal', name: 'petani');
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Petani berhasil ditambahkan.');
        $this->resetFields();
    }

    public function edit($id)
    {
        $petani = Petani::findOrFail($id);
        $this->petani_id = $id;
        $this->nama_petani = $petani->nama_petani;
        $this->luas_lahan = $petani->luas_lahan;
        $this->produktivitas_lahan = $petani->produktivitas_lahan;
        $this->status_kepemilikan_lahan = $petani->status_kepemilikan_lahan;
        $this->isEdit = true;

        $this->dispatch('open-modal', name: 'petani');
    }

    public function update()
    {
        $this->validate([
            'nama_petani' => 'required',
            'luas_lahan' => 'required|numeric',
            'produktivitas_lahan' => 'required|numeric',
            'status_kepemilikan_lahan' => 'required',
        ]);

        $petani = Petani::findOrFail($this->petani_id);
        $petani->update([
            'nama_petani' => $this->nama_petani,
            'luas_lahan' => $this->luas_lahan,
            'produktivitas_lahan' => $this->produktivitas_lahan,
            'status_kepemilikan_lahan' => $this->status_kepemilikan_lahan,
        ]);

        $this->dispatch('close-modal', name: 'petani');
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Petani berhasil diupdate.');
        $this->resetFields();
    }

    public function delete($id)
    {
        Petani::find($id)->delete();
        $this->dispatch('toast', variant: 'success', heading: 'Berhasil', content: 'Petani berhasil dihapus.');
    }
}; ?>
@section('title', 'Data Petani')

<div>
    <div class="space-y-6 animate-fade-in">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Data Petani</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola data petani dan informasi lahan mereka.</p>
            </div>
            <button wire:click="create" class="btn-primary">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Petani
            </button>
        </div>

        {{-- Table Card --}}
        <div class="card">
            <div class="card-header">
                <div class="search-wrapper max-w-sm w-full">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input wire:model.live="search" type="text" placeholder="Cari petani..." class="form-input" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('nama_petani')" class="sortable-th">
                                Nama
                                <span class="sort-icon {{ $sortField === 'nama_petani' ? 'active' : '' }}">
                                    @if($sortField === 'nama_petani') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </th>
                            <th wire:click="sortBy('luas_lahan')" class="sortable-th">
                                Luas (Ha)
                                <span class="sort-icon {{ $sortField === 'luas_lahan' ? 'active' : '' }}">
                                    @if($sortField === 'luas_lahan') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </th>
                            <th wire:click="sortBy('produktivitas_lahan')" class="sortable-th">
                                Produktivitas
                                <span class="sort-icon {{ $sortField === 'produktivitas_lahan' ? 'active' : '' }}">
                                    @if($sortField === 'produktivitas_lahan') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @else ↕ @endif
                                </span>
                            </th>
                            <th>Status</th>
                            <th class="th-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($petanis as $petani)
                        <tr>
                            <td class="text-gray-600 font-medium">{{ $petani->nama_petani }}</td>
                            <td class="text-gray-600">{{ $petani->luas_lahan }}</td>
                            <td class="text-gray-600">{{ $petani->produktivitas_lahan }}</td>
                            <td>
                                <span class="badge-neutral">{{ $petani->status_kepemilikan_lahan }}</span>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-1">
                                    <button wire:click="edit({{ $petani->id_petani }})"
                                        class="btn-ghost btn-sm btn-icon text-gray-400 hover:text-emerald-600">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $petani->id_petani }})"
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
                            <td colspan="5" class="text-center py-16 text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="size-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <span class="font-medium">Belum ada data petani</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $petanis->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit Petani --}}
    <div id="modal-petani" class="modal-overlay" onclick="closeModalOnBackdrop(event, 'petani')">
        <div class="modal-box">
            <button onclick="closeModal('petani')" class="modal-close-btn">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="modal-header">
                <h3 class="modal-title">{{ $isEdit ? 'Edit Petani' : 'Tambah Petani' }}</h3>
                <p class="modal-subtitle">Isi informasi detail petani di bawah ini.</p>
            </div>

            <form wire:submit="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">
                    <div>
                        <label class="form-label">Nama Petani</label>
                        <input wire:model="nama_petani" type="text" placeholder="Contoh: Budi Santoso" class="form-input" />
                        @error('nama_petani') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Luas Lahan (Ha)</label>
                            <input wire:model="luas_lahan" type="number" step="0.01" placeholder="0.00" class="form-input" />
                            @error('luas_lahan') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Produktivitas (Ton/Ha)</label>
                            <input wire:model="produktivitas_lahan" type="number" step="0.01" placeholder="0.00" class="form-input" />
                            @error('produktivitas_lahan') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Status Kepemilikan</label>
                        <select wire:model="status_kepemilikan_lahan" class="form-select">
                            <option value="">Pilih Status</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Sewa">Sewa</option>
                            <option value="Bagi Hasil">Bagi Hasil</option>
                        </select>
                        @error('status_kepemilikan_lahan') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('petani')" class="btn-ghost">Batal</button>
                    <button type="submit" class="btn-primary">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Petani' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>