<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzincutiController extends Controller
{
    public function create()
    {
        $mastercuti = DB::table('pengajuan_cuti')->orderBy('kode_cuti')->get();
        return view('izincuti.create', compact('mastercuti'));
    }


    public  function store(Request $request)
    {

        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $kode_cuti = $request->kode_cuti;
        $status = "c";
        $keterangan = $request->keterangan;

        // Mengambil bulan & tahun izin 
        $bulan = date("m", strtotime($tgl_izin_dari));
        $tahun = date("Y", strtotime($tgl_izin_dari));
        $thn = substr($tahun, 2, 2);
        $lastizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin_dari) ="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_izin_dari) ="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->first();

        // Membuat kode izin "iz1223001"
        $lastkodeizin = $lastizin != null ? $lastizin->kode_izin : "";
        $format = "iz" . $bulan . $thn;
        $kode_izin = buatkode($lastkodeizin, $format, 3);


        // Hitung jumlah hari yang diajukan
        $jmlhari = hitunghari($tgl_izin_dari, $tgl_izin_sampai);

        // Cek jumlah maksimal cuti
        $cuti = DB::table('pengajuan_cuti')->where('kode_cuti', $kode_cuti)->first();
        $jmlmaxcuti = $cuti->jml_hari;

        // Cek jumlah cuti yang sudah digunakan pada tahun aktif
        $cutidigunakan = DB::table('presensi')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('status', 'c')
            ->where('nik', $nik)
            ->count();
        // Sisa cuti
        $sisacuti = $jmlmaxcuti - $cutidigunakan;

        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'kode_cuti' => $kode_cuti,
            'status' => $status,
            'keterangan' => $keterangan,
        ];


        // Mengecek apakah ada sudah ada tanggal absen, atau izin
        $cekpresensi = DB::table('presensi')
            ->whereBetween('tgl_presensi', [$tgl_izin_dari, $tgl_izin_sampai])
            ->where('nik', '=', $nik);


        $datapresensi = $cekpresensi->get();

        if ($jmlhari > $sisacuti) {
            return redirect('/presensi/izin')->with(['error' => 'Jumlah hari melebihi jumlah maksimal cuti 1 tahun, sisa cuti anda adalah ' . $sisacuti . ' Hari']);
        } else if ($cekpresensi->count() > 0) {
            $blacklistdate = "";
            foreach ($datapresensi as $d) {
                $blacklistdate .= date("d-m-Y", strtotime($d->tgl_presensi)) . ", ";
            }

            return redirect('/presensi/izin')->with(['error' => 'Tanggal pengajuan  ' . $blacklistdate . '  sudah pernah diajukan.']);
        } else {
            $simpan = DB::table('pengajuan_izin')->insert($data);
            if ($simpan) {
                return redirect('/presensi/izin')->with(['success' => 'Data berhasil disimpan']);
            } else {
                return redirect('/presensi/izin')->with(['error' => 'Data gagal disimpan']);
            }
        }
    }

    public function edit($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $mastercuti = DB::table('pengajuan_cuti')->orderBy('kode_cuti')->get();
        return view('izincuti.edit', compact('dataizin', 'mastercuti'));
    }

    public  function update($kode_izin, Request $request)
    {

        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $keterangan = $request->keterangan;
        $kode_cuti = $request->kode_cuti;

        try {
            $data = [
                'tgl_izin_dari' => $tgl_izin_dari,
                'tgl_izin_sampai' => $tgl_izin_sampai,
                'keterangan' => $keterangan,
                'kode_cuti' => $kode_cuti

            ];
            DB::table('pengajuan_izin')
                ->where('kode_izin', $kode_izin)
                ->update($data);
            //Simpan File Surat Izin Dokter
            return redirect('/presensi/izin')->with(['success' => 'Data berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data gagal diupdate']);
        }
    }
}
