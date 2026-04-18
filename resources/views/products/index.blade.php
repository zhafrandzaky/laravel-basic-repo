<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Manajemen Produk</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-blue-600">Laravel CRUD</p>
                    <h1 class="mt-2 text-3xl font-bold">Manajemen Produk</h1>
                    <p class="mt-2 text-sm text-slate-600">Tambah, lihat, ubah, dan hapus data produk dalam satu halaman.</p>
                </div>

                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Reset Form
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1fr,1.2fr]">
                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold">Tambah Produk</h2>
                    <p class="mt-1 text-sm text-slate-500">Form ini memakai Form Request untuk validasi input.</p>

                    <form action="{{ route('products.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nama Produk</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Contoh: Mouse Wireless">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Deskripsi singkat produk">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="price_display" class="mb-2 block text-sm font-medium text-slate-700">Harga</label>
                                <input id="price_display" type="text" inputmode="numeric" data-price-display data-target="price" value="{{ old('price') ? number_format((float) old('price'), 0, ',', '.') : '' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="0">
                                <input type="hidden" name="price" id="price" value="{{ old('price') }}">
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="stock" class="mb-2 block text-sm font-medium text-slate-700">Stok</label>
                                <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', 0) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="0">
                                @error('stock')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                            Simpan Produk
                        </button>
                    </form>
                </section>

                <section class="space-y-6">
                    @if ($editableProduct)
                        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-amber-200">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold">Ubah Produk</h2>
                                    <p class="mt-1 text-sm text-slate-500">Sedang mengubah data {{ $editableProduct->name }}.</p>
                                </div>

                                <a href="{{ route('products.index') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">Batal ubah</a>
                            </div>

                            <form action="{{ route('products.update', $editableProduct) }}" method="POST" class="mt-6 space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="edit_name" class="mb-2 block text-sm font-medium text-slate-700">Nama Produk</label>
                                    <input id="edit_name" name="name" type="text" value="{{ old('name', $editableProduct->name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                                </div>

                                <div>
                                    <label for="edit_description" class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</label>
                                    <textarea id="edit_description" name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">{{ old('description', $editableProduct->description) }}</textarea>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="edit_price_display" class="mb-2 block text-sm font-medium text-slate-700">Harga</label>
                                        <input id="edit_price_display" type="text" inputmode="numeric" data-price-display data-target="edit_price" value="{{ number_format((float) old('price', $editableProduct->price), 0, ',', '.') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                                        <input type="hidden" name="price" id="edit_price" value="{{ old('price', $editableProduct->price) }}">
                                    </div>

                                    <div>
                                        <label for="edit_stock" class="mb-2 block text-sm font-medium text-slate-700">Stok</label>
                                        <input id="edit_stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $editableProduct->stock) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100">
                                    </div>
                                </div>

                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600">
                                    Update Produk
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold">Daftar Produk</h2>
                                <p class="mt-1 text-sm text-slate-500">Menampilkan seluruh data produk yang tersimpan.</p>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $products->count() }} item</span>
                        </div>

                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Nama</th>
                                        <th class="px-4 py-3 font-semibold">Deskripsi</th>
                                        <th class="px-4 py-3 font-semibold">Harga</th>
                                        <th class="px-4 py-3 font-semibold">Stok</th>
                                        <th class="px-4 py-3 font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($products as $product)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-slate-900">{{ $product->name }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $product->description ?: '-' }}</td>
                                            <td class="px-4 py-3 text-slate-600">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $product->stock }}</td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('products.index', ['edit' => $product->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Ubah</a>

                                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data produk.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <script>
            document.querySelectorAll('[data-price-display]').forEach(function (display) {
                const hiddenId = display.dataset.target;
                const hidden   = document.getElementById(hiddenId);

                function toRaw(val) {
                    return val.replace(/\./g, '');
                }

                function format(raw) {
                    if (!raw) return '';
                    return parseInt(raw, 10).toLocaleString('id-ID');
                }

                display.addEventListener('input', function () {
                    const raw     = toRaw(this.value).replace(/\D/g, '');
                    this.value    = raw ? format(raw) : '';
                    hidden.value  = raw || '';
                });

                // Sync saat pertama load (untuk old() value)
                if (hidden.value) {
                    display.value = format(hidden.value);
                }
            });
        </script>
    </body>
</html>
