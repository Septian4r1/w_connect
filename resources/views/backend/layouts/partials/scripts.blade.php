@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // =========================
            // 1. Proteksi klik kanan
            // =========================
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                alert("Anda tidak di izinkan untuk melihat!");
            });

            // =========================
            // 2. Proteksi tombol DevTools
            // =========================
            document.addEventListener('keydown', function(e) {
                if (e.key === "F12") e.preventDefault(); // F12
                if (e.ctrlKey && e.shiftKey && e.key.toUpperCase() === "I") e
                    .preventDefault(); // Ctrl+Shift+I
                if (e.ctrlKey && e.shiftKey && e.key.toUpperCase() === "J") e
                    .preventDefault(); // Ctrl+Shift+J
                if (e.ctrlKey && e.key.toUpperCase() === "U") e.preventDefault(); // Ctrl+U
            });

            // =========================
            // 3. Deteksi DevTools terbuka
            // =========================
            let devtoolsOpen = false;
            setInterval(() => {
                const threshold = 160;
                const widthThreshold = window.outerWidth - window.innerWidth > threshold;
                const heightThreshold = window.outerHeight - window.innerHeight > threshold;

                if (widthThreshold || heightThreshold) {
                    if (!devtoolsOpen) {
                        devtoolsOpen = true;
                        alert("Inspect element terdeteksi! Konten akan disembunyikan.");
                        document.body.innerHTML = ''; // sembunyikan konten
                    }
                } else {
                    devtoolsOpen = false;
                }
            }, 1000);

            // =========================
            // 4. Blur saat tab tidak fokus (mencegah screenshot)
            // =========================
            window.addEventListener("blur", function() {
                document.body.style.filter = "blur(8px)";
            });
            window.addEventListener("focus", function() {
                document.body.style.filter = "none";
            });
        });
    </script>
@endpush
