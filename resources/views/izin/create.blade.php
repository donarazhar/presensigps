@extends('layouts.presensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        .datepicker-modal {
            max-height: 470px !important;
        }

        .datepicker-date-display {
            background-color: #0f3a7e !important
        }

        #keterangan {
            height: 5rem !important;
        }
    </style>
    {{-- App Header --}}
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton" goBack>
                <ion-icon name="chevron-back-outline">
                </ion-icon></a>
        </div>
        <div class="pageTittle">Formulir Pengajuan Izin</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="/izinabsen/store" id="formIzin">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control datepicker" autocomplete="off" placeholder="Dari"
                        name="tgl_izin_dari" id="tgl_izin_dari">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control datepicker" autocomplete="off" placeholder="Sampai"
                        name="tgl_izin_sampai" id="tgl_izin_sampai">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" readonly autocomplete="off" placeholder="Jumlah Izin"
                        name="jml_hari" id="jml_hari">
                </div>

                <div class="form-group">
                    <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" class="form-control"
                        autocomplete="off"></input>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block ">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });

            function loadjumlahhari() {
                var dari = $("#tgl_izin_dari").val();
                var sampai = $("#tgl_izin_sampai").val();
                var date1 = new Date(dari);
                var date2 = new Date(sampai);

                // to calculate the time
                var Difference_In_Time = date2.getTime() - date1.getTime();
                // To Calculate the of days
                var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
                // To Display  
                if (dari == "" || sampai == "") {
                    var jmlhari = 0;
                } else {
                    var jmlhari = Difference_In_Days + 1;
                }
                $("#jml_hari").val(jmlhari + " Hari");

            }
            $("#tgl_izin_dari,#tgl_izin_sampai").change(function(e) {
                loadjumlahhari();
            });

            $("#formIzin").submit(function() {
                var tgl_izin_dari = $("#tgl_izin_dari").val();
                var tgl_izin_sampai = $("#tgl_izin_sampai").val();
                var keterangan = $("#keterangan").val();
                if (tgl_izin_dari == "" || tgl_izin_sampai == "") {
                    // alert('Tanggal harus Diisi');
                    Swal.fire({
                        title: 'Oopss!',
                        text: 'Tanggal Izin harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (keterangan == "") {
                    Swal.fire({
                        title: 'Oopss!',
                        text: 'Keterangan harus diisi',
                        icon: 'warning'
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
