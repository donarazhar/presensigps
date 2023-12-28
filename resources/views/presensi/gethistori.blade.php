<style>
    .historicontent {
        display: flex;
    }

    .datapresensi {
        margin-left: 10px;
    }

    .card {
        border: 1px solid rgb(184, 184, 184)
    }
</style>
@if ($histori->isEmpty())
    <div class="alert alert-warning">Data Tidak Ada</div>
@endif
@foreach ($histori as $d)
    @if ($d->status == 'h')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <div class="avatar">
                            @if (!empty(Auth::guard('karyawan')->user()->foto))
                                @php
                                    $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                                @endphp
                                <img src="{{ url($path) }}" alt="avatar" class="imaged w32 rounded">
                            @else
                                <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar"
                                    class="imaged w32 rounded">
                            @endif
                        </div>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">{{ $d->nama_jam_kerja }}</h3>
                        <h4 style="margin: 0px !important">
                            {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span>
                            {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<span class="text-danger">Belum Absen</span>' !!}
                        </span>
                        <span>
                            {!! $d->jam_out != null
                                ? '-' . date('H:i', strtotime($d->jam_out))
                                : '<span class="text-danger">- Belum Absen</span>' !!}
                        </span><br>

                        <div id="keterangan">
                            @php
                                // Jam ketika dia Absen
                                $jam_in = date('H:i', strtotime($d->jam_in));
                                // Jam Jadwal masuk
                                $jam_masuk = date('H:i', strtotime($d->jam_masuk));

                                $jadwal_jam_masuk = $d->tgl_presensi . '' . $jam_masuk;
                                $jam_presensi = $d->tgl_presensi . '' . $jam_in;
                            @endphp
                            @if ($jam_in > $jam_masuk)
                                @php
                                    $jmlterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);

                                @endphp
                                <span class="danger">Telat:
                                    {{ $jmlterlambat }} menit</span>
                            @else
                                <span style="color: green">On Time</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 'i')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="person-remove-outline" style="font-size: 28px; color:#1e74fd;"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">Izin - {{ $d->kode_izin }}
                        </h3>
                        <h4 style="margin: 0px !important">Dari :
                            {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span>Keterangan : {{ $d->keterangan }}</span>
                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 's')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="medkit-outline" style="font-size: 28px; color:#fd1e1e;"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">Sakit - {{ $d->kode_izin }} </h3>
                        <h4 style="margin: 0px !important">Dari :
                            {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span>Keterangan : {{ $d->keterangan }}</span>
                        <br>
                        @if (!empty($d->doc_sid))
                            <span style="color: #1e74fd;">
                                <ion-icon name="document-attach-outline"></ion-icon>
                                <small>Surat Dokter</small>
                            </span>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 'c')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="calendar-outline" style="font-size: 36px; color:#ffae00;"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">Cuti - {{ $d->kode_izin }} </h3>
                        <h4 style="margin: 0px !important">Dari :
                            {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        {{-- <span>{{ $d->nama_cuti }}<br> </span> --}}
                        <span>Keterangan : {{ $d->keterangan }}</span>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
