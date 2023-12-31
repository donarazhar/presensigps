<?php

namespace App\Http\Controllers;

use App\Models\Setjamkerja;
use App\Models\Setjamkerjadeptdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\TryCatch;

use function Laravel\Prompts\table;

class KonfigurasiController extends Controller
{
    public function lokasikantor()
    {
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

        return view('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request)
    {
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasi')->where('id', 1)
            ->update([
                'lokasi_kantor' => $lokasi_kantor,
                'radius' => $radius
            ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function jamkerja()
    {
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jamkerja', compact('jam_kerja'));
    }

    public function storejamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;
        $lintashari = $request->lintashari;


        $data = [
            'kode_jam_kerja' => $kode_jam_kerja,
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'lintashari' => $lintashari,
            'jam_pulang' => $jam_pulang
        ];
        try {
            DB::table('jam_kerja')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
            //throw $th;
        }
    }

    public function editjamkerja(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $jam_kerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        return view('konfigurasi.editjamkerja', compact('jam_kerja'));
    }

    public function updatejamkerja($kode_jam_kerja, Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;
        $lintashari = $request->lintashari;

        $data = [
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang,
            'lintashari' => $lintashari
        ];

        try {
            $update = DB::table('jam_kerja')
                ->where('kode_jam_kerja', $kode_jam_kerja)
                ->update($data);
            if ($update) {
                return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
            }
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
            //throw $th;
        }
    }


    public function deletejamkerja($kode_jam_kerja)
    {
        $hapus = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data  Gagal Dihapus']);
        }
    }

    public function setjamkerja($nik, Request $request)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cekjamkerja = DB::table('konfigurasi_jamkerja')->where('nik', $nik)->count();
        if ($cekjamkerja > 0) {
            $setjamkerja = DB::table('konfigurasi_jamkerja')->where('nik', $nik)->get();
            return view('konfigurasi.editsetjamkerja', compact('karyawan', 'jamkerja', 'setjamkerja'));
        } else {
            return view('konfigurasi.setjamkerja', compact('karyawan', 'jamkerja'));
        }
    }

    public function storesetjamkerja(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        for ($i = 0; $i < count($hari); $i++) {

            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }
        try {
            Setjamkerja::insert($data);
            // echo "Sukses";
            return redirect('/karyawan')->with(['success' => 'Jam Kerja berhasil disetting']);
        } catch (\Exception $e) {
            return redirect('/karyawan')->with(['warning' => 'Jam Kerja gagal disetting']);
            // dd($e);
            //throw $th;
        }
    }

    public function updatesetjamkerja(Request $request)
    {
        $nik = $request->nik;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        for ($i = 0; $i < count($hari); $i++) {

            $data[] = [
                'nik' => $nik,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }
        // Proses untuk menghilangkan data sebelumnya untuk diupdate data baru pada database
        DB::beginTransaction();
        try {
            DB::table('konfigurasi_jamkerja')->where('nik', $nik)->delete();
            Setjamkerja::insert($data);
            DB::commit();
            return redirect('/karyawan')->with(['success' => 'Jam Kerja berhasil disetting']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/karyawan')->with(['warning' => 'Jam Kerja gagal disetting']);
        }
    }

    public function jamkerjadept()
    {
        $jamkerjadept = DB::table('konfigurasi_jk_dept')
            ->join('cabang', 'konfigurasi_jk_dept.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('departemen', 'konfigurasi_jk_dept.kode_dept', '=', 'departemen.kode_dept')
            ->get();
        return view('konfigurasi.jamkerjadept', compact('jamkerjadept'));
    }
    public function setjamkerjadept()
    {
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cabang = DB::table('cabang')->get();
        $departemen = DB::table('departemen')->get();
        return view('konfigurasi.setjamkerjadept', compact('jamkerja', 'cabang', 'departemen'));
    }

    public function storesetjamkerjadept(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $kode_dept = $request->kode_dept;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        $kode_jk_dept = "j" . $kode_cabang . $kode_dept;

        DB::beginTransaction();
        try {
            //code...Menyimpan Dara ke table konfigurasi jk dept
            DB::table('konfigurasi_jk_dept')->insert([
                'kode_jk_dept' => $kode_jk_dept,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept
            ]);


            // Data yang akan dimasukkan ketable Set Jam Kerja Detail
            for ($i = 0; $i < count($hari); $i++) {

                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i]
                ];
            }

            // Memasukkan data model Setjamkerjadeptdetail
            Setjamkerjadeptdetail::insert($data);
            DB::commit();
            return  redirect('/konfigurasi/jamkerjadept')->with(['success' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return  redirect('/konfigurasi/jamkerjadept')->with(['warning' => 'Data gagal disimpan']);
            //throw $th;
        }
    }
    public function editjamkerjadept($kode_jk_dept)
    {

        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cabang = DB::table('cabang')->get();
        $departemen = DB::table('departemen')->get();

        // Menampilkan data sesuai dengan kode jk dept yang dipilih
        $jamkerjadept = DB::table('konfigurasi_jk_dept')->where('kode_jk_dept', $kode_jk_dept)->first();
        $jamkerjadept_detail = DB::table('konfigurasi_jk_dept_detail')->where('kode_jk_dept', $kode_jk_dept)->get();

        return view('konfigurasi.editjamkerjadept', compact('jamkerja', 'cabang', 'departemen', 'jamkerjadept', 'jamkerjadept_detail'));
    }

    public function updatesetjamkerjadept($kode_jk_dept, Request $request)
    {

        // Menangkap hari dan jam kerja pada kode_jk_dept tersebut
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        DB::beginTransaction();
        try {

            // Menghapus data jam kerja sebelumnya
            DB::table('konfigurasi_jk_dept_detail')->where('kode_jk_dept', $kode_jk_dept)->delete();

            // Data yang akan dimasukkan ketable Set Jam Kerja Detail
            for ($i = 0; $i < count($hari); $i++) {

                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i]
                ];
            }

            // Memasukkan data model Setjamkerjadeptdetail
            Setjamkerjadeptdetail::insert($data);
            DB::commit();
            return  redirect('/konfigurasi/jamkerjadept')->with(['success' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return  redirect('/konfigurasi/jamkerjadept')->with(['warning' => 'Data gagal disimpan']);
            //throw $th;
        }
    }

    public function showjamkerjadept($kode_jk_dept)
    {
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cabang = DB::table('cabang')->get();
        $departemen = DB::table('departemen')->get();

        // Menampilkan data sesuai dengan kode jk dept yang dipilih
        $jamkerjadept = DB::table('konfigurasi_jk_dept')->where('kode_jk_dept', $kode_jk_dept)->first();
        $jamkerjadept_detail = DB::table('konfigurasi_jk_dept_detail')
            ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('kode_jk_dept', $kode_jk_dept)->get();
        return view('konfigurasi.showjamkerjadept', compact('jamkerja', 'cabang', 'departemen', 'jamkerjadept', 'jamkerjadept_detail'));
    }

    public function deletejamkerjadept($kode_jk_dept)
    {
        try {
            DB::table('konfigurasi_jk_dept')->where('kode_jk_dept', $kode_jk_dept)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data  Gagal Dihapus']);
        }
    }
}
