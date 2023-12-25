<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzinsakitController extends Controller
{
    public function create()
    {
        return view('sakit.create');
    }

    public  function store(Request $request)
    {

        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "s";
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
        // dd($kode_izin);

        // Membuat kode number random
        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        } else {
            $sid = null;
        }
        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan,
            'doc_sid' => $sid

        ];

        // Mengecek apakah ada sudah ada tanggal absen, atau izin
        $cekpresensi = DB::table('presensi')
            ->whereBetween('tgl_presensi', [$tgl_izin_dari, $tgl_izin_sampai])
            ->where('nik', '=', $nik);


        $datapresensi = $cekpresensi->get();
        if ($cekpresensi->count() > 0) {
            $blacklistdate = "";
            foreach ($datapresensi as $d) {
                $blacklistdate .= date("d-m-Y", strtotime($d->tgl_presensi)) . ", ";
            }

            return redirect('/presensi/izin')->with(['error' => 'Tanggal pengajuan  ' . $blacklistdate . '  sudah pernah diajukan.']);
        } else {
            $simpan = DB::table('pengajuan_izin')->insert($data);
            if ($simpan) {
                //Simpan File Surat Izin Dokter
                if ($request->hasFile('sid')) {
                    $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
                    $folderPath = "public/uploads/sid/";
                    $request->file('sid')->storeAs($folderPath, $sid);
                }
                return redirect('/presensi/izin')->with(['success' => 'Data berhasil disimpan']);
            } else {
                return redirect('/presensi/izin')->with(['error' => 'Data gagal disimpan']);
            }
        }
    }

    public function edit($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('sakit.edit', compact('dataizin'));
    }

    public  function update($kode_izin, Request $request)
    {

        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $keterangan = $request->keterangan;
        $doc_sid = $request->doc_sidedit;

        // Membuat kode number random
        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        } else {
            $sid = $doc_sid;
        }
        $data = [
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'keterangan' => $keterangan,
            'doc_sid' => $sid

        ];

        try {
            DB::table('pengajuan_izin')
                ->where('kode_izin', $kode_izin)
                ->update($data);
            //Simpan File Surat Izin Dokter
            if ($request->hasFile('sid')) {
                $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
                $folderPath = "public/uploads/sid/";
                $request->file('sid')->storeAs($folderPath, $sid);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data berhasil diupdate']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data gagal diupdate']);
        }
    }
}
