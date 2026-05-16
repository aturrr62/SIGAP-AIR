<x-app-admin-layout>

{{-- Page Header --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.zona.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-headline">Detail Zona Wilayah</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $zona->nama_zona }}</p>
        </div>
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
@if(session('warning'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm">
        <span class="material-symbols-outlined text-amber-500 flex-shrink-0">warning</span>
        {{ session('warning') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Info Zona --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-navy-gradient px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-2xl">map</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-white">{{ $zona->nama_zona }}</h2>
                        <p class="text-xs text-blue-200 font-mono">{{ $zona->kode_zona }}</p>
                    </div>
                </div>
            </div>

            <div class="p-5 space-y-4">
                {{-- Status --}}
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Status</span>
                    @if($zona->is_active)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>

                {{-- Petugas Count --}}
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Jumlah Petugas</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $zona->petugas->count() }} orang</span>
                </div>

                {{-- Deskripsi --}}
                @if($zona->deskripsi)
                    <div class="pt-2 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $zona->deskripsi }}</p>
                    </div>
                @endif

                {{-- Timestamps --}}
                <div class="pt-2 border-t border-gray-100 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Dibuat</span>
                        <span class="text-xs text-gray-500">{{ $zona->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Diperbarui</span>
                        <span class="text-xs text-gray-500">{{ $zona->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="px-5 pb-5 flex gap-2">
                <a href="{{ route('admin.zona.edit', $zona->id) }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-50 text-amber-700 hover:bg-amber-100 text-sm font-semibold rounded-xl transition-colors duration-200">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Edit
                </a>
                <form id="delete-zona-{{ $zona->id }}"
                      action="{{ route('admin.zona.destroy', $zona->id) }}"
                      method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            onclick="if(confirm('Yakin ingin menghapus zona \'{{ $zona->nama_zona }}\'? Zona tidak dapat dihapus jika masih ada pengaduan aktif.')) { document.getElementById('delete-zona-{{ $zona->id }}').submit(); }"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 text-sm font-semibold rounded-xl transition-colors duration-200 cursor-pointer">
                        <span class="material-symbols-outlined text-lg" style="pointer-events:none;">delete</span>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Daftar Petugas --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#022448] text-xl">badge</span>
                    <h2 class="text-base font-semibold text-gray-900">Petugas di Zona Ini</h2>
                    <span class="inline-flex items-center justify-center w-5 h-5 bg-[#022448] text-white rounded-full text-xs font-bold">
                        {{ $zona->petugas->count() }}
                    </span>
                </div>
            </div>

            {{-- Daftar Petugas --}}
            @if($zona->petugas->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($zona->petugas as $petugas)
                        <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gradient-to-br from-[#022448] to-[#1e3a5f] rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs font-bold">
                                        {{ strtoupper(substr($petugas->user->name ?? 'P', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $petugas->user->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $petugas->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center py-12 text-center">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-gray-300 text-2xl">badge</span>
                    </div>
                    <p class="text-gray-500 font-medium text-sm">Belum ada petugas di zona ini</p>
                    <p class="text-gray-400 text-xs mt-1">Petakan petugas melalui halaman manajemen petugas</p>
                </div>
            @endif
        </div>
    </div>
</div>

</x-app-admin-layout>
