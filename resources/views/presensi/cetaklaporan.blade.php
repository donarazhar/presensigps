<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Laporan Presensi</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            font-weight: bold;
        }

        .tabeldatakaryawan {
            font-family: Arial, Helvetica, sans-serif;
            margin-top: 40px;
        }

        .tabeldatakaryawan td {
            padding: 1px;
        }

        .tabelpresensi {
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelpresensi tr th {
            border: 1px solid #000000;
            padding: 8px;
            background-color: rgb(230, 230, 230);
        }

        .tabelpresensi tr td {
            border: 1px solid #000000;
            padding: 5px;

        }

        .foto {
            width: 30px;
            height: 30px
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">
    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ':' . round($sisamenit2);
        }
    @endphp
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">
        <table style="width:100%">
            <tr>
                <td style="width:30px">
                    <img src="{{ asset('assets/img/logo.png') }}" width="100" height="100">
                </td>
                <td>
                    <span id="title">
                        YAYASAN PESANTREN ISLAM<br>
                        LAPORAN PRESENSI KARYAWAN<br>
                        {{-- Periode : {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} --}}
                        Periode : {{ $namabulan[$bulan] }} {{ $tahun }}
                    </span><br>
                    <span><i>Jl. Sisingamangaraja Kebayoran Baru Jakarta Selatan</i></span>
                </td>
            </tr>
        </table>
        <table class="tabeldatakaryawan">
            <tr>
                <td rowspan="6">
                    @php
                        $path = Storage::url('uploads/karyawan/' . $karyawan->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="100px" height="100px">
                </td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td>Nama Unit</td>
                <td>:</td>
                <td>{{ $karyawan->nama_dept }}</td>
            </tr>
            <tr>
                <td>No Handphone</td>
                <td>:</td>
                <td>{{ $karyawan->no_hp }}</td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Keterangan</th>
                <th>Jml Jam</th>
            </tr>
            @foreach ($presensi as $d)
                @php
                    $path_in = Storage::url('uploads/absensi/' . $d->foto_in);
                    $path_out = Storage::url('uploads/absensi/' . $d->foto_out);
                    $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                    <td>{{ $d->jam_in }}</td>
                    <td><img src="{{ url($path_in) }}" alt="" class="foto">
                    </td>
                    <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                    <td>
                        @if ($d->jam_out != null)
                            <img src="{{ url($path_out) }}" alt="" class="foto">
                        @else
                            <img src="{{ asset('assets/img/nofoto.png') }}" alt="" class="foto">
                        @endif
                    </td>
                    <td>
                        @if ($d->jam_in >= $d->jam_masuk)
                            Telat ({{ $jamterlambat }} menit)</span>
                        @else
                            On Time</span>
                        @endif
                    </td>
                    <td>
                        @if ($d->jam_out != null)
                            @php
                                $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                            @endphp
                        @else
                            @php
                                $jmljamkerja = 0;
                            @endphp
                        @endif
                        {{ $jmljamkerja }}
                        Jam
                    </td>
                </tr>
            @endforeach
        </table>
        <table width="100%">
            <tr>
                <td style="text-align: right; vertical-align:bottom" height="50px">
                    Jakarta, {{ date('d-m-Y') }}
                </td>
            </tr>

        </table>
        <table width="100%" style="margin-top: 10px">
            <tr>
                <td style="text-align: right; vertical-align:bottom" height="100px">
                    <u><b>Ngadiman, S.Pd</b></u><br>
                    <i>Kabag Kepegawaian</i>
                </td>
            </tr>

        </table>

    </section>

</body>

</html>
