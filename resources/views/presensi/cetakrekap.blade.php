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
                <th colspan="31">Tanggal</th>
                <th rowspan="2">TH</th>
                <th rowspan="2">TT</th>
            </tr>
            <tr>
                <?php 
                for ($i = 1; $i <= 31; $i++) {
                ?>
                <th>{{ $i }}</th>
                <?php  
                }
                ?>
            </tr>
            <tr>
                @foreach ($rekap as $d)
            <tr>
                <td>{{ $d->nik }}</td>
                <td>{{ $d->nama_lengkap }}</td>

                <?php 
                $totalhadir =0;
                $totaltelat =0;
                for ($i = 1; $i <= 31; $i++) {
                        $tgl = "tgl_".$i;

                        if(empty($d->$tgl)){
                            $hadir = ['',''];
                            $totalhadir += 0;
                        }else {
                        $hadir = explode("-",$d->$tgl);
                        $totalhadir += 1;
                        if($hadir[0] > "07:00:00"){
                            $totaltelat +=1;
                        }
                        }
                ?>
                <td>
                    <span style="color: {{ $hadir[0] > '07:00:00' ? 'red' : '' }}">{{ $hadir[0] }}</span>
                    <span style="color: {{ $hadir[1] < '15:00:00' ? 'red' : '' }}">{{ $hadir[1] }}</span>

                </td>
                <?php  
                }
                ?>
                <td>{{ $totalhadir }}</td>
                <td>{{ $totaltelat }}</td>

            </tr>
            @endforeach
            </tr>
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
