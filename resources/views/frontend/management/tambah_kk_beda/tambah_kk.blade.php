@extends('frontend.layouts.app')

@section('title', 'Profil')
@section('header-title', 'Data Keluarga')

@section('content')

    <style>
        .container {
            max-width: 420px;
            margin: auto;
        }

        /* Judul */
        .title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        /* Label */
        .label {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 3px;
            display: block;
            color: #444;
        }

        /* Input */
        .input,
        .select,
        .textarea {
            width: 100%;
            padding: 9px 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            font-size: 13px;
            background: #fafafa;
        }

        /* Textarea */
        .textarea {
            min-height: 70px;
            resize: none;
        }

        /* Upload */
        .filebox {
            border: 1.5px dashed #1fa55b;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            background: #f7fff9;
            margin-bottom: 12px;
            font-size: 12px;
        }

        /* Preview */
        .preview {
            width: 100%;
            border-radius: 8px;
            margin-top: 8px;
            max-height: 150px;
            object-fit: cover;
        }

        /* Button */
        .btn-save {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 8px;
            background: #17a34a;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        /* Select lebih kecil */
        select {
            appearance: none;
        }
    </style>


    <div class="container py-2">

        <div class="title">
            Isi Data Kartu Keluarga
        </div>

        <form id="formKK" method="POST" action="{{ route('storeBeda.kk') }}" enctype="multipart/form-data">

            @csrf

            <label class="label">Jenis Kartu Keluarga</label>

            <select name="jenis_kk_id" class="select" required>

                <option value="">Pilih Jenis KK</option>

                @foreach ($jenisKk as $jenis)
                    <option value="{{ $jenis->id }}">
                        {{ $jenis->nama }}
                    </option>
                @endforeach

            </select>


            <label class="label">Nomor KK</label>
            <input type="text" name="no_kk" class="input" required>



            <label class="label">Status</label>
            <select class="select" disabled>
                <option value="aktif" selected>Aktif</option>
            </select>

            <input type="hidden" name="status" value="aktif">



            <label class="label">KTP Setempat</label>
            <select name="ktp_setempat" id="ktp_setempat" class="select">
                <option value="ya">Ya</option>
                <option value="tidak">Tidak</option>
            </select>



            <label class="label">Kependudukan</label>
            <select id="kependudukan_view" class="select" disabled>
                <option value="tetap">KTP Setempat</option>
                <option value="domisili">Domisili</option>
            </select>

            <input type="hidden" name="kependudukan" id="kependudukan">



            <label class="label">Alamat KK</label>
            <textarea name="alamat_kk" rows="2" class="textarea"></textarea>



            <label class="label">Provinsi</label>
            <select id="provinsi" name="provinsi" class="select">
                <option value="">Pilih Provinsi</option>
            </select>



            <label class="label">Kota/Kabupaten</label>
            <select id="kota" name="kota" class="select">
                <option value="">Pilih Kota/Kabupaten</option>
            </select>



            <label class="label">Kecamatan</label>
            <select id="kecamatan" name="kecamatan" class="select">
                <option value="">Pilih Kecamatan</option>
            </select>



            <label class="label">Desa / Kelurahan</label>
            <select id="desa" name="desa_kelurahan" class="select">
                <option value="">Pilih Desa/Kelurahan</option>
            </select>



            <label class="label">Upload Foto KK</label>

            <div class="filebox">

                <input type="file" name="foto_kk" accept="image/*" capture="environment"
                    onchange="previewImage(this,'preview_kk')" required>

                <img id="preview_kk" class="preview" style="display:none">

            </div>



            <button type="submit" class="btn-save">
                Simpan Data
            </button>


        </form>

    </div>



    <script>
        /*
            ==============================
            CONFIG API WILAYAH
            ==============================
            */

        const API_PROVINSI = "{{ config('wilayah.provinsi') }}";
        const API_KOTA = "{{ config('wilayah.kota') }}";
        const API_KECAMATAN = "{{ config('wilayah.kecamatan') }}";
        const API_DESA = "{{ config('wilayah.desa') }}";


        /*
        ==============================
        ERROR API
        ==============================
        */

        function apiError() {

            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Wilayah',
                text: 'Periksa koneksi internet atau server sedang sibuk',
                confirmButtonText: 'Coba Lagi'
            }).then(() => {
                location.reload();
            });

        }



        document.addEventListener("DOMContentLoaded", function() {

            /*
            ==============================
            LOAD PROVINSI
            ==============================
            */

            fetch(API_PROVINSI)
                .then(res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .then(data => {

                    let prov = document.getElementById('provinsi');

                    data.forEach(item => {
                        prov.innerHTML += `<option value="${item.name}" data-id="${item.id}">
                    ${item.name}
                    </option>`;
                    });

                })
                .catch(apiError);



            /*
            ==============================
            PROVINSI -> KOTA
            ==============================
            */

            document.getElementById('provinsi').addEventListener('change', function() {

                let id = this.selectedOptions[0]?.dataset.id;

                document.getElementById('kota').innerHTML =
                    '<option value="">Pilih Kota/Kabupaten</option>';
                document.getElementById('kecamatan').innerHTML =
                    '<option value="">Pilih Kecamatan</option>';
                document.getElementById('desa').innerHTML =
                    '<option value="">Pilih Desa/Kelurahan</option>';

                if (!id) return;

                fetch(API_KOTA + '/' + id + '.json')
                    .then(res => res.json())
                    .then(data => {

                        data.forEach(item => {
                            document.getElementById('kota').innerHTML +=
                                `<option value="${item.name}" data-id="${item.id}">
                        ${item.name}
                        </option>`;
                        });

                    })
                    .catch(apiError);

            });



            /*
            ==============================
            KOTA -> KECAMATAN
            ==============================
            */

            document.getElementById('kota').addEventListener('change', function() {

                let id = this.selectedOptions[0]?.dataset.id;

                document.getElementById('kecamatan').innerHTML =
                    '<option value="">Pilih Kecamatan</option>';
                document.getElementById('desa').innerHTML =
                    '<option value="">Pilih Desa/Kelurahan</option>';

                if (!id) return;

                fetch(API_KECAMATAN + '/' + id + '.json')
                    .then(res => res.json())
                    .then(data => {

                        data.forEach(item => {
                            document.getElementById('kecamatan').innerHTML +=
                                `<option value="${item.name}" data-id="${item.id}">
                    ${item.name}
                    </option>`;
                        });

                    })
                    .catch(apiError);

            });



            /*
            ==============================
            KECAMATAN -> DESA
            ==============================
            */

            document.getElementById('kecamatan').addEventListener('change', function() {

                let id = this.selectedOptions[0]?.dataset.id;

                document.getElementById('desa').innerHTML =
                    '<option value="">Pilih Desa/Kelurahan</option>';

                if (!id) return;

                fetch(API_DESA + '/' + id + '.json')
                    .then(res => res.json())
                    .then(data => {

                        data.forEach(item => {
                            document.getElementById('desa').innerHTML +=
                                `<option value="${item.name}">
                        ${item.name}
                        </option>`;
                        });

                    })
                    .catch(apiError);

            });



            /*
            ==============================
            AUTO KEPENDUDUKAN
            ==============================
            */

            const ktp = document.getElementById('ktp_setempat');
            const kep = document.getElementById('kependudukan');
            const kepView = document.getElementById('kependudukan_view');

            function setKependudukan() {

                if (ktp.value === 'ya') {
                    kep.value = 'tetap';
                    kepView.value = 'tetap';
                } else {
                    kep.value = 'domisili';
                    kepView.value = 'domisili';
                }

            }

            setKependudukan();

            ktp.addEventListener('change', setKependudukan);

        });



        /*
        ==============================
        PREVIEW FOTO KK
        ==============================
        */

        function previewImage(input, id) {

            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(e) {

                const preview = document.getElementById(id);
                preview.src = e.target.result;
                preview.style.display = 'block';

            }

            reader.readAsDataURL(file);

        }



        /*
        ==============================
        SWEET ALERT RESPONSE
        ==============================
        */

        @if (session('status'))

            document.addEventListener("DOMContentLoaded", function() {

                Swal.fire({
                    icon: "{{ session('status') }}",
                    title: "{{ session('message') }}",
                    confirmButtonColor: '#3085d6'
                });

            });
        @endif



        /*
        ==============================
        LOADING SAAT SUBMIT
        ==============================
        */

        document.getElementById('formKK').addEventListener('submit', function() {

            Swal.fire({
                title: 'Sedang memproses data...',
                html: 'Mohon tunggu beberapa saat.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

        });
    </script>


@endsection
