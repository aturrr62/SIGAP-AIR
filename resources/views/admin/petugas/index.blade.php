<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Manajemen Petugas Teknis</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data petugas dan pemetaan wilayah zona</p>
        </div>
        <a href="{{ route('admin.petugas.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-navy-gradient text-white font-semibold rounded-xl shadow-lg shadow-[#022448]/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
            <span class="material-symbols-outlined text-xl">person_add</span>
            Tambah Petugas
        </a>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
        <span class="material-symbols-outlined text-emerald-500 flex-shrink-0">check_circle</span>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
        <span class="material-symbols-outlined text-red-500 flex-shrink-0">error</span>
        {{ session('error') }}
    </div>
@endif
@if($errors->has('error'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
        <span class="material-symbols-outlined text-red-500 flex-shrink-0">error</span>
        {{ $errors->first('error') }}
    </div>
@endif

{{-- ======================== --}}
{{-- FILTER BAR               --}}
{{-- ======================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" action="{{ route('admin.petugas.index') }}" id="filter-form">
        <div class="flex flex-col sm:flex-row gap-3">

            {{-- Search --}}
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                <input type="text" name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau NIP petugas..."
                       class="w-full h-11 pl-11 pr-4 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 placeholder:text-gray-400">
            </div>

            {{-- Filter Zona Wilayah --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl pointer-events-none">map</span>
                <select name="zona_id"
                        onchange="document.getElementById('filter-form').submit()"
                        class="h-11 pl-11 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 appearance-none cursor-pointer min-w-[180px]">
                    <option value="">Semua Zona</option>
                    <option value="tanpa_zona" {{ request('zona_id') === 'tanpa_zona' ? 'selected' : '' }}>
                        — Tanpa Zona
                    </option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nama_zona }}
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-base pointer-events-none">expand_more</span>
            </div>

            {{-- Filter Status --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl pointer-events-none">badge</span>
                <select name="status"
                        onchange="document.getElementById('filter-form').submit()"
                        class="h-11 pl-11 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent focus:bg-white transition-all duration-200 appearance-none cursor-pointer min-w-[170px]">
                    <option value="">Semua Status</option>
                    <option value="tersedia"    {{ request('status') === 'tersedia'    ? 'selected' : '' }}>Tersedia</option>
                    <option value="sibuk"       {{ request('status') === 'sibuk'       ? 'selected' : '' }}>Sibuk</option>
                    <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-base pointer-events-none">expand_more</span>
            </div>

            {{-- Tombol Filter & Reset --}}
            <button type="submit"
                    class="h-11 px-5 bg-navy-gradient text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined text-lg">filter_list</span>
                Filter
            </button>
            @if(request()->hasAny(['search', 'zona_id', 'status']))
                <a href="{{ route('admin.petugas.index') }}"
                   class="h-11 px-4 bg-gray-100 text-gray-600 hover:bg-gray-200 text-sm font-semibold rounded-xl transition-colors flex items-center gap-2 whitespace-nowrap">
                    <span class="material-symbols-outlined text-lg">close</span>
                    Reset
                </a>
            @endif
        </div>

        {{-- Active Filter Chips --}}
        @if(request()->hasAny(['search', 'zona_id', 'status']))
            <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-400 font-medium">Filter aktif:</span>
                @if(request('search'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-[#022448] rounded-full text-xs font-medium">
                        <span class="material-symbols-outlined text-sm">search</span>
                        "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 hover:text-red-500">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </a>
                    </span>
                @endif
                @if(request('zona_id'))
                    @php
                        $selectedZona = $zonas->firstWhere('id', request('zona_id'));
                        $zonaLabel = request('zona_id') === 'tanpa_zona' ? 'Tanpa Zona' : ($selectedZona?->nama_zona ?? 'Zona #' . request('zona_id'));
                    @endphp
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-medium">
                        <span class="material-symbols-outlined text-sm">map</span>
                        {{ $zonaLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['zona_id' => null]) }}" class="ml-1 hover:text-red-500">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </a>
                    </span>
                @endif
                @if(request('status'))
                    @php
                        $statusLabel = ['tersedia' => 'Tersedia', 'sibuk' => 'Sibuk', 'tidak_aktif' => 'Tidak Aktif'][request('status')] ?? request('status');
                        $statusColor = ['tersedia' => 'bg-emerald-50 text-emerald-700', 'sibuk' => 'bg-amber-50 text-amber-700', 'tidak_aktif' => 'bg-gray-100 text-gray-600'][request('status')] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="inline-flex items-center gap-1 px-3 py-1 {{ $statusColor }} rounded-full text-xs font-medium">
                        <span class="material-symbols-outlined text-sm">badge</span>
                        {{ $statusLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 hover:text-red-500">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </a>
                    </span>
                @endif
            </div>
        @endif
    </form>
</div>

{{-- ======================== --}}
{{-- DATA TABLE               --}}
{{-- ======================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Info Hasil --}}
    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
        <p class="text-xs text-gray-500">
            Menampilkan <strong class="text-gray-700">{{ $petugas->firstItem() ?? 0 }}–{{ $petugas->lastItem() ?? 0 }}</strong>
            dari <strong class="text-gray-700">{{ $petugas->total() }}</strong> petugas
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Petugas</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">NIP</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Zona Wilayah</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($petugas as $index => $p)
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                        {{-- No --}}
                        <td class="px-6 py-4 text-gray-500 font-medium text-sm">
                            {{ $petugas->firstItem() + $index }}
                        </td>

                        {{-- Petugas Info --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gradient-to-br from-[#022448] to-[#1e3a5f] rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($p->user->name ?? 'P', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $p->user->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $p->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- NIP --}}
                        <td class="px-6 py-4">
                            @if($p->nip)
                                <span class="font-mono text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded-lg border border-gray-200">
                                    {{ $p->nip }}
                                </span>
                            @else
                                <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>

                        {{-- Zona Wilayah --}}
                        <td class="px-6 py-4">
                            @if($p->zona)
                                <a href="{{ route('admin.zona.show', $p->zona->id) }}"
                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-[#022448] hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors">
                                    <span class="material-symbols-outlined text-sm">map</span>
                                    {{ $p->zona->nama_zona }}
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-400 rounded-lg text-xs font-medium">
                                    <span class="material-symbols-outlined text-sm">location_off</span>
                                    Belum dipetakan
                                </span>
                            @endif
                        </td>

                        {{-- Status Ketersediaan --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusConfig = [
                                    'tersedia'    => ['bg-emerald-50 text-emerald-700', 'check_circle'],
                                    'sibuk'       => ['bg-amber-50 text-amber-700',     'pending'],
                                    'tidak_aktif' => ['bg-gray-100 text-gray-500',      'cancel'],
                                ];
                                $statusLabel = [
                                    'tersedia'    => 'Tersedia',
                                    'sibuk'       => 'Sibuk',
                                    'tidak_aktif' => 'Tidak Aktif',
                                ];
                                [$statusClass, $statusIcon] = $statusConfig[$p->status_tersedia] ?? ['bg-gray-100 text-gray-500', 'help'];
                                $label = $statusLabel[$p->status_tersedia] ?? ucwords(str_replace('_', ' ', $p->status_tersedia ?? '-'));
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $statusClass }} rounded-full text-xs font-semibold">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $statusIcon }}</span>
                                {{ $label }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Edit --}}
                                <a href="{{ route('admin.petugas.edit', $p) }}"
                                   class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                   title="Edit data petugas">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                {{-- Nonaktifkan --}}
                                <form id="destroy-petugas-{{ $p->id }}"
                                      action="{{ route('admin.petugas.destroy', $p) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="if(confirm('Nonaktifkan petugas {{ addslashes($p->user->name ?? '') }}?\n\nPetugas dengan tugas aktif tidak dapat dinonaktifkan.')) { document.getElementById('destroy-petugas-{{ $p->id }}').submit(); }"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                            title="Nonaktifkan petugas">
                                        <span class="material-symbols-outlined text-xl" style="pointer-events:none;">person_off</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-gray-300 text-3xl">badge</span>
                                </div>
                                @if(request()->hasAny(['search', 'zona_id', 'status']))
                                    <p class="text-gray-500 font-medium">Tidak ada petugas yang sesuai filter</p>
                                    <p class="text-gray-400 text-sm mt-1">Coba ubah kriteria filter atau <a href="{{ route('admin.petugas.index') }}" class="text-[#022448] hover:underline font-medium">tampilkan semua</a></p>
                                @else
                                    <p class="text-gray-500 font-medium">Belum ada data petugas</p>
                                    <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Petugas" untuk menambahkan data baru</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($petugas->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $petugas->withQueryString()->links() }}
        </div>
    @endif
</div>

</x-app-admin-layout>
