<!DOCTYPE html>
<html lang="id">

<head>

    <!-- Encoding karakter -->
    <meta charset="UTF-8">

    <!-- Supaya layout mengikuti ukuran HP -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Kartu Keluarga</title>

    <!-- Font seperti aplikasi mobile -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">


    <style>
        /* =========================
   SweetAlert2 kecil untuk response keluarga
   ========================= */
        .swal2-title-sm {
            font-size: 1rem !important;
            /* judul lebih kecil */
        }

        .swal2-html-sm {
            font-size: 0.85rem !important;
            /* teks html lebih kecil */
        }

        .swal2-confirm-sm {
            font-size: 0.85rem !important;
            /* tombol OK lebih kecil */
            padding: 0.25em 0.75em !important;
        }

        /* =========================
   Style khusus SweetAlert2 loading
   ========================= */
        .swal2-title-sm {
            font-size: 1rem !important;
            /* ukuran title lebih kecil */
        }

        .swal2-html-sm {
            font-size: 0.85rem !important;
            /* ukuran teks html lebih kecil */
        }

        /* ==============================
   RESET DASAR
============================== */

        * {
            box-sizing: border-box;
        }



        /* ==============================
   BODY
============================== */

        body {

            font-family: Poppins;
            background: #eaeaea;
            margin: 0;

            display: flex;
            justify-content: center;

        }



        /* ==============================
   WRAPPER HP RESPONSIVE
   (Gabungan kode kamu + perbaikan)
============================== */

        .app-wrapper {

            width: 100%;
            max-width: 420px;

            /* lebih stabil dari 100vh */
            height: 100dvh;

            background: #f3f7f4;

            display: flex;
            flex-direction: column;

        }



        /* ==============================
   HEADER RESPONSIVE HP
============================== */

        .header {

            background: #17a34a;
            color: white;

            /* padding mengikuti layar HP */
            padding-top: calc(12px + env(safe-area-inset-top));
            padding-bottom: 12px;

            font-size: 18px;
            font-weight: 600;
            text-align: center;

            border-radius: 0 0 20px 20px;

            flex-shrink: 0;

        }



        /* ==============================
   CONTAINER SCROLL
============================== */

        .container {

            flex: 1;

            overflow-y: auto;

            padding: 15px;

            padding-bottom: 120px;

        }



        /* ==============================
   CARD FORM
============================== */

        .card {

            background: white;

            border-radius: 18px;

            padding: 18px;

            box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.06);

        }



        /* ==============================
   JUDUL CARD
============================== */

        .title {

            font-size: 17px;
            font-weight: 600;
            margin-bottom: 15px;

        }



        /* ==============================
   LABEL INPUT
============================== */

        .label {

            font-size: 13px;
            margin-bottom: 5px;
            display: block;
            font-weight: 600;
            color: #333;

        }



        /* ==============================
   INPUT STYLE
============================== */

        .input,
        .select,
        .textarea {

            width: 100%;

            padding: 13px;

            border-radius: 12px;

            border: 1px solid #e2e2e2;

            margin-bottom: 15px;

            font-size: 14px;

            background: #fafafa;

        }



        /* ==============================
   FILE UPLOAD BOX
============================== */

        .filebox {

            border: 2px dashed #1fa55b;

            padding: 18px;

            text-align: center;

            border-radius: 14px;

            margin-bottom: 15px;

            background: #f7fff9;

        }



        /* ==============================
   PREVIEW FOTO
============================== */

        .preview {

            width: 100%;
            margin-top: 10px;
            border-radius: 12px;
            display: none;

        }



        /* ==============================
   FOOTER RESPONSIVE HP
============================== */

        .footer {

            flex-shrink: 0;

            width: 100%;

            background: white;

            box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.08);

            /* supaya tidak mentok bawah HP */

            padding-top: 12px;

            padding-bottom: calc(12px + env(safe-area-inset-bottom));

            padding-left: 12px;

            padding-right: 12px;

        }



        /* ==============================
   BUTTON SIMPAN
============================== */

        .btn {

            width: 100%;

            padding: 16px;

            border-radius: 14px;

            border: none;

            background: #17a34a;

            color: white;

            font-weight: 600;

            font-size: 16px;

            cursor: pointer;

        }



        .btn:active {

            transform: scale(0.98);

        }
    </style>

</head>



<body>



    <div class="app-wrapper">



        <!-- HEADER -->

        <div class="header">

            Data Kartu Keluarga

        </div>



        <!-- CONTAINER SCROLL -->

        <div class="container">

            <div class="card">

                <div class="title">
                    Isi Data Kartu Keluarga
                </div>


                <!-- FORM LARAVEL -->
                <form method="POST" action="{{ route('keluarga.store') }}" enctype="multipart/form-data">

                    @csrf


                    <label class="label">
                        Nomor KK
                    </label>

                    <input type="text" name="no_kk" class="input" required>



                    <label class="label">
                        Status
                    </label>

                    <!-- Tampilan readonly -->
                    <select class="select" disabled>
                        <option value="aktif" selected>
                            Aktif
                        </option>
                    </select>

                    <!-- Nilai dikirim ke Laravel -->
                    <input type="hidden" name="status" value="aktif">



                    <label class="label">
                        KTP Setempat
                    </label>

                    <select name="ktp_setempat" id="ktp_setempat" class="select">

                        <option value="ya">
                            Ya
                        </option>

                        <option value="tidak">
                            Tidak
                        </option>

                    </select>



                    <label class="label">
                        Kependudukan
                    </label>

                    <select id="kependudukan_view" class="select" disabled>

                        <option value="tetap">
                            KTP Setempat
                        </option>

                        <option value="domisili">
                            Domisili
                        </option>

                    </select>

                    <input type="hidden" name="kependudukan" id="kependudukan">



                    <label class="label">
                        Alamat KK
                    </label>

                    <textarea name="alamat_kk" rows="3" class="textarea"></textarea>



                    <div class="form-group">
                        <label class="label">Provinsi</label>
                        <select id="provinsi" name="provinsi" class="select">
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label">Kota/Kabupaten</label>
                        <select id="kota" name="kota" class="select">
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label">Kecamatan</label>
                        <select id="kecamatan" name="kecamatan" class="select">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label">Desa / Kelurahan</label>
                        <select id="desa" name="desa_kelurahan" class="select">
                            <option value="">Pilih Desa/Kelurahan</option>
                        </select>
                    </div>



                    <label class="label">Upload Foto KK</label>
                    <div class="filebox">
                        <input type="file" name="foto_kk" accept="image/*" capture="user"
                            onchange="previewImage(this, 'preview_kk')" required>
                        <img id="preview_kk" class="preview" style="display:none; max-width:200px; margin-top:8px;">
                    </div>


                </form>

            </div>

        </div>



        <!-- FOOTER -->

        <div class="footer">

            <button class="btn">

                Simpan Data

            </button>

        </div>



    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /*
                                            ================================
                                            CONFIG API WILAYAH (.ENV)
                                            ================================
                                    */

        const API_PROVINSI = "{{ config('wilayah.provinsi') }}";
        const API_KOTA = "{{ config('wilayah.kota') }}";
        const API_KECAMATAN = "{{ config('wilayah.kecamatan') }}";
        const API_DESA = "{{ config('wilayah.desa') }}";


        /*
        ================================
        SWEET ALERT ERROR API
        ================================
        */

        function apiError() {
            // ✅ Perbaikan: menggunakan didOpen untuk memastikan DOM popup siap
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Wilayah',
                text: 'Periksa koneksi internet atau server sedang sibuk',
                confirmButtonText: 'Coba Lagi',
                didOpen: () => {
                    // tidak perlu tambahan event listener karena confirmButton otomatis
                }
            }).then(() => {
                location.reload();
            });
        }


        document.addEventListener("DOMContentLoaded", function() {

            /*
            ================================
            LOAD PROVINSI
            ================================
            */

            fetch(API_PROVINSI)
                .then(res => {
                    if (!res.ok) throw new Error('Server Error');
                    return res.json();
                })
                .then(data => {
                    let prov = document.getElementById('provinsi');
                    if (prov) {
                        prov.innerHTML = '<option value="">Pilih Provinsi</option>';
                        data.forEach(item => {
                            prov.innerHTML +=
                                `<option value="${item.name}" data-id="${item.id}">
                            ${item.name}
                            </option>`;
                        });
                    }
                })
                .catch(apiError);

            /*
            ================================
            PROVINSI -> KOTA
            ================================
            */
            const provinsiEl = document.getElementById('provinsi');
            if (provinsiEl) {
                provinsiEl.addEventListener('change', function() {
                    let id = this.selectedOptions[0]?.dataset.id;
                    let kota = document.getElementById('kota');
                    let kec = document.getElementById('kecamatan');
                    let desa = document.getElementById('desa');

                    if (kota) kota.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    if (kec) kec.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    if (desa) desa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

                    if (!id) return;

                    fetch(API_KOTA + '/' + id + '.json')
                        .then(res => {
                            if (!res.ok) throw new Error('Server Error');
                            return res.json();
                        })
                        .then(data => {
                            data.forEach(item => {
                                if (kota) {
                                    kota.innerHTML +=
                                        `<option value="${item.name}" data-id="${item.id}">
                                    ${item.name}
                                    </option>`;
                                }
                            });
                        })
                        .catch(apiError);
                });
            }

            /*
            ================================
            KOTA -> KECAMATAN
            ================================
            */
            const kotaEl = document.getElementById('kota');
            if (kotaEl) {
                kotaEl.addEventListener('change', function() {
                    let id = this.selectedOptions[0]?.dataset.id;
                    let kec = document.getElementById('kecamatan');
                    let desa = document.getElementById('desa');

                    if (kec) kec.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    if (desa) desa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

                    if (!id) return;

                    fetch(API_KECAMATAN + '/' + id + '.json')
                        .then(res => {
                            if (!res.ok) throw new Error('Server Error');
                            return res.json();
                        })
                        .then(data => {
                            data.forEach(item => {
                                if (kec) {
                                    kec.innerHTML +=
                                        `<option value="${item.name}" data-id="${item.id}">
                                    ${item.name}
                                    </option>`;
                                }
                            });
                        })
                        .catch(apiError);
                });
            }

            /*
            ================================
            KECAMATAN -> DESA
            ================================
            */
            const kecEl = document.getElementById('kecamatan');
            if (kecEl) {
                kecEl.addEventListener('change', function() {
                    let id = this.selectedOptions[0]?.dataset.id;
                    let desa = document.getElementById('desa');

                    if (desa) desa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                    if (!id) return;

                    fetch(API_DESA + '/' + id + '.json')
                        .then(res => {
                            if (!res.ok) throw new Error('Server Error');
                            return res.json();
                        })
                        .then(data => {
                            data.forEach(item => {
                                if (desa) {
                                    desa.innerHTML +=
                                        `<option value="${item.name}">
                                    ${item.name}
                                    </option>`;
                                }
                            });
                        })
                        .catch(apiError);
                });
            }

            /*
            ================================
            AUTO KEPENDUDUKAN
            ================================
            */
            const ktp = document.getElementById('ktp_setempat');
            const kependudukan = document.getElementById('kependudukan');
            const kependudukanView = document.getElementById('kependudukan_view');

            function setKependudukan() {
                if (ktp.value === 'ya') {
                    kependudukan.value = 'tetap';
                    kependudukanView.value = 'tetap';
                } else {
                    kependudukan.value = 'domisili';
                    kependudukanView.value = 'domisili';
                }
            }

            setKependudukan();
            if (ktp) ktp.addEventListener('change', setKependudukan);

        });

        /*
        ================================
        PREVIEW FOTO KK
        ================================
        */
        function previewImage(input, previewId) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }

        /*
    ==========================================
    SWEET ALERT RESPONSE KELUARGA CONTROLLER
    (PERBAIKAN: gunakan didOpen untuk mencegah error)
    ==========================================
    */
        @if (session('status'))
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "{{ session('status') }}",
                    title: "{{ session('message') }}",
                    confirmButtonColor: '#3085d6',
                    customClass: {
                        title: 'swal2-title-sm', // title lebih kecil
                        htmlContainer: 'swal2-html-sm', // teks html lebih kecil
                        confirmButton: 'swal2-confirm-sm' // tombol ok lebih kecil
                    },
                    didOpen: () => {
                        // ✅ pastikan DOM popup siap sebelum JS tambahan
                    }
                });
            });
        @endif
        /*
        ==========================================
        SUBMIT FORM DARI BUTTON FOOTER + LOADING ALERT
        ==========================================
        */
        const footerBtn = document.querySelector('.btn');
        if (footerBtn) {
            footerBtn.addEventListener('click', function(e) {
                e.preventDefault(); // hentikan submit default sementara

                const form = document.querySelector('form');
                if (!form) return;

                // ✅ Tampilkan SweetAlert2 loading dengan teks lebih kecil
                Swal.fire({
                    title: 'Sedang memproses data...',
                    html: 'Mohon tunggu beberapa saat.',
                    allowOutsideClick: false,
                    customClass: {
                        title: 'swal2-title-sm',
                        htmlContainer: 'swal2-html-sm'
                    },
                    didOpen: () => {
                        Swal.showLoading(); // animasi loading
                    }
                });

                // submit form setelah alert tampil
                form.submit();
            });
        }
    </script>



</body>

</html>
