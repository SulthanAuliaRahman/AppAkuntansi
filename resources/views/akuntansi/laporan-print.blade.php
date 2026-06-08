<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
        }
        .page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 15mm;
            background: white;
            page-break-after: always;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .company-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .report-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .report-date {
            font-size: 11px;
            color: #333;
        }
        .content {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .subsection {
            margin-bottom: 12px;
            margin-left: 10px;
        }
        .subsection-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
            text-decoration: underline;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 3px 5px;
            border-bottom: 1px dotted #ccc;
            font-size: 11px;
        }
        .row.total {
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
            font-weight: bold;
            padding: 4px 5px;
        }
        .row.subtotal {
            font-weight: bold;
            background: #f9f9f9;
            border-bottom: 1px solid #000;
        }
        .row.grand-total {
            font-weight: bold;
            background: #f0f0f0;
            border: 1.5px solid #000;
            padding: 5px;
        }
        .label {
            text-align: left;
            flex: 1;
        }
        .amount {
            text-align: right;
            min-width: 100px;
            font-weight: 500;
        }
        .balance-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .assets, .liabilities {
            page-break-inside: avoid;
        }
        .assets-section-title, .liabilities-section-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
            text-decoration: underline;
        }
        .footer {
            position: absolute;
            bottom: 15mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        @media print {
            body { margin: 0; padding: 0; }
            .page { box-shadow: none; margin: 0; page-break-after: always; }
        }
    </style>
</head>
<body>
    <!-- HALAMAN 1: LAPORAN LABA RUGI -->
    <div class="page">
        <div class="header">
            <div class="company-name">Perusahaan Jasa "Anugerah Sakti"</div>
            <div class="report-title">Laporan Laba Rugi</div>
            <div class="report-date">
                Untuk Periode yang Berakhir
                @if($endDate)
                    {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                @else
                    30 April 2008
                @endif
            </div>
        </div>

        <div class="content">
            <div class="section-title">Pendapatan</div>
            <div class="subsection">
                @forelse($revenues as $code => $revenue)
                    <div class="row">
                        <span class="label">{{ $revenue['name'] ?? 'Akun ' . $code }}</span>
                        <span class="amount">Rp {{ number_format($revenue['balance'], 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="row"><span class="label" style="color: #ccc;">-</span></div>
                @endforelse
                <div class="row subtotal">
                    <span class="label">Total Pendapatan</span>
                    <span class="amount">Rp {{ number_format($totalRev, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="section-title" style="margin-top: 15px;">Beban Operasional</div>
            <div class="subsection">
                @forelse($expenses as $code => $expense)
                    <div class="row">
                        <span class="label">{{ $expense['name'] ?? 'Akun ' . $code }}</span>
                        <span class="amount">Rp {{ number_format($expense['balance'], 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="row"><span class="label" style="color: #ccc;">-</span></div>
                @endforelse
                <div class="row subtotal">
                    <span class="label">Total Beban Operasional</span>
                    <span class="amount">Rp {{ number_format($totalExp, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="row grand-total" style="margin-top: 15px;">
                <span class="label">LABA BERSIH (NET INCOME)</span>
                <span class="amount">Rp {{ number_format($netIncome, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>

    <!-- HALAMAN 2: LAPORAN PERUBAHAN MODAL -->
    <div class="page">
        <div class="header">
            <div class="company-name">Perusahaan Jasa "Anugerah Sakti"</div>
            <div class="report-title">Laporan Perubahan Modal</div>
            <div class="report-date">
                Untuk Periode yang Berakhir
                @if($endDate)
                    {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                @else
                    30 April 2008
                @endif
            </div>
        </div>

        <div class="content">
            <div class="subsection" style="margin-left: 0;">
                <div class="row">
                    <span class="label">Modal Awal</span>
                    <span class="amount">Rp {{ number_format($initialCap, 0, ',', '.') }}</span>
                </div>
                <div class="row">
                    <span class="label">(+) Laba Bersih</span>
                    <span class="amount">Rp {{ number_format($netIncome, 0, ',', '.') }}</span>
                </div>
                <div class="row">
                    <span class="label">(-) Prive (Pengambilan Pribadi)</span>
                    <span class="amount">Rp {{ number_format($prive, 0, ',', '.') }}</span>
                </div>
                <div class="row subtotal">
                    <span class="label">Kenaikan Neto Modal Pemilik</span>
                    <span class="amount">Rp {{ number_format($capIncrease, 0, ',', '.') }}</span>
                </div>
                <div class="row grand-total" style="margin-top: 15px;">
                    <span class="label">MODAL PEMILIK (AKHIR)</span>
                    <span class="amount">Rp {{ number_format($finalCap, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>

    <!-- HALAMAN 3: NERACA -->
    <div class="page">
        <div class="header">
            <div class="company-name">Perusahaan Jasa "Anugerah Sakti"</div>
            <div class="report-title">Neraca (Balance Sheet)</div>
            <div class="report-date">
                Per
                @if($endDate)
                    {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                @else
                    30 April 2008
                @endif
            </div>
        </div>

        <div class="content">
            <div class="balance-section">
                <!-- AKTIVA -->
                <div class="assets">
                    <div class="assets-section-title">AKTIVA</div>
                    @forelse($assets as $code => $asset)
                        @if(stripos($asset['name'] ?? '', 'akum') === false)
                            <div class="row">
                                <span class="label">{{ $asset['name'] ?? 'Akun ' . $code }}</span>
                                <span class="amount">Rp {{ number_format($asset['balance'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @empty
                        <div class="row"><span class="label" style="color: #ccc;">-</span></div>
                    @endforelse

                    @foreach($assets as $code => $asset)
                        @if(stripos($asset['name'] ?? '', 'akum') !== false)
                            <div class="row">
                                <span class="label">{{ $asset['name'] ?? 'Akun ' . $code }}</span>
                                <span class="amount">(Rp {{ number_format(abs($asset['balance']), 0, ',', '.') }})</span>
                            </div>
                        @endif
                    @endforeach

                    <div class="row grand-total" style="margin-top: 10px;">
                        <span class="label">TOTAL AKTIVA</span>
                        <span class="amount">Rp {{ number_format($assetTotals['total'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- PASIVA -->
                <div class="liabilities">
                    <div class="liabilities-section-title">PASIVA</div>

                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold; font-size: 10px; margin-bottom: 4px;">Kewajiban Jangka Pendek</div>
                        @forelse($liabilities as $code => $liability)
                            <div class="row">
                                <span class="label">{{ $liability['name'] ?? 'Akun ' . $code }}</span>
                                <span class="amount">Rp {{ number_format($liability['balance'], 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="row"><span class="label" style="color: #ccc;">-</span></div>
                        @endforelse
                        <div class="row subtotal" style="border-bottom: 1px dotted #ccc; margin-bottom: 8px;">
                            <span class="label">Total Kewajiban</span>
                            <span class="amount">Rp {{ number_format($totalLiabilities, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <div style="font-weight: bold; font-size: 10px; margin-bottom: 4px;">Modal Pemilik</div>
                        <div class="row">
                            <span class="label">Modal Akhir</span>
                            <span class="amount">Rp {{ number_format($finalCap, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="row grand-total" style="margin-top: 10px;">
                        <span class="label">TOTAL PASIVA</span>
                        <span class="amount">Rp {{ number_format($totalPassives, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
