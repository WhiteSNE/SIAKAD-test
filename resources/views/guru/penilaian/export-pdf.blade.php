<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.2; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .header { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .border th, .border td { border: 1px solid #000; padding: 6px; }
        .info-table td { border: none; padding: 2px 0; }
        .signature { margin-top: 40px; }
    </style>
</head>
<body>
    <div class="header text-center bold">
        <p>LEMBAR PENILAIAN SISWA PRAKTIK KERJA LAPANGAN</p>
        <p>SMKN NEGERI 4 MADIUN</p>
    </div>

    <table class="info-table">
        <tr><td width="20%">Nama Siswa</td><td width="2%">:</td><td>{{ $penilaian->siswa->nama_lengkap ?? '-' }}</td></tr>
        <tr><td>Tempat PKL</td><td>:</td><td>{{ $penilaian->siswa->dudi->nama_perusahaan ?? '-' }}</td></tr>
        <tr><td>Lama PKL</td><td>:</td><td>{{ $penilaian->lama_pkl ?? '-' }}</td></tr>
    </table>

    <table class="border">
        <thead>
            <tr style="background-color: #D9D9D9;">
                <th width="5%">NO</th>
                <th width="45%">CAPAIAN PEMBELAJARAN</th>
                <th width="25%">KETERcapaian YA/TIDAK</th>
                <th width="25%">DESKRIPSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penilaian->nilai as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item['capaian'] }}</td>
                <td class="text-center">{{ $item['ketercapaian'] }}</td>
                <td>{{ $item['deskripsi'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 11pt;">
        <p class="bold">Keterangan:</p>
        <p>A = 96 - 100,00 (sangat baik) | B = 86 - 95 (baik) | C = 80 - 85 (cukup)</p>
    </div>

    <table class="signature" width="100%">
        <tr>
            <td class="text-center" width="50%">Pembimbing Sekolah</td>
            <td class="text-center" width="50%">Instruktur Industri</td>
        </tr>
        <tr style="height: 80px;"><td><br><br><br></td><td></td></tr>
        <tr>
            <td class="text-center">({{ $penilaian->guru->NAMA ?? '..........................' }})</td>
            <td class="text-center">(..................................)</td>
        </tr>
    </table>
</body>
</html>