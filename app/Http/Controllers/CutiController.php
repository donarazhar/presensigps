<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use function PHPUnit\Framework\returnSelf;

class CutiController extends Controller
{
    public function index()
    {
        $cuti = DB::table('pengajuan_cuti')->orderBy('kode_cuti', 'asc')->get();
        return view('cuti.index', compact('cuti'));
    }

    public function store(Request $request)
    {
        $kode_cuti = $request->kode_cuti;
        $nama_cuti = $request->nama_cuti;
        $jml_hari = $request->jml_hari;

        $cekcuti = DB::table('pengajuan_cuti')->where('kode_cuti', $kode_cuti)->count();
        if ($cekcuti > 0) {
            return Redirect::back()->with(['warning' => 'Kode cuti sudah ada']);
        }
        try {
            DB::table('pengajuan_cuti')->insert([
                'kode_cuti' => $kode_cuti,
                'nama_cuti' => $nama_cuti,
                'jml_hari' => $jml_hari
            ]);
            return Redirect::back()->with(['success' => 'Data berhasi disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data berhasi disimpan' . $e->getMessage()]);
            //throw $th;
        }
    }

    public function edit(Request $request)
    {
        $kode_cuti = $request->kode_cuti;
        $cuti = DB::table('pengajuan_cuti')->where('kode_cuti', $kode_cuti)->first();
        return view('cuti.edit', compact('cuti'));
    }

    public function update($kode_cuti, Request $request)
    {
        $nama_cuti = $request->nama_cuti;
        $jml_hari = $request->jml_hari;
        $data = [
            'nama_cuti' => $nama_cuti,
            'jml_hari' => $jml_hari
        ];
        $update = DB::table('pengajuan_cuti')->where('kode_cuti', $kode_cuti)->update($data);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data  Gagal Diupdate']);
        }
    }

    public function delete($kode_cuti)
    {
        $hapus = DB::table('pengajuan_cuti')->where('kode_cuti', $kode_cuti)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data  Gagal Dihapus']);
        }
    }
}
