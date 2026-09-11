<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - {{ $laporan->judul }}</title>
    <!-- Use bootstrap for quick styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');
        body { background: #e0e0e0; font-family: 'Times New Roman', Times, serif; }
        .print-container { background: #fff; width: 21cm; min-height: 29.7cm; margin: 2cm auto; padding: 2cm; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 5px; margin-bottom: 2px; }
        .kop-surat-inner { border-bottom: 1px solid #000; padding-bottom: 15px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; }
        .logo { width: 90px; }
        .kop-text { flex-grow: 1; text-align: center; }
        .kop-text h2 { margin: 0; font-weight: bold; font-size: 1.5rem; text-transform: uppercase; }
        .kop-text h3 { margin: 5px 0 0; font-weight: bold; font-size: 1.25rem; }
        .kop-text p { margin: 5px 0 0; font-size: 0.9rem; }
        
        .report-title { text-align: center; margin: 30px 0; font-weight: bold; font-size: 1.1rem; text-decoration: underline; text-transform: uppercase;}
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 12px; }
        th, td { border: 1px solid #000 !important; padding: 8px !important; text-align: center; }
        th { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
        
        .signature-area { margin-top: 50px; text-align: right; font-size: 0.95rem; }
        .signature-area p { margin: 0 0 70px; }
        .signature-area strong { text-decoration: underline; }
        
        @media print {
            body { background: #fff; margin: 0; padding: 0; }
            .print-container { width: 100%; min-height: auto; margin: 0; padding: 1cm; box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Floating Print Button -->
        <div class="no-print position-fixed top-0 end-0 m-3">
            <button onclick="window.print()" class="btn btn-primary d-flex align-items-center gap-2 shadow pr-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/></svg>
                Cetak Dokumen
            </button>
        </div>

        <div class="kop-surat">
            <div class="kop-surat-inner">
                <div class="logo-wrapper">
                    <img src="{{ asset('assets/logo.png') }}" class="logo" alt="Logo">
                </div>
                <div class="kop-text">
                    <h2>Sistem Pendukung Keputusan</h2>
                    <h3>Rekomendasi Layanan Siswa Berkebutuhan Khusus</h3>
                    <p>Jl. Pendidikan No. 123, Kota Cerdas, Telp. (021) 1234567<br>Website: www.spkdisabilitas.sch.id | Email: info@spkdisabilitas.sch.id</p>
                </div>
                <!-- Empty div for flex balance -->
                <div style="width: 90px;"></div>
            </div>
        </div>

        <div class="content-area">
            <div class="report-title">{{ $laporan->judul }}</div>
            
            <table class="table-border">
                <thead>
                    <tr>
                        <th width="5%">Rank</th>
                        <th>Nama Siswa</th>
                        <th>Kebutuhan Khusus</th>
                        @foreach($data['kriterias'] as $k)
                            <th>{{ $k['kode'] }}<br>({{ number_format($data['weights'][$k['id']]*100,0) }}%)</th>
                        @endforeach
                        <th>Total Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['results'] as $index => $row)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td style="text-align: left; padding-left: 10px !important;">{{ $row['siswa']['nama'] }}</td>
                            <td>{{ $row['siswa']['jenis_kebutuhan_khusus'] ?? '-' }}</td>
                            @foreach($data['kriterias'] as $k)
                                <td>{{ number_format($row['details'][$k['id']]['terbobot'], 4) }}</td>
                            @endforeach
                            <td><strong>{{ number_format($row['score'], 4) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <p style="font-size: 0.9rem; margin-top: -15px;"><em>*Keterangan: Skor di atas menggunakan metode pembobotan Analytic Hierarchy Process (AHP). Nilai pada masing-masing kriteria di kolom adalah nilai konversi terbobot.</em></p>
        </div>

        <div class="signature-area">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <br>
            <strong>{{ optional($laporan->author)->name ?? 'Guru / Wali Kelas' }}</strong><br>
            NIP: {{ optional($laporan->author)->nip ?? '-' }}
        </div>
    </div>
</body>
</html>
