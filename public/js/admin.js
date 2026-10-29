// Konfirmasi sebelum submit form yang punya atribut data-confirm (dipakai tombol hapus)
document.addEventListener('submit', function (event) {
    var message = event.target.dataset ? event.target.dataset.confirm : null;

    if (message && !confirm(message)) {
        event.preventDefault();
    }
});
