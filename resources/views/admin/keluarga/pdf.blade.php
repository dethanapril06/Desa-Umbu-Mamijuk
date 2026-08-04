<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="color-scheme" content="light">
    <title>Kartu Keluarga - {{ $keluarga->no_kk }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 30pt 40pt 25pt 40pt;
        }

        html, body {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        body {
            padding: 5pt 10pt;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-uppercase { text-transform: uppercase; }
        .fw-bold { font-weight: bold; }

        /* Header Document */
        .header-title {
            text-align: center;
            margin-bottom: 8px;
        }

        .header-title h1 {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        .header-title h2 {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 2px 0 0 0;
            padding: 0;
        }

        /* Information Grid */
        .info-table {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
        }

        .info-table td {
            vertical-align: top;
            padding: 1px 2px;
            font-size: 8.5px;
        }

        .info-label {
            font-weight: bold;
            width: 130px;
        }

        .info-colon {
            width: 8px;
            font-weight: bold;
        }

        .info-val {
            font-weight: bold;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            font-size: 8px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .data-table td {
            height: 14px;
        }

        .sub-header th {
            font-size: 7.5px;
            font-weight: normal;
            background-color: #fafafa;
        }

        .table-title {
            font-size: 8.5px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        /* Footer / Signatures */
        .footer-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: top;
            text-align: center;
            font-size: 8.5px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 35px;
            text-transform: uppercase;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .watermark-box {
            border: 1px dashed #aaa;
            padding: 4px;
            font-size: 7px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header Judul -->
    <div class="header-title">
        <h1>KARTU KELUARGA</h1>
        <h2>No. {{ $keluarga->no_kk }}</h2>
    </div>

    <!-- Info Identitas KK -->
    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="info-label">Nama Kepala Keluarga</td>
                        <td class="info-colon">:</td>
                        <td class="info-val text-uppercase">{{ $kepalaKeluarga ? $kepalaKeluarga->nama_lengkap : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Alamat</td>
                        <td class="info-colon">:</td>
                        <td class="info-val text-uppercase">{{ $keluarga->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">RT / RW</td>
                        <td class="info-colon">:</td>
                        <td class="info-val">RT {{ sprintf('%03d', $keluarga->rtRw->no_rt ?? 0) }} / RW {{ sprintf('%03d', $keluarga->rtRw->no_rw ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Desa / Kelurahan</td>
                        <td class="info-colon">:</td>
                        <td class="info-val text-uppercase">{{ $profilDesa->nama_desa ?? ($keluarga->rtRw->dusun->nama ?? '-') }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="info-label">Kecamatan</td>
                        <td class="info-colon">:</td>
                        <td class="info-val text-uppercase">{{ $profilDesa->kecamatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Kabupaten / Kota</td>
                        <td class="info-colon">:</td>
                        <td class="info-val text-uppercase">{{ $profilDesa->kabupaten ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Kode Pos</td>
                        <td class="info-colon">:</td>
                        <td class="info-val">{{ $keluarga->kode_pos ?? ($profilDesa->kode_pos ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Provinsi</td>
                        <td class="info-colon">:</td>
                        <td class="info-val text-uppercase">{{ $profilDesa->provinsi ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @php
        $maxRows = 7;
        
        $pendidikanMap = [
            'tidak_sekolah' => 'TIDAK / BELUM SEKOLAH',
            'sd' => 'SD / SEDERAJAT',
            'smp' => 'SMP / SEDERAJAT',
            'sma' => 'SMA / SEDERAJAT',
            'd1' => 'D1',
            'd2' => 'D2',
            'd3' => 'D3',
            's1' => 'S1 / DIPLOMA IV',
            's2' => 'S2',
            's3' => 'S3',
        ];

        $hubunganMap = [
            'kepala_keluarga' => 'KEPALA KELUARGA',
            'istri' => 'ISTRI',
            'anak' => 'ANAK',
            'menantu' => 'MENANTU',
            'cucu' => 'CUCU',
            'orang_tua' => 'ORANG TUA',
            'mertua' => 'MERTUA',
            'famili_lain' => 'FAMILI LAIN',
            'lainnya' => 'LAINNYA',
        ];

        $kawinMap = [
            'belum_kawin' => 'BELUM KAWIN',
            'kawin' => 'KAWIN',
            'cerai_hidup' => 'CERAI HIDUP',
            'cerai_mati' => 'CERAI MATI',
        ];
    @endphp

    <!-- TABEL 1: DATA ANGGOTA KELUARGA (DEMOGRAFI & IDENTITAS) -->
    <div class="table-title">I. DATA ANGGOTA KELUARGA</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 170px;">Nama Lengkap</th>
                <th style="width: 110px;">NIK</th>
                <th style="width: 70px;">Jenis Kelamin</th>
                <th style="width: 90px;">Tempat Lahir</th>
                <th style="width: 75px;">Tanggal Lahir</th>
                <th style="width: 70px;">Agama</th>
                <th style="width: 110px;">Pendidikan</th>
                <th>Jenis Pekerjaan</th>
            </tr>
            <tr class="sub-header">
                <th>(1)</th>
                <th>(2)</th>
                <th>(3)</th>
                <th>(4)</th>
                <th>(5)</th>
                <th>(6)</th>
                <th>(7)</th>
                <th>(8)</th>
                <th>(9)</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < $maxRows; $i++)
                @php $p = $keluarga->penduduk->get($i); @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-left text-uppercase fw-bold">{{ $p ? $p->nama_lengkap : '' }}</td>
                    <td class="text-center">{{ $p ? $p->nik : '' }}</td>
                    <td class="text-center text-uppercase">{{ $p ? ($p->jenis_kelamin == 'laki-laki' ? 'LAKI-LAKI' : 'PEREMPUAN') : '' }}</td>
                    <td class="text-left text-uppercase">{{ $p ? $p->tempat_lahir : '' }}</td>
                    <td class="text-center">{{ $p && $p->tanggal_lahir ? $p->tanggal_lahir->format('d-m-Y') : '' }}</td>
                    <td class="text-center text-uppercase">{{ $p ? $p->agama : '' }}</td>
                    <td class="text-left text-uppercase">{{ $p ? ($pendidikanMap[$p->pendidikan_terakhir] ?? strtoupper($p->pendidikan_terakhir)) : '' }}</td>
                    <td class="text-left text-uppercase">{{ $p ? $p->pekerjaan : '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <!-- TABEL 2: STATUS PERKAWINAN, HUBUNGAN, ORANG TUA -->
    <div class="table-title">II. STATUS PERKAWINAN, HUBUNGAN KELUARGA, KEWARGANEGARAAN, & ORANG TUA</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" rowspan="2">No</th>
                <th style="width: 90px;" rowspan="2">Status Perkawinan</th>
                <th style="width: 130px;" rowspan="2">Status Hubungan Dalam Keluarga</th>
                <th style="width: 90px;" rowspan="2">Kewarganegaraan</th>
                <th colspan="2">Dokumen Imigrasi</th>
                <th colspan="2">Nama Orang Tua</th>
            </tr>
            <tr>
                <th style="width: 80px;">No. Paspor</th>
                <th style="width: 80px;">No. KITAP / KITAS</th>
                <th style="width: 120px;">Ayah</th>
                <th>Ibu</th>
            </tr>
            <tr class="sub-header">
                <th>(1)</th>
                <th>(10)</th>
                <th>(11)</th>
                <th>(12)</th>
                <th>(13)</th>
                <th>(14)</th>
                <th>(15)</th>
                <th>(16)</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < $maxRows; $i++)
                @php $p = $keluarga->penduduk->get($i); @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center text-uppercase">{{ $p ? ($kawinMap[$p->status_perkawinan] ?? strtoupper($p->status_perkawinan)) : '' }}</td>
                    <td class="text-center text-uppercase fw-bold">{{ $p ? ($hubunganMap[$p->status_hubungan_keluarga] ?? strtoupper($p->status_hubungan_keluarga)) : '' }}</td>
                    <td class="text-center text-uppercase">{{ $p ? $p->kewarganegaraan : '' }}</td>
                    <td class="text-center text-uppercase">{{ $p ? ($p->no_paspor ?? '-') : '' }}</td>
                    <td class="text-center text-uppercase">{{ $p ? ($p->no_kitas_kitap ?? '-') : '' }}</td>
                    <td class="text-left text-uppercase">{{ $p ? $p->nama_ayah : '' }}</td>
                    <td class="text-left text-uppercase">{{ $p ? $p->nama_ibu : '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <!-- TANDA TANGAN / FOOTER -->
    <table class="footer-table">
        <tr>
            <td style="width: 35%;">
                <div>Dikeluarkan Tanggal: {{ $keluarga->tanggal_terdaftar ? $keluarga->tanggal_terdaftar->format('d-m-Y') : now()->format('d-m-Y') }}</div>
                <div class="signature-title" style="margin-top: 5px;">KEPALA KELUARGA</div>
                <div class="signature-name">{{ $kepalaKeluarga ? $kepalaKeluarga->nama_lengkap : '................................' }}</div>
                <div style="font-size: 8px; font-style: italic; margin-top: 2px;">Tanda Tangan / Cap Ibu Jari</div>
            </td>
            <td style="width: 30%; vertical-align: middle;">
                <div class="watermark-box">
                    <div>Dokumen Cetak Resmi</div>
                    <div style="font-weight: bold; margin-top: 2px;">SISTEM INFORMASI DESA</div>
                    <div>{{ strtoupper($profilDesa->nama_desa ?? 'DESA UMBU MAMIJUK') }}</div>
                </div>
            </td>
            <td style="width: 35%;">
                <div>{{ $profilDesa->nama_desa ?? 'Umbu Mamijuk' }}, {{ now()->isoFormat('D MMMM Y') }}</div>
                <div class="signature-title" style="margin-top: 5px;">KEPALA DESA {{ strtoupper($profilDesa->nama_desa ?? 'DESA') }}</div>
                <div class="signature-name">{{ $kepalaDesa ? $kepalaDesa->nama : ($profilDesa->kepala_desa ?? '................................') }}</div>
                @if($kepalaDesa && $kepalaDesa->nip)
                    <div style="font-size: 8px;">NIP. {{ $kepalaDesa->nip }}</div>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
