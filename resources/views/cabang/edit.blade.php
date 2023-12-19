<form action="/cabang/{{ $cabang->kode_cabang }}/update" method="POST" id="frmcabangedit">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-number" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v-10l7 10v-10" />
                        <path d="M15 17h5" />
                        <path d="M17.5 10m-2.5 0a2.5 3 0 1 0 5 0a2.5 3 0 1 0 -5 0" />
                    </svg>
                </span>
                <input type="text" value="{{ $cabang->kode_cabang }}" readonly class="form-control"
                    name="kode_cabang" id="kode_cabang" placeholder="Kode Cabang">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                    </svg>
                </span>
                <input type="text" value="{{ $cabang->nama_cabang }}" class="form-control" name="nama_cabang"
                    id="nama_cabang" placeholder="Nama Cabang">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" />
                        <path d="M9 4v13" />
                        <path d="M15 7v13" />
                    </svg>
                </span>
                <input type="text" value="{{ $cabang->lokasi_cabang }}" class="form-control" name="lokasi_cabang"
                    id="lokasi_cabang" placeholder="Latitude Longitude Cabang">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-letter-r"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M10 12h2a2 2 0 1 0 0 -4h-2v8m4 0l-3 -4" />
                    </svg>
                </span>
                <input type="text" value="{{ $cabang->radius_cabang }}" class="form-control" name="radius_cabang"
                    id="radius_cabang" placeholder="Radius Cabang">
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 14l11 -11" />
                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                    </svg>
                    Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {

        $("#frmcabangedit").submit(function() {
            var kode_cabang = $("#frmcabangedit").find("#kode_cabang").val();
            var nama_cabang = $("#frmcabangedit").find("#nama_cabang").val();
            var lokasi_cabang = $("#frmcabangedit").find("#lokasi_cabang").val();
            var radius_cabang = $("#frmcabangedit").find("#radius_cabang").val();

            if (kode_cabang == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Kode Cabang Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#kode_cabang").focus();
                });
                return false;
            } else if (nama_cabang == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Cabang Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#nama_cabang").focus();
                });
                return false;
            } else if (lokasi_cabang == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Latitude Longitude Cabang Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#lokasi_cabang").focus();
                });
                return false;
            } else if (radius_cabang == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Radius Cabang Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#radius_cabang").focus();
                });
                return false;
            }
        });
    });
</script>
