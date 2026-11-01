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

        function writeCookie(value, expires) {
            var base = 'googtrans=' + value + '; path=/' + (expires ? '; expires=' + expires : '');
            document.cookie = base;
            // Google kadang menyimpan cookie di domain utama (.tatamiindonesia.id), jadi ikut diatur
            if (location.hostname.indexOf('.') !== -1 && !/^[\d.]+$/.test(location.hostname)) {
                document.cookie = base + '; domain=.' + location.hostname.replace(/^www\./, '');
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
            if (lang === current) {
                return;
            }
            if (lang === 'id') {
                writeCookie('', 'Thu, 01 Jan 1970 00:00:00 GMT');
            } else {
                writeCookie('/id/' + lang);
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
