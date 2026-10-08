@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('admin-content')
    <form method="GET" class="surface-card p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <input type="search" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama kategori..." class="form-input">
            </div>
            <select name="status" class="form-input">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors">
                Terapkan
            </button>
            @if (request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.categories.index') }}"
                   class="px-5 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors">
                    Reset
                </a>
            @endif
            <span class="ml-auto font-body-sm text-body-sm text-outline">{{ $categories->total() }} kategori</span>
        </div>
    </form>

    <div class="surface-card overflow-hidden">
        @if ($categories->isEmpty())
            <x-empty-state title="Kategori tidak ditemukan" description="Mulai dengan menambahkan kategori pertama Anda." icon="category"
                           :action="route('admin.categories.create')" action-label="Tambah Kategori" />
        @else
            <div class="table-shell">
                <table>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Slug</th>
                            <th>Produk</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if ($category->icon)
                                            <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                                                <span class="material-symbols-outlined text-[20px]">{{ $category->icon }}</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-body-sm text-body-sm text-on-surface font-semibold truncate">
                                                {{ $category->name }}
                                            </p>
                                            @if ($category->description)
                                                <p class="font-label-md text-label-md text-outline line-clamp-1">
                                                    {{ $category->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-mono font-label-md text-label-md text-outline">{{ $category->slug }}</span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $category->products_count }}</span>
                                </td>
                                <td>
                                    <span class="font-label-md text-label-md text-outline">{{ $category->sort_order }}</span>
                                </td>
                                <td>
                                    <span @class([
                                        'badge',
                                        'bg-tertiary-fixed text-tertiary-container' => $category->status === 'active',
                                        'bg-surface-container text-outline' => $category->status !== 'active',
                                    ])>{{ $category->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                              onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="px-6 py-4 border-t border-surface-container-low">
                    <x-pagination :paginator="$categories" />
                </div>
            @endif
        @endif
    </div>
@endsection