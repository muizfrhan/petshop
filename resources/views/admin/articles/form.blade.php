@extends('layouts.admin')

@section('title', $mode === 'create' ? 'Tulis Artikel' : 'Edit Artikel')
@section('subtitle', $mode === 'create' ? 'Buat artikel baru' : $article->title)
@section('hideErrors', true)

@section('admin-content')
    <form action="{{ $mode === 'create' ? route('admin.articles.store') : route('admin.articles.update', $article) }}"
          method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Isi Artikel</h2>

                <div class="space-y-4">
                    <x-form-field name="title" label="Judul" required :value="$article->title"
                                  placeholder="Contoh: Tips Memandukan Kucing yang Takut Air" />

                    <x-form-field name="slug" label="Slug URL" :value="$article->slug"
                                  placeholder="otomatis-dari-judul"
                                  hint="Dipakai di URL: /artikel/{slug}" />

                    <x-form-field name="excerpt" label="Ringkasan" type="textarea" required :value="$article->excerpt"
                                  placeholder="Ringkasan singkat yang tampil di daftar artikel (20-400 karakter)."
                                  hint="Maksimal 400 karakter." />

                    <div>
                        <label class="form-label" for="content">Isi Konten</label>
                        <textarea id="content" name="content" rows="18"
                                  class="form-input py-3 font-body-sm text-body-sm leading-relaxed"
                                  placeholder="Tulis isi artikel di sini. Anda dapat memakai HTML sederhana (<p>, <h2>, <ul>, <strong>).">{{ old('content', $article->content) }}</textarea>
                        <p class="mt-1.5 font-body-sm text-body-sm text-outline">
                            HTML diperbolehkan; skrip berbahaya akan dibersihkan otomatis.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Publikasi</h2>

                <div class="space-y-4">
                    <x-form-field name="status" label="Status" type="select" required>
                        <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $article->status) === 'published')>Terbit</option>
                    </x-form-field>

                    <x-form-field name="article_category_id" label="Kategori" type="select">
                        <option value="">Tanpa kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('article_category_id', $article->article_category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-form-field>

                    <x-form-field name="published_at" label="Tanggal Terbit" type="date"
                                  :value="$article->published_at?->format('Y-m-d')"
                                  hint="Kosongkan untuk mengisi otomatis saat diterbitkan." />
                </div>
            </div>

            <div class="surface-card p-6">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold mb-5">Gambar Sampul</h2>

                <label for="thumbnail" class="block cursor-pointer">
                    <div class="border-2 border-dashed border-surface-outline-app rounded-2xl p-6 text-center hover:border-primary hover:bg-surface-container-low transition-colors">
                        @if ($article->thumbnail)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($article->thumbnail) }}"
                                 alt="{{ $article->title }}" class="w-full h-32 object-cover rounded-xl mb-3">
                        @else
                            <span class="material-symbols-outlined text-[40px] text-outline mb-2">image</span>
                        @endif
                        <p class="font-label-lg text-label-lg text-on-surface font-bold">
                            {{ $article->thumbnail ? 'Ganti Gambar' : 'Pilih Gambar' }}
                        </p>
                        <p class="font-body-sm text-body-sm text-outline mt-1">JPG, PNG, atau WEBP · Maksimal 2MB</p>
                    </div>
                </label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="sr-only">
            </div>

            <div class="surface-card p-6 space-y-3">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg font-bold hover:bg-primary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $mode === 'create' ? 'Simpan Artikel' : 'Simpan Perubahan' }}
                </button>
                <a href="{{ route('admin.articles.index') }}"
                   class="w-full py-3 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                    Batal
                </a>
                @if ($mode === 'edit' && $article->status === 'published' && $article->slug)
                    <a href="{{ route('artikel.show', $article->slug) }}" target="_blank" rel="noopener"
                       class="w-full py-3 rounded-xl bg-surface-container text-on-surface font-label-lg text-label-lg font-bold hover:bg-surface-container-high transition-colors block text-center">
                        Lihat di Situs
                    </a>
                @endif
            </div>
        </aside>
    </form>
@endsection