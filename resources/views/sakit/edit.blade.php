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
        <div class="pageTittle">Edit Izin Sakit</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="/izinsakit/{{ $dataizin->kode_izin }}/update" id="formIzin"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control datepicker" value="{{ $dataizin->tgl_izin_dari }}"
                        autocomplete="off" placeholder="Dari" name="tgl_izin_dari" id="tgl_izin_dari">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control datepicker" value="{{ $dataizin->tgl_izin_sampai }}"
                        autocomplete="off" placeholder="Sampai" name="tgl_izin_sampai" id="tgl_izin_sampai">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" readonly autocomplete="off" placeholder="Jumlah Izin"
                        name="jml_hari" id="jml_hari">
                </div>

                {{-- Menampilkan image yang tersimpan --}}
                @if ($dataizin->doc_sid != null)
                    <div class="row">
                        <div class="col-12">
                            @php
                                $docsid = Storage::url('/uploads/sid/' . $dataizin->doc_sid);
                            @endphp
                            <img src="{{ url($docsid) }}" alt="" width="100px">
                        </div>
                    </div>
                @endif
                {{-- Form Upload --}}
                <input type="hidden" name="doc_sidedit" id="doc_sidedit" value="{{ $dataizin->doc_sid }}">
                <div class="custom-file-upload" id="fileUpload1" style="height: 100px !important">
                    <input type="file" name="sid" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                    <label for="fileuploadInput">
                        <span>
                            <strong>
                                <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                    aria-label="cloud upload outline"></ion-icon>
                                <i>Upload Surat Izin Dokter</i>
                            </strong>
                        </span>
                    </label>
                </div>
                <div class="form-group">
                    <input type="text" name="keterangan" id="keterangan" value="{{ $dataizin->keterangan }}"
                        placeholder="Keterangan" class="form-control" autocomplete="off"></input>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block ">Update</button>
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
            loadjumlahhari();
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
