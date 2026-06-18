<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            color: #0f172a;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.45;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .page-title {
            margin-bottom: 18px;
            text-align: center;
        }

        .page-title h1 {
            font-size: 17px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }

        .section-title {
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 10px;
            padding-bottom: 6px;
            text-align: center;
        }

        .section-title h2 {
            font-size: 14px;
            margin: 3px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border-bottom: 1px solid #e2e8f0;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background: #f1f5f9;
            font-weight: bold;
            text-align: left;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .group-row td {
            background: #f8fafc;
            color: #334155;
            font-weight: bold;
            text-transform: uppercase;
        }

        .total-row td {
            border-top: 1px solid #94a3b8;
            font-weight: bold;
        }

        .grand-total td {
            background: #eef2ff;
            border-top: 2px solid #6366f1;
            font-size: 12px;
            font-weight: bold;
        }

        .negative {
            color: #be123c;
        }

        .muted {
            color: #64748b;
        }

        .two-columns {
            width: 100%;
        }

        .two-columns td {
            border: 0;
            padding: 0 10px 0 0;
            width: 50%;
        }
    </style>
</head>
<body>
    @php
        $rupiah = fn ($value) => 'Rp ' . number_format((int) $value, 0, ',', '.');
    @endphp

    <div class="page-title">
        <h1>Perusahaan Jasa "Anugerah Sakti"</h1>
        <p class="muted">Laporan Keuangan Setelah Penyesuaian</p>
    </div>

    <div class="section">
        <div class="section-title">
            <p>Perusahaan Jasa "Anugerah Sakti"</p>
            <h2>Laporan Laba Rugi</h2>
            <p class="muted">Untuk Periode yang Berakhir 30 April 2008</p>
        </div>

        <table>
            <tbody>
                <tr class="group-row">
                    <td colspan="2">Pendapatan</td>
                </tr>
                @forelse($revenues as $revenue)
                    <tr>
                        <td>{{ $revenue['name'] }}</td>
                        <td class="amount">{{ $rupiah($revenue['amount']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="muted">Tidak ada pendapatan</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td>Total Pendapatan</td>
                    <td class="amount">{{ $rupiah($totalRev) }}</td>
                </tr>

                <tr class="group-row">
                    <td colspan="2">Beban-Beban Operasional</td>
                </tr>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense['name'] }}</td>
                        <td class="amount">{{ $rupiah($expense['amount']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="muted">Tidak ada beban</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td>Total Beban Operasional</td>
                    <td class="amount">{{ $rupiah($totalExp) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>{{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</td>
                    <td class="amount">{{ $rupiah(abs($netIncome)) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            <p>Perusahaan Jasa "Anugerah Sakti"</p>
            <h2>Laporan Perubahan Modal</h2>
            <p class="muted">Untuk Periode yang Berakhir 30 April 2008</p>
        </div>

        <table>
            <tbody>
                <tr>
                    <td>Modal Awal</td>
                    <td class="amount">{{ $rupiah($initialCap) }}</td>
                </tr>
                <tr>
                    <td>{{ $netIncome >= 0 ? '(+) Laba Bersih' : '(-) Rugi Bersih' }}</td>
                    <td class="amount">{{ $rupiah(abs($netIncome)) }}</td>
                </tr>
                <tr class="negative">
                    <td>(-) Prive</td>
                    <td class="amount">{{ $rupiah($prive) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Perubahan Neto Modal Pemilik</td>
                    <td class="amount">{{ $rupiah($capIncrease) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Modal Pemilik Akhir</td>
                    <td class="amount">{{ $rupiah($finalCap) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            <p>Perusahaan Jasa "Anugerah Sakti"</p>
            <h2>Neraca</h2>
            <p class="muted">Per 30 April 2008</p>
        </div>

        <table>
            <tbody>
                <tr class="group-row">
                    <td colspan="2">Aktiva</td>
                </tr>
                @forelse($assets as $asset)
                    @php
                        $isContraAsset = stripos($asset['name'], 'akum') !== false
                            || stripos($asset['name'], 'penyusutan') !== false
                            || ($asset['normal'] ?? 'debit') === 'credit';
                    @endphp
                    <tr>
                        <td>{{ $asset['name'] }}</td>
                        <td class="amount">
                            {{ $isContraAsset ? '(' . $rupiah(abs($asset['amount'])) . ')' : $rupiah($asset['amount']) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="muted">Tidak ada aktiva</td>
                    </tr>
                @endforelse
                <tr class="grand-total">
                    <td>Total Aktiva</td>
                    <td class="amount">{{ $rupiah($assetTotals['total']) }}</td>
                </tr>

                <tr class="group-row">
                    <td colspan="2">Kewajiban</td>
                </tr>
                @forelse($liabilities as $liability)
                    <tr>
                        <td>{{ $liability['name'] }}</td>
                        <td class="amount">{{ $rupiah($liability['amount']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="muted">Tidak ada kewajiban</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td>Total Kewajiban</td>
                    <td class="amount">{{ $rupiah($totalLiabilities) }}</td>
                </tr>

                <tr class="group-row">
                    <td colspan="2">Modal Pemilik</td>
                </tr>
                <tr>
                    <td>Modal Akhir</td>
                    <td class="amount">{{ $rupiah($finalCap) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Total Pasiva</td>
                    <td class="amount">{{ $rupiah($totalPassives) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
