@extends('layouts.admin.tabler')
@section('content')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Anda sedang dihalaman
                    </div>
                    <h2 class="page-title">
                        Konfigurasi Jam Kerja
                    </h2>
                </div>

            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif
                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('warning') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="#" class="btn btn-primary" id="btnTambahJK"><svg
                                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>Tambah Data</a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode JK</th>
                                                    <th>Nama JK</th>
                                                    <th>Awal Jam Masuk</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Akhir Jam Masuk</th>
                                                    <th>Jam Pulang</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($jam_kerja as $d)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $d->kode_jam_kerja }}</td>
                                                        <td>{{ $d->nama_jam_kerja }}</td>
                                                        <td>{{ $d->awal_jam_masuk }}</td>
                                                        <td>{{ $d->jam_masuk }}</td>
                                                        <td>{{ $d->akhir_jam_masuk }}</td>
                                                        <td>{{ $d->jam_pulang }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="#" class="edit btn btn-info btn-sm"
                                                                    kode_jam_kerja="{{ $d->kode_jam_kerja }}"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-pencil-bolt"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                                        <path d="M13.5 6.5l4 4" />
                                                                        <path d="M19 16l-2 3h4l-2 3" />
                                                                    </svg></a>
                                                                <form
                                                                    action="/konfigurasi/{{ $d->kode_jam_kerja }}/deletejamkerja"
                                                                    style="margin-left: 2px" method="POST">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-trash-filled"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                            stroke="currentColor" fill="none"
                                                                            stroke-linecap="round" stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z"
                                                                                stroke-width="0" fill="currentColor" />
                                                                            <path
                                                                                d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z"
                                                                                stroke-width="0" fill="currentColor" />
                                                                        </svg>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Input Jam Kerja --}}
    <div class="modal modal-blur fade" id="modal-inputJK" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Jam Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/konfigurasi/storejamkerja" method="POST" id="frmJK">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-number"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v-10l7 10v-10" />
                                            <path d="M15 17h5" />
                                            <path d="M17.5 10m-2.5 0a2.5 3 0 1 0 5 0a2.5 3 0 1 0 -5 0" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="kode_jam_kerja"
                                        id="kode_jam_kerja" placeholder="Kode Jam Kerja">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-code-plus" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 12h6" />
                                            <path d="M12 9v6" />
                                            <path d="M6 19a2 2 0 0 1 -2 -2v-4l-1 -1l1 -1v-4a2 2 0 0 1 2 -2" />
                                            <path d="M18 19a2 2 0 0 0 2 -2v-4l1 -1l-1 -1v-4a2 2 0 0 0 -2 -2" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="nama_jam_kerja"
                                        id="nama_jam_kerja" placeholder="Nama Jam Kerja">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-alarm-filled" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M16 6.072a8 8 0 1 1 -11.995 7.213l-.005 -.285l.005 -.285a8 8 0 0 1 11.995 -6.643zm-4 2.928a1 1 0 0 0 -1 1v3l.007 .117a1 1 0 0 0 .993 .883h2l.117 -.007a1 1 0 0 0 .883 -.993l-.007 -.117a1 1 0 0 0 -.993 -.883h-1v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M6.412 3.191a1 1 0 0 1 1.273 1.539l-.097 .08l-2.75 2a1 1 0 0 1 -1.273 -1.54l.097 -.08l2.75 -2z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M16.191 3.412a1 1 0 0 1 1.291 -.288l.106 .067l2.75 2a1 1 0 0 1 -1.07 1.685l-.106 -.067l-2.75 -2a1 1 0 0 1 -.22 -1.397z"
                                                stroke-width="0" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="awal_jam_masuk"
                                        id="awal_jam_masuk" placeholder="Awal Jam Masuk">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-alarm-filled" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M16 6.072a8 8 0 1 1 -11.995 7.213l-.005 -.285l.005 -.285a8 8 0 0 1 11.995 -6.643zm-4 2.928a1 1 0 0 0 -1 1v3l.007 .117a1 1 0 0 0 .993 .883h2l.117 -.007a1 1 0 0 0 .883 -.993l-.007 -.117a1 1 0 0 0 -.993 -.883h-1v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M6.412 3.191a1 1 0 0 1 1.273 1.539l-.097 .08l-2.75 2a1 1 0 0 1 -1.273 -1.54l.097 -.08l2.75 -2z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M16.191 3.412a1 1 0 0 1 1.291 -.288l.106 .067l2.75 2a1 1 0 0 1 -1.07 1.685l-.106 -.067l-2.75 -2a1 1 0 0 1 -.22 -1.397z"
                                                stroke-width="0" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="jam_masuk"
                                        id="jam_masuk" placeholder="Jam Masuk">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-alarm-filled" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M16 6.072a8 8 0 1 1 -11.995 7.213l-.005 -.285l.005 -.285a8 8 0 0 1 11.995 -6.643zm-4 2.928a1 1 0 0 0 -1 1v3l.007 .117a1 1 0 0 0 .993 .883h2l.117 -.007a1 1 0 0 0 .883 -.993l-.007 -.117a1 1 0 0 0 -.993 -.883h-1v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M6.412 3.191a1 1 0 0 1 1.273 1.539l-.097 .08l-2.75 2a1 1 0 0 1 -1.273 -1.54l.097 -.08l2.75 -2z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M16.191 3.412a1 1 0 0 1 1.291 -.288l.106 .067l2.75 2a1 1 0 0 1 -1.07 1.685l-.106 -.067l-2.75 -2a1 1 0 0 1 -.22 -1.397z"
                                                stroke-width="0" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="akhir_jam_masuk"
                                        id="akhir_jam_masuk" placeholder="Akhir Jam Masuk">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-alarm-filled" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M16 6.072a8 8 0 1 1 -11.995 7.213l-.005 -.285l.005 -.285a8 8 0 0 1 11.995 -6.643zm-4 2.928a1 1 0 0 0 -1 1v3l.007 .117a1 1 0 0 0 .993 .883h2l.117 -.007a1 1 0 0 0 .883 -.993l-.007 -.117a1 1 0 0 0 -.993 -.883h-1v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M6.412 3.191a1 1 0 0 1 1.273 1.539l-.097 .08l-2.75 2a1 1 0 0 1 -1.273 -1.54l.097 -.08l2.75 -2z"
                                                stroke-width="0" fill="currentColor" />
                                            <path
                                                d="M16.191 3.412a1 1 0 0 1 1.291 -.288l.106 .067l2.75 2a1 1 0 0 1 -1.07 1.685l-.106 -.067l-2.75 -2a1 1 0 0 1 -.22 -1.397z"
                                                stroke-width="0" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="jam_pulang"
                                        id="jam_pulang" placeholder="Jam Pulang">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 14l11 -11" />
                                            <path
                                                d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                        </svg>
                                        Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Jam Kerja --}}
    <div class="modal modal-blur fade" id="modal-editJK" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Jama Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadeditform">
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- JQuery Modal Input JK --}}
@push('myscript')
    <script>
        $(function() {
            // Modal Tambah JK
            $("#btnTambahJK").click(function() {
                // alert('test');
                $("#modal-inputJK").modal("show");
            });

            // Modal Edit Cabang
            $(".edit").click(function() {
                var kode_jam_kerja = $(this).attr('kode_jam_kerja');
                $.ajax({
                    type: 'POST',
                    url: '/konfigurasi/editjamkerja',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_jam_kerja: kode_jam_kerja
                    },
                    success: function(respond) {
                        $('#loadeditform').html(respond);
                    }
                });
                // alert(kode_cabang);
                $("#modal-editJK").modal("show");
            });

            $(".delete-confirm").click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: "Yakin Hapus Data?",
                    text: "Data anda akan terhapus permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                            title: "Terhapus!",
                            text: "Data anda berhasil terhapus",
                            icon: "success"
                        });
                    }
                });
            });

            $("#frmJK").submit(function() {
                var kode_jam_kerja = $("#kode_jam_kerja").val();
                var nama_jam_kerja = $("#nama_jam_kerja").val();
                var awal_jam_masuk = $("#awal_jam_masuk").val();
                var jam_masuk = $("#jam_masuk").val();
                var akhir_jam_masuk = $("#akhir_jam_masuk").val();
                var jam_pulang = $("#jam_pulang").val();


                if (kode_jam_kerja == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Kode Jam Kerja Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#kode_jam_kerja").focus();
                    });
                    return false;
                } else if (nama_jam_kerja == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Jam Kerja Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#nama_jam_kerja").focus();
                    });
                    return false;
                } else if (awal_jam_masuk == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Awal Jam Masuk Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#awal_jam_masuk").focus();
                    });
                    return false;
                } else if (jam_masuk == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Masuk Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#jam_masuk").focus();
                    });
                    return false;
                } else if (akhir_jam_masuk == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Akhir Jam Masuk Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#akhir_jam_masuk").focus();
                    });
                    return false;
                } else if (jam_pulang == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Pulang Harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        $("#jam_pulang").focus();
                    });
                    return false;
                }
            });

        });
    </script>
@endpush
