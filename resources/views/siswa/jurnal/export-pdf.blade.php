<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jurnal PKL - {{ $siswa->nama_lengkap }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.3; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .header { margin-bottom: 20px; }
        .judul { font-size: 12pt; margin: 0; }
        
        /* Tabel Info Siswa (Tanpa Border) */
        .table-info { width: 100%; margin-bottom: 20px; border: none; }
        .table-info td { padding: 2px 0; vertical-align: top; }
        
        /* Tabel Jurnal (Dengan Border) */
        .table-jurnal { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-jurnal th, .table-jurnal td { border: 1px solid #000; padding: 8px; }
        .table-jurnal th { background-color: #E0E0E0; font-weight: bold; }
        
        /* Tabel Tanda Tangan */
        .table-signature { width: 100%; margin-top: 40px; border: none; }
        .table-signature td { text-align: center; width: 50%; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="header text-center bold">
        <p class="judul">JURNAL KEGIATAN PRAKTIK KERJA LAPANGAN</p>
        <p class="judul">SMK NEGERI 4 MADIUN</p>
    </div>

    <table class="table-info">
        <tr>
            <td width="25%">Nama Siswa</td>
            <td width="2%">:</td>
            <td width="73%">{{ $siswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>:</td>
            <td>{{ $siswa->nisn }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Program Keahlian</td>
            <td>:</td>
            <td>{{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tempat Magang</td>
            <td>:</td>
            <td>{{ $siswa->dudi->nama_perusahaan ?? '-' }}</td>
        </tr>
    </table>

    <table class="table-jurnal">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">Tanggal</th>
                <th width="70%">Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurnals as $index => $jurnal)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, j F Y') }}</td>
                <td>{{ $jurnal->deskripsi_kegiatan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;" class="text-right">
        Madiun, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
    </div>

    <table class="table-signature">
        <tr>
            <td>Pembimbing Sekolah</td>
            <td>Instruktur Industri</td>
        </tr>
        <tr>
            <td class="signature-space"></td>
            <td class="signature-space"></td>
        </tr>
        <tr>
            <td>({{ $siswa->guru->NAMA ?? '..................................' }})</td>
            <td>(..................................)</td>
        </tr>
    </table>
</body>
</html>