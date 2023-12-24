<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class PresensiController extends Controller
{

    public function gethari()
    {
        $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;
            case 'Mon':
                $hari_ini = "Senin";
                break;
            case 'Tue':
                $hari_ini = "Selasa";
                break;
            case 'Wed':
                $hari_ini = "Rabu";
                break;
            case 'Thu':
                $hari_ini = "Kamis";
                break;
            case 'Fri':
                $hari_ini = "Jumat";
                break;
            case 'Sat':
                $hari_ini = "Sabtu";
                break;
            default:
                $hari_ini = "Tidak diketahui";
                break;
        }
        return $hari_ini;
    }

    public function create()
    {
        $hariini = date("Y-m-d");
        //Mendapatkan nama hari pada dashboard absen karyawan 
        $namahari = $this->gethari();
        $nik = Auth::guard('karyawan')->user()->nik;
        //Mengecek karyawan tersebut di Departemen apa
        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        // $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();

        // Mendapatkan jam kerja perorangan sesuai dengan nik yang login
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->where('hari', $namahari)->first();


        if ($jamkerja == null) {
            // Mendapatkan jam kerja perdepartemen sesuai dengan nik yang login
            $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('kode_dept', $kode_dept)
                ->where('kode_cabang', $kode_cabang)
                ->where('hari', $namahari)->first();
        }


        if ($jamkerja == null) {
            return view('presensi.notifjadwal');
        } else {
            return view('presensi.create', compact('cek', 'lok_kantor', 'jamkerja'));
        }
    }

    public  function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        //Mengecek karyawan tersebut di Departemen apa
        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        $lok = explode(",", $lok_kantor->lokasi_cabang);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        //Cek jam kerja karyawan
        $namahari = $this->gethari();
        // Mendapatkan jam kerja perorangan sesuai dengan nik yang login
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->where('hari', $namahari)->first();


        if ($jamkerja == null) {
            // Mendapatkan jam kerja perdepartemen sesuai dengan nik yang login
            $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('kode_dept', $kode_dept)
                ->where('kode_cabang', $kode_cabang)
                ->where('hari', $namahari)->first();
        }


        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }

        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "_" . $tgl_presensi . "_" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($radius > $lok_kantor->radius_cabang) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " Meter Dari Kantor|radius";
        } else {

            // Membuat kondisi validasi absen sesuai dengan jam kerja
            if ($cek > 0) {

                // Validasi untuk jam pulang
                if ($jam < $jamkerja->jam_pulang) {
                    echo "error|Maaf Belum Waktunya Jam Pulang|in";
                } else {
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                    if ($update) {
                        echo "success|Terima Kasih, Hati-Hati Dijalan|out";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal Absen Hubungi Admin|out";
                    }
                }
            } else {
                // Validasi waktu awal jam masuk belum
                if ($jam < $jamkerja->awal_jam_masuk) {
                    echo "error|Maaf Belum Waktunya Melakukan Presensi|in";

                    // Validasi akhir jam masuk
                } else if ($jam > $jamkerja->akhir_jam_masuk) {
                    echo "error|Maaf Waktu Untuk Presensi Sudah Habis|in";
                } else {
                    $data = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => 'h'

                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Selamat Bekerja|in";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf Gagal Absen Hubungi Admin|in";
                    }
                }
            }
        }
    }

    //Validasi Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        // Validasi untuk file yang diupload
        // $request->validate([
        //     'foto' => 'required|image|mimes:png,jpg|max:1024'
        // ]);

        // Proses Upload Foto
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        // Proses Edit Profile
        if (!empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto,
            ];
        }

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update) {
            // Proses simpan update foto
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    public  function izin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;

        if (!empty($request->bulan) && !empty($request->tahun)) {
            $dataizin = DB::table('pengajuan_izin')
                ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
                ->orderBy('tgl_izin_dari', 'desc')
                ->where('nik', $nik)
                ->whereRaw('MONTH(tgl_izin_dari)="' . $request->bulan . '"')
                ->whereRaw('YEAR(tgl_izin_dari)="' . $request->tahun . '"')
                ->get();
        } else {
            $dataizin = DB::table('pengajuan_izin')
                ->leftJoin('pengajuan_cuti', 'pengajuan_izin.kode_cuti', '=', 'pengajuan_cuti.kode_cuti')
                ->orderBy('tgl_izin_dari', 'desc')
                ->where('nik', $nik)->limit(5)->orderBy('tgl_izin_dari', 'DESC')
                ->get();
        }

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('presensi.izin', compact('dataizin', 'namabulan'));
    }

    public  function buatizin()
    {
        return view('presensi.buatizin');
    }

    public  function storeizin(Request $request)
    {

        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan,
        ];
        $simpan = DB::table('pengajuan_izin')->insert($data);
        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data berhasil disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['errror' => 'Data gagal disimpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }
    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'karyawan.kode_dept', 'nama_dept', 'jam_masuk', 'nama_jam_kerja', 'jam_masuk', 'jam_pulang')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    // Fungsi menampilkan peta
    public  function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    // Laporan
    public  function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public  function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();

        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //Fungsi header dengan mengirimkan row data excel
            header("Content-type:application/vnd-ms-excel");
            //Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Karyawan $time.xls");
            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
        }

        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    // Rekap Presensi
    public  function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        // Untuk mendapatkan tanggal dari dan tanggal sampai untuk rekap presensi
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // Menampilkan range tanggal dari 01 sampai tanggal akhir
        while (strtotime($dari) <= strtotime($sampai)) {
            $rangetanggal[] = $dari;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }

        $jmlhari = count($rangetanggal);
        $lastrange = $jmlhari - 1;
        $sampai = $rangetanggal[$lastrange];

        if ($jmlhari == 30) {
            array_push($rangetanggal, NULL);
        } else if ($jmlhari == 29) {
            array_push($rangetanggal, NULL, NULL);
        } else if ($jmlhari == 28) {
            array_push($rangetanggal, NULL, NULL, NULL);
        }

        $query = Karyawan::query();
        $query->selectRaw("karyawan.nik, nama_lengkap, jabatan, presensi.tgl_1, presensi.tgl_2, presensi.tgl_3, presensi.tgl_4, presensi.tgl_5, presensi.tgl_6, presensi.tgl_7, presensi.tgl_8, presensi.tgl_9, presensi.tgl_10, presensi.tgl_11, presensi.tgl_12, presensi.tgl_13, presensi.tgl_14, presensi.tgl_15, presensi.tgl_16, presensi.tgl_17, presensi.tgl_18, presensi.tgl_19, presensi.tgl_20, presensi.tgl_21, presensi.tgl_22, presensi.tgl_23, presensi.tgl_24, presensi.tgl_25, presensi.tgl_26, presensi.tgl_27, presensi.tgl_28, presensi.tgl_29, presensi.tgl_30, presensi.tgl_31");

        $query->leftJoin(
            DB::raw("( 
    SELECT presensi.nik,
    MAX(IF(tgl_presensi = '$rangetanggal[0]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_1,
    
    MAX(IF(tgl_presensi = '$rangetanggal[1]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_2,

    MAX(IF(tgl_presensi = '$rangetanggal[2]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_3,
    
    MAX(IF(tgl_presensi = '$rangetanggal[3]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_4,  
    
    MAX(IF(tgl_presensi = '$rangetanggal[4]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_5,
    
    MAX(IF(tgl_presensi = '$rangetanggal[5]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_6,
    
    MAX(IF(tgl_presensi = '$rangetanggal[6]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_7,
    
    MAX(IF(tgl_presensi = '$rangetanggal[7]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_8,
    
    MAX(IF(tgl_presensi = '$rangetanggal[8]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_9,
    
    MAX(IF(tgl_presensi = '$rangetanggal[9]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_10,
    
    MAX(IF(tgl_presensi = '$rangetanggal[10]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_11,
    
    MAX(IF(tgl_presensi = '$rangetanggal[11]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_12,
    
    MAX(IF(tgl_presensi = '$rangetanggal[12]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_13,
    
    MAX(IF(tgl_presensi = '$rangetanggal[13]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_14,
    
    MAX(IF(tgl_presensi = '$rangetanggal[14]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_15,
    
    MAX(IF(tgl_presensi = '$rangetanggal[15]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_16,
    
    MAX(IF(tgl_presensi = '$rangetanggal[16]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_17,
    
    MAX(IF(tgl_presensi = '$rangetanggal[17]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_18,
    
    MAX(IF(tgl_presensi = '$rangetanggal[18]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_19,
    
    MAX(IF(tgl_presensi = '$rangetanggal[19]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_20,
    
    MAX(IF(tgl_presensi = '$rangetanggal[20]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_21,
    
    MAX(IF(tgl_presensi = '$rangetanggal[21]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_22,
    
    MAX(IF(tgl_presensi = '$rangetanggal[22]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_23,
    
    MAX(IF(tgl_presensi = '$rangetanggal[23]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_24,
    
    MAX(IF(tgl_presensi = '$rangetanggal[24]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_25,
    
    MAX(IF(tgl_presensi = '$rangetanggal[25]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_26,
    
    MAX(IF(tgl_presensi = '$rangetanggal[26]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_27,
    
    MAX(IF(tgl_presensi = '$rangetanggal[27]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_28,
    
    MAX(IF(tgl_presensi = '$rangetanggal[28]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_29,
    
    MAX(IF(tgl_presensi = '$rangetanggal[29]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_30,
    
    MAX(IF(tgl_presensi = '$rangetanggal[30]', CONCAT(
        IFNULL(jam_in, 'NA'), '|', IFNULL(jam_out, 'NA'), '|', IFNULL(presensi.status, 'NA'), '|', IFNULL(nama_jam_kerja, 'NA'), '|', IFNULL(jam_masuk, 'NA'), '|', IFNULL(jam_pulang, 'NA'), '|', IFNULL(presensi.kode_izin, 'NA'), '|', IFNULL(keterangan, 'NA'), '|', ''), NULL)) AS tgl_31
    
    FROM presensi
    LEFT JOIN jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
    LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
    WHERE tgl_presensi BETWEEN '$rangetanggal[0]' AND '$sampai'
    GROUP BY nik
    )presensi"),
            function ($join) {
                $join->on('karyawan.nik', '=', 'presensi.nik');
            }
        );

        $query->orderBy('nama_lengkap');
        $rekap = $query->get();


        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //Fungsi header dengan mengirimkan row data excel
            header("Content-type:application/vnd-ms-excel");
            //Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }

        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap', 'rangetanggal', 'jmlhari'));
    }

    public  function izinsakit(Request $request)
    {

        $query = PengajuanIzin::query();
        $query->select('kode_izin', 'tgl_izin_dari', 'tgl_izin_sampai', 'pengajuan_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin_dari', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }
        if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin_dari', 'desc');
        $izinsakit = $query->paginate(10);
        $izinsakit->appends($request->all());

        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public  function approveizinsakit(Request $request)
    {
        // Mendapatkan nilai status approved
        $status_approved = $request->status_approved;
        $kode_izin = $request->kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $nik = $dataizin->nik;
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        $status = $dataizin->status;
        DB::beginTransaction();
        try {
            if ($status_approved == 1) {
                while (strtotime($tgl_dari) <= strtotime($tgl_sampai)) {

                    DB::table('presensi')->insert([
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_dari,
                        'status' => $status,
                        'kode_izin' => $kode_izin
                    ]);
                    $tgl_dari = date("Y-m-d", strtotime("+1 days", strtotime($tgl_dari)));
                }
            }

            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => $status_approved
            ]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Diproses']);
        }
    }

    public  function batalkanizinsakit($kode_izin)
    {
        DB::beginTransaction();
        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => 0
            ]);
            DB::table('presensi')->where('kode_izin', $kode_izin)->delete();
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Dibatalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Dibatalkan']);
        }

        $update = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
            'status_approved' => 0
        ]);
        if ($update) {

            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public  function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')
            ->where('nik', $nik)
            ->where('tgl_izin_dari', $tgl_izin)
            ->count();
        return $cek;
    }

    public function showact($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('presensi.showact', compact('dataizin'));
    }

    public function deleteizin($kode_izin)
    {
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $doc_sid = $cekdataizin->doc_sid;
        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();
            if ($doc_sid != null) {
                Storage::delete('public/uploads/sid/' . $doc_sid);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data gagal dihapus']);
        }
    }
}
