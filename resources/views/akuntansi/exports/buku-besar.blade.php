<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Besar</title>
    <style>
        body {
            color: #0f172a;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }

        h1, h2, h3, p { margin: 0; }

        .page-title {
            margin-bottom: 16px;
            text-align: center;
        }

        .page-title h1 {
            font-size: 15px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .muted { color: #64748b; }

        .account-block {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .account-header {
            background: #e0e7ff;
            border-bottom: 1px solid #a5b4fc;
            margin-bottom: 0;
            padding: 5px 8px;
        }

        .account-header h3 {
            font-size: 11px;
            color: #1e1b4b;
        }

        .account-header p {
            font-size: 9px;
            color: #4338ca;
            margin-top: 1px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #e2e8f0;
            padding: 4px 6px;
            vertical-align: top;
        }

        th {
            background: #f1f5f9;
            font-weight: bold;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .opening-row td { background: #fffbeb; color: #92400e; }

        .amount { text-align: right; white-space: nowrap; }

        .summary-row td {
            background: #eef2ff;
            border-top: 2px solid #6366f1;
            font-weight: bold;
        }

        .date-range {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $rupiah = fn ($v) => 'Rp ' . number_format((int) $v, 0, ',', '.');
    @endphp

    <div class="page-title">
        <h1>Perusahaan Jasa "Anugerah Sakti"</h1>
        <p class="muted">Buku Besar (Ledger — Format Saldo Tunggal)</p>
    </div>

    @if($startDate || $endDate)
        <p class="date-range">
            Filter Tanggal:
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : '—' }}
            s.d.
            {{ $endDate   ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y')   : '—' }}
        </p>
    @endif

    @foreach($allAccounts as $code => $account)
    <div class="account-block">
        <div class="account-header">
            <h3>[{{ $code }}] {{ $account['config']['name'] }}</h3>
            <p>{{ $account['config']['class'] }} &nbsp;|&nbsp; Saldo Normal: {{ ucfirst($account['config']['normal']) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:14%">Tanggal</th>
                    <th>Keterangan</th>
                    <th class="amount" style="width:16%">Debet (Rp)</th>
                    <th class="amount" style="width:16%">Kredit (Rp)</th>
                    <th class="amount" style="width:16%">Saldo Debet (Rp)</th>
                    <th class="amount" style="width:16%">Saldo Kredit (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($account['entries'] as $i => $e)
                <tr class="{{ $i === 0 ? 'opening-row' : '' }}">
                    <td>{{ $e['date'] }}</td>
                    <td>{{ $e['desc'] }}</td>
                    <td class="amount">{{ $e['debit']  > 0 ? $rupiah($e['debit'])  : '-' }}</td>
                    <td class="amount">{{ $e['credit'] > 0 ? $rupiah($e['credit']) : '-' }}</td>
                    <td class="amount">
                        {{ $account['config']['normal'] === 'debit'  ? $rupiah($e['balance']) : '-' }}
                    </td>
                    <td class="amount">
                        {{ $account['config']['normal'] === 'credit' ? $rupiah($e['balance']) : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="muted" style="text-align:center">Tidak ada transaksi</td>
                </tr>
                @endforelse

                <tr class="summary-row">
                    <td colspan="2">Total / Saldo Akhir</td>
                    <td class="amount">{{ $rupiah($account['summary']['totalDebit'])  }}</td>
                    <td class="amount">{{ $rupiah($account['summary']['totalCredit']) }}</td>
                    <td class="amount" colspan="2" style="text-align:right">
                        Saldo: {{ $rupiah($account['summary']['finalBalance']) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endforeach
</body>
</html>
