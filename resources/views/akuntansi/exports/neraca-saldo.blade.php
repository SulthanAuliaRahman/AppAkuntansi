<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Neraca Saldo</title>
    <style>
        body {
            color: #1e293b;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
        }

        h1, h2, h3, p { margin: 0; }

        .page-title {
            margin-bottom: 20px;
            text-align: center;
        }

        .page-title h1 {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .page-title h2 {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.02em;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background: #e2e8f0;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.03em;
        }

        td {
            background: #ffffff;
        }

        tr:nth-child(even) td {
            background: #f8fafc;
        }

        .code-cell {
            color: #64748b;
            font-weight: 500;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
            font-family: 'Courier New', monospace;
        }

        tfoot tr {
            background: #f1f5f9;
            font-weight: bold;
            border-top: 2px solid #94a3b8;
        }

        tfoot td {
            color: #1e40af;
        }

        tfoot .amount {
            color: #4f46e5;
            font-size: 12px;
        }
    </style>
</head>
<body>
    @php
        $rupiah = fn ($v) => 'Rp ' . number_format((int) $v, 0, ',', '.');
    @endphp

    <div class="page-title">
        <h1>Perusahaan Jasa "Anugerah Sakti"</h1>
        <h2>Neraca Saldo</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:15%">No. Perk</th>
                <th style="width:55%">Nama Perkiraan</th>
                <th class="amount" style="width:15%">Debet (Rp)</th>
                <th class="amount" style="width:15%">Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                <td class="code-cell">{{ $row['code'] }}</td>
                <td>{{ $row['config']['name'] }}</td>
                <td class="amount">
                    {{ $row['debit'] > 0 ? $rupiah($row['debit']) : '-' }}
                </td>
                <td class="amount">
                    {{ $row['credit'] > 0 ? $rupiah($row['credit']) : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;color:#94a3b8">
                    Tidak ada data neraca saldo
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;text-transform:uppercase;letter-spacing:0.02em">
                    Total Akhir Neraca Saldo
                </td>
                <td class="amount">{{ $rupiah($totalDebit) }}</td>
                <td class="amount">{{ $rupiah($totalCredit) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
