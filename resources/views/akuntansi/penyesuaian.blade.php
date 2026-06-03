@extends('layouts.akuntansi')

@section('content')
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @include('akuntansi.partials.navigation')

    <div class="space-y-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">4. Jurnal Penyesuaian (Adjusting Entries)</h2>
                <p class="text-sm text-slate-500">Pencatatan data penyesuaian per 30 April 2008 agar menggambarkan kondisi riil keuangan</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-slate-500">Otomatisasi Penyesuaian:</span>
                <form method="POST" action="{{ route('akuntansi.penyesuaian.toggle') }}">
                    @csrf
                    <button type="submit"
                        class="{{ $adjustmentsEnabled ? 'bg-rose-600 hover:bg-rose-500' : 'bg-emerald-600 hover:bg-emerald-500' }} text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-md">
                        {{ $adjustmentsEnabled ? 'Matikan Penyesuaian' : 'Aktifkan Penyesuaian' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Keterangan -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4 lg:col-span-1">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Keterangan Data Penyesuaian</h3>
                <div class="space-y-3.5 text-xs text-slate-600 max-h-[450px] overflow-y-auto custom-scrollbar pr-1">
                    @foreach ([
                        ['title' => '1. Sisa Perlengkapan Kantor',    'body' => 'Perlengkapan yang tersisa adalah Rp 5.500.000. Selisih dari saldo awal + pembelian diakui sebagai beban terpakai.'],
                        ['title' => '2. Penyusutan Peralatan',         'body' => 'Metode Garis Lurus, 3 tahun, residu 0. Nilai Peralatan Rp 81.000.000. Penyusutan 1 bulan = Rp 2.250.000.'],
                        ['title' => '3. Sewa Ruangan Terpakai',        'body' => 'Sewa ruangan dibayar di muka Rp 1.500.000 untuk 1 tahun. Terpakai 1 bulan (April) = Rp 125.000.'],
                        ['title' => '4. Piutang Pendapatan',           'body' => 'Pendapatan yang masih harus ditagih ke pelanggan (accrued revenue) sebesar Rp 1.000.000.'],
                        ['title' => '5. Pendapatan Iklan Dimuka',      'body' => 'Saldo Iklan Diterima Dimuka Rp 1.000.000 diakui sebagai pendapatan iklan.'],
                        ['title' => '6. Akrual Beban Gaji',            'body' => 'Beban gaji karyawan yang belum dibayarkan per akhir April sebesar Rp 2.342.400.'],
                    ] as $info)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1">
                        <span class="font-bold text-indigo-700">{{ $info['title'] }}</span>
                        <p>{{ $info['body'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tabel AJE -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden lg:col-span-2">
                <div class="p-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-800">Ayat Jurnal Penyesuaian (AJE)</span>
                    <span class="text-xs font-semibold {{ $adjustmentsEnabled ? 'text-emerald-600' : 'text-rose-500' }}">
                        {{ $adjustmentsEnabled ? 'Aktif (Data Terhitung)' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100/50 text-slate-600 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200">
                                <th class="py-3 px-4">Akun Rekening</th>
                                <th class="py-3 px-4">Ref</th>
                                <th class="py-3 px-4 text-right">Debet (Rp)</th>
                                <th class="py-3 px-4 text-right">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-100">
                            @if ($adjustmentsEnabled)
                                @foreach ($ajeRows as $row)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 px-4 font-bold text-slate-700">
                                        <div>{{ $accounts[$row->kode_akun_debet]['name'] }}</div>
                                        <div class="pl-4 font-medium text-slate-500 italic mt-1">{{ $accounts[$row->kode_akun_kredit]['name'] }}</div>
                                        <span class="text-[10px] text-indigo-500 block mt-1">{{ $row->keterangan }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-500">
                                        <div>{{ $row->kode_akun_debet }}</div>
                                        <div class="pl-4 mt-1">{{ $row->kode_akun_kredit }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-slate-800">@rupiah($row->jumlah)</td>
                                    <td class="py-3 px-4 text-right font-bold text-slate-800">
                                        <div class="h-4"></div>
                                        <div>@rupiah($row->jumlah)</div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 font-medium">
                                        <i class="fa-solid fa-circle-info text-2xl mb-2 block"></i>
                                        Aktifkan simulasi penyesuaian di samping untuk melihat rincian posting jurnal.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 font-bold border-t border-slate-200 text-slate-800">
                                <td colspan="2" class="py-3 px-4 text-right uppercase text-[10px]">Total Penyesuaian</td>
                                <td class="py-3 px-4 text-right text-indigo-700">{{ $adjustmentsEnabled ? 'Rp '.number_format($totalAJE,0,',','.') : 'Rp 0' }}</td>
                                <td class="py-3 px-4 text-right text-indigo-700">{{ $adjustmentsEnabled ? 'Rp '.number_format($totalAJE,0,',','.') : 'Rp 0' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
