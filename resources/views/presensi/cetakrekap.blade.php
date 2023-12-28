<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rekap Karyawan</title>

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
            font-size: 18px;
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
            font-size: 10px;
        }

        .tabelpresensi tr td {
            border: 1px solid #000000;
            padding: 5px;
            font-size: 10px;

        }

        .foto {
            width: 30px;
            height: 30px
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4 landscape">

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
                        REKAP PRESENSI KARYAWAN<br>
                        {{-- Periode : {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} --}}
                        Periode : {{ $namabulan[$bulan] }} {{ $tahun }}
                    </span><br>
                    <span><i>Jl. Sisingamangaraja Kebayoran Baru Jakarta Selatan</i></span>
                </td>
            </tr>
        </table>

        <table class="tabelpresensi">
            <tr>
                <th rowspan="2">NIK</th>
                <th rowspan="2">Nama Karyawan</th>
                <th colspan="{{ $jmlhari }}">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                <th rowspan="2">H</th>
                <th rowspan="2">I</th>
                <th rowspan="2">S</th>
                <th rowspan="2">C</th>
                <th rowspan="2">A</th>
            </tr>
            <tr>
                @foreach ($rangetanggal as $d)
                    @if ($d != null)
                        <th>{{ date('d', strtotime($d)) }}</th>
                    @endif
                @endforeach
            </tr>
            @foreach ($rekap as $r)
                <tr>
                    <td>{{ $r->nik }}</td>
                    <td>{{ $r->nama_lengkap }}</td>

                    <?php
                    $jml_hadir = 0;
                    $jml_izin = 0;
                    $jml_sakit = 0;
                    $jml_cuti = 0;
                    $jml_alpa = 0;
                    
                    for ($i = 1; $i <= $jmlhari; $i++) {
                        $tgl = 'tgl_' . $i;
                        $datapresensi = explode('|', $r->$tgl);
                    
                        if ($r->$tgl != null && $datapresensi[2] != null) {
                            $status = $datapresensi[2];
                            echo "<td style='color: rgb(0, 0, 0); text-align: center; background-color: ";
                    
                            // Set warna berdasarkan status
                            if ($status == 'h') {
                                echo 'white';
                                $jml_hadir += 1;
                            } elseif ($status == 'i') {
                                echo 'yellow';
                                $jml_izin += 1;
                            } elseif ($status == 's') {
                                echo 'pink';
                                $jml_sakit += 1;
                            } else {
                                echo 'red'; // Warna merah untuk status kosong
                            }
                    
                            echo "'>$status</td>";
                        } else {
                            // Jika status kosong, beri warna merah
                            echo '<td style="background-color: rgb(224, 224, 224);"></td>';
                        }
                    }
                    
                    // Hitungan Alpa tidak termasuk kondisi kosong
                    $jml_alpa = $jmlhari - ($jml_hadir + $jml_izin + $jml_sakit);
                    ?>

                    <td>{{ !empty($jml_hadir) ? $jml_hadir : '' }}</td>
                    <td>{{ !empty($jml_izin) ? $jml_izin : '' }}</td>
                    <td>{{ !empty($jml_sakit) ? $jml_sakit : '' }}</td>
                    <td>{{ !empty($jml_cuti) ? $jml_cuti : '' }}</td>
                    <td>{{ !empty($jml_alpa) ? $jml_alpa : '' }}</td>



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
