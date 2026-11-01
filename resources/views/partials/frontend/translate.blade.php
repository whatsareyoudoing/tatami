{{--
    Terjemahan otomatis halaman website (Indonesia -> English / 日本語) memakai Google Translate.
    Dipasang di layout frontend dan guest saja, tidak di panel admin.

    Cara kerja: pilihan bahasa disimpan di cookie "googtrans" (format /id/en).
    Script Google hanya dimuat kalau bahasa yang dipilih bukan Indonesia, jadi pengunjung
    berbahasa Indonesia tidak memuat apa pun dari luar.
    Teks yang tidak boleh diterjemahkan diberi class "notranslate" (nama toko, no. pesanan, dll).
--}}
<div id="google_translate_element" class="d-none" aria-hidden="true"></div>

<style>
    /* Sembunyikan banner dan tooltip bawaan Google */
    body { top: 0 !important; }
    .skiptranslate iframe, .goog-te-banner-frame, #goog-gt-tt, .goog-te-balloon-frame { display: none !important; }
    .goog-text-highlight { background: none !important; box-shadow: none !important; }
</style>

<script>
    (function () {
        var LANGUAGES = ['en', 'ja'];
        var match = document.cookie.match(/(?:^|;\s*)googtrans=\/id\/([a-z]+)/);
        var current = match && LANGUAGES.indexOf(match[1]) !== -1 ? match[1] : 'id';

        // Google ikut menyimpan cookie "googtrans" di domain induk (misal .gresikdev.com untuk
        // tatami.gresikdev.com). Kalau hanya cookie di domain situs yang diganti, cookie Google tetap
        // terbaca dan bahasa "nyangkut". Jadi hapus di semua tingkat domain, lalu tulis satu cookie baru.
        function clearCookies() {
            var expired = 'googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
            document.cookie = expired;

            var host = location.hostname;
            if (/^[\d.]+$/.test(host) || host.indexOf('.') === -1) {
                return; // IP atau localhost: tidak ada domain induk
            }
            var parts = host.split('.');
            for (var i = 0; i < parts.length - 1; i++) {
                document.cookie = expired + '; domain=.' + parts.slice(i).join('.');
                document.cookie = expired + '; domain=' + parts.slice(i).join('.');
            }
        }

        // Pilihan bahasa di navbar: <a data-lang="en">
        document.addEventListener('click', function (event) {
            var link = event.target.closest('[data-lang]');
            if (!link) {
                return;
            }
            event.preventDefault();

            var lang = link.getAttribute('data-lang');
            clearCookies();
            if (LANGUAGES.indexOf(lang) !== -1) {
                document.cookie = 'googtrans=/id/' + lang + '; path=/';
            }
            location.reload();
        });

        // Tandai bahasa aktif di menu
        document.querySelectorAll('[data-lang]').forEach(function (link) {
            link.classList.toggle('active', link.getAttribute('data-lang') === current);
        });
        document.querySelectorAll('[data-lang-current]').forEach(function (label) {
            label.textContent = current.toUpperCase() === 'JA' ? 'JP' : current.toUpperCase();
        });

        if (current === 'id') {
            return;
        }

        window.googleTranslateElementInit = function () {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: LANGUAGES.join(','),
                autoDisplay: false
            }, 'google_translate_element');
        };

        var script = document.createElement('script');
        script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        script.async = true;
        document.body.appendChild(script);
    })();
</script>
