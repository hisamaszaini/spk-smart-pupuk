<table>
    <thead>
        <tr></tr>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center; font-size: 16pt;">
                Distribusi Pupuk {{ $periodeName }} Kelompok Tani Subur di Desa Menang, Kecamatan Jambon
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-size: 12pt; border: 1px solid #000000; background-color: #f3f4f6;">No</th>
            <th style="font-size: 12pt; border: 1px solid #000000; background-color: #f3f4f6;">Nama</th>
            <th style="font-size: 12pt; border: 1px solid #000000; background-color: #f3f4f6;">Luas (Ha)</th>
            <th style="font-size: 12pt; border: 1px solid #000000; background-color: #f3f4f6;">Produktivitas (Ton/Ha)</th>
            <th style="font-size: 12pt; border: 1px solid #000000; background-color: #f3f4f6;">Status Kepemilikan Lahan</th>
            <th style="font-size: 12pt; border: 1px solid #000000; background-color: #f3f4f6;">Alokasi Pupuk (Kg)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alokasis as $index => $alokasi)
        <tr>
            <td style="font-size: 12pt; border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
            <td style="font-size: 12pt; border: 1px solid #000000;">{{ $alokasi->petani->nama_petani }}</td>
            <td style="font-size: 12pt; border: 1px solid #000000; text-align: center;">{{ $alokasi->petani->luas_lahan }}</td>
            <td style="font-size: 12pt; border: 1px solid #000000; text-align: center;">{{ $alokasi->petani->produktivitas_lahan }}</td>
            <td style="font-size: 12pt; border: 1px solid #000000;">{{ $alokasi->petani->status_kepemilikan_lahan }}</td>
            <td style="font-size: 12pt; border: 1px solid #000000; text-align: right;">{{ number_format($alokasi->jumlah_pupuk, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>