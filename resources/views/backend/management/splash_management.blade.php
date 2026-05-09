<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W_Connect Setup</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #f0fff4;
            min-height: 100vh;
        }

        /* animasi logo */
        @keyframes bounceText {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }

            50% {
                transform: translateY(5px);
                opacity: 1;
            }

            100% {
                transform: translateY(0);
            }
        }

        .bounce-text {
            animation: bounceText 1.5s ease-out infinite alternate;
        }

        /* animasi modal */
        @keyframes modalFade {
            from {
                opacity: 0;
                transform: scale(.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-enter {
            animation: modalFade .25s ease;
        }
    </style>
</head>

<body class="flex items-center justify-center p-4">

    @php
        $showRW = request()->get('show_rw_modal');
        $showRT = request()->get('show_rt_modal');
        $showBlock = request()->get('show_block_modal');

        $rwDone = !$showRW;
        $rtDone = $rwDone && !$showRT;
        $blockDone = $rtDone && !$showBlock;
    @endphp

    <div class="w-full max-w-md mx-auto flex flex-col items-center gap-6 relative z-40">

        <!-- LOGO -->
        <div class="flex flex-col items-center gap-2">
            <img src="{{ asset('images/logo_w_connect_web.gif') }}" class="w-20 sm:w-24 md:w-28">

            <img src="{{ asset('images/text_w_connect.png') }}" class="w-40 sm:w-44 md:w-52 bounce-text">
        </div>

        {{-- PROGRESS --}}
        @if ($showRW || $showRT || $showBlock)
            <div class="bg-white shadow-xl rounded-2xl p-5 sm:p-6 w-full">

                <h3 class="text-center font-semibold text-lg mb-5">
                    Setup Sistem
                </h3>

                <div class="space-y-4 text-sm">

                    <div class="flex items-center justify-between">
                        <span>1. Setup RW</span>
                        <span class="font-semibold {{ $rwDone ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $rwDone ? '✔ Selesai' : 'Sedang diproses...' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span>2. Setup RT</span>
                        <span class="font-semibold {{ $rtDone ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $rtDone ? '✔ Selesai' : ($showRT ? 'Menunggu...' : '-') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span>3. Setup Block / Cluster</span>
                        <span class="font-semibold {{ $blockDone ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $blockDone ? '✔ Selesai' : ($showBlock ? 'Menunggu...' : '-') }}
                        </span>
                    </div>

                </div>

                <!-- Progress bar -->
                <div class="mt-5 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-2 bg-green-500 transition-all duration-500"
                        style="width:
                        {{ $blockDone ? '100%' : ($rtDone ? '66%' : ($rwDone ? '33%' : '10%')) }}">
                    </div>
                </div>

            </div>
        @endif

        <!-- FOOTER -->
        <div class="text-center text-gray-600 text-sm">
            <p class="font-semibold">By: AsthA Production</p>
            <p class="text-xs tracking-widest">
                Version {{ config('app.version') }}
            </p>
        </div>

    </div>

    {{-- OVERLAY --}}
    @if ($showRW || $showRT || $showBlock)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-30"></div>
    @endif

    {{-- MODAL RW --}}
    @if ($showRW)
        <div class="fixed inset-0 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 modal-enter">

                <h2 class="text-lg text-center font-semibold mb-4">
                    Langkah 1: Buat RW
                </h2>

                <form action="{{ route('rw.store') }}" method="POST">
                    @csrf

                    <input type="text" name="nama_rw"
                        class="w-full border rounded-lg p-2 mt-1 focus:ring-2 focus:ring-green-400 outline-none"
                        placeholder="Contoh: RW 01" required>

                    <button class="w-full bg-green-600 text-white py-2 rounded-lg mt-4 hover:bg-green-700 transition">
                        Simpan RW
                    </button>
                </form>

            </div>
        </div>
    @endif

    {{-- MODAL RT --}}
    @if ($showRT)
        <div class="fixed inset-0 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 modal-enter">

                <h2 class="text-lg text-center font-semibold mb-4">
                    Langkah 2: Buat RT
                </h2>

                <form action="{{ route('rt.store') }}" method="POST">
                    @csrf

                    <select name="rw_id"
                        class="w-full border rounded-lg p-2 mt-1 focus:ring-2 focus:ring-green-400 outline-none"
                        required>
                        @foreach (\App\Models\RW::where('status', 'aktif')->get() as $rw)
                            <option value="{{ $rw->id }}">{{ $rw->nama_rw }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="nama_rt"
                        class="w-full border rounded-lg p-2 mt-3 focus:ring-2 focus:ring-green-400 outline-none"
                        placeholder="Contoh: RT 01" required>

                    <button class="w-full bg-green-600 text-white py-2 rounded-lg mt-4 hover:bg-green-700 transition">
                        Simpan RT
                    </button>
                </form>

            </div>
        </div>
    @endif

    {{-- MODAL BLOCK --}}
    @if ($showBlock)
        <div class="fixed inset-0 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 modal-enter">

                <h2 class="text-lg text-center font-semibold mb-4">
                    Langkah 3: Buat Cluster / Blok
                </h2>

                <form action="{{ route('block.store') }}" method="POST">
                    @csrf

                    <select name="rt_id"
                        class="w-full border rounded-lg p-2 mt-1 focus:ring-2 focus:ring-green-400 outline-none"
                        required>
                        @foreach (\App\Models\RT::where('status', 'aktif')->get() as $rt)
                            <option value="{{ $rt->id }}">{{ $rt->nama_rt }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="nama_blok"
                        class="w-full border rounded-lg p-2 mt-3 focus:ring-2 focus:ring-green-400 outline-none"
                        placeholder="Contoh: Cluster A" required>

                    <button class="w-full bg-green-600 text-white py-2 rounded-lg mt-4 hover:bg-green-700 transition">
                        Simpan Block
                    </button>
                </form>

            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const showRW = @json($showRW);
            const showRT = @json($showRT);
            const showBlock = @json($showBlock);
            const loggedIn = @json(auth()->check());
            const hasAlert = @json(session('alert_status'));

            if (hasAlert) {
                Swal.fire({
                    icon: "{{ session('alert_status') }}",
                    title: "{{ session('alert_title') }}",
                    text: "{{ session('alert_message') }}",
                    confirmButtonColor: '#16a34a'
                }).then(() => location.reload());
                return;
            }

            if (showRW || showRT || showBlock) return;

            setTimeout(() => {
                window.location.href = loggedIn ?
                    "/management/dashboard" :
                    "{{ route('showlogin_management') }}";
            }, 2000);

        });
    </script>

</body>

</html>
