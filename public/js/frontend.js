// Efek halaman website (bukan admin): transisi pindah halaman dan animasi muncul saat discroll.
(function () {
    var main = document.getElementById('content');
    if (!main) return;

    // --- Pindah halaman: isi memudar keluar dulu, baru browser membuka link-nya ---
    var LEAVE_MS = 250; // samakan dengan transition #content di app.css

    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link || event.defaultPrevented || event.button !== 0) return;
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        if (link.target && link.target !== '_self') return;
        if (link.hasAttribute('download') || link.hasAttribute('data-bs-toggle')) return;
        if (link.origin !== location.origin) return;
        // Link ke bagian halaman yang sama (#...) tidak perlu animasi
        if (link.getAttribute('href').charAt(0) === '#') return;
        if (link.pathname === location.pathname && link.search === location.search && link.hash) return;

        event.preventDefault();
        main.classList.add('page-leaving');
        setTimeout(function () { location.href = link.href; }, LEAVE_MS);
    });

    // Kembali lewat tombol back (halaman dari cache browser): tampilkan lagi isinya
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) main.classList.remove('page-leaving');
    });

    // --- Animasi muncul saat discroll ---
    // Target otomatis: kolom grid, judul, paragraf/gambar langsung di .container, atau [data-reveal].
    // Elemen di dalam .page-hero atau yang bersarang di target lain dilewati.
    if (!('IntersectionObserver' in window)) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -40px 0px' });

    var selector = '[data-reveal], .row > [class*="col"], h2, h3, .container > p, .container > img';
    var targets = [];

    main.querySelectorAll(selector).forEach(function (el) {
        if (el.closest('.page-hero')) return;
        if (targets.some(function (t) { return t.contains(el); })) return;
        targets.push(el);

        // Kolom dalam satu baris muncul bergantian, maksimal 6 langkah agar tidak terlalu lama
        var parent = el.parentElement;
        var index = parent && parent.classList.contains('row')
            ? Array.prototype.indexOf.call(parent.children, el) % 6
            : 0;
        el.style.setProperty('--reveal-delay', (index * 80) + 'ms');
        el.classList.add('reveal');
        if (el.dataset.reveal === 'down') el.classList.add('reveal-down');
        observer.observe(el);
    });
})();
