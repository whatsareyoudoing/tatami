{{--
    Tombol hapus (form DELETE) dengan konfirmasi. Konfirmasinya ditangani public/js/admin.js.
    <x-admin.delete-button :action="route('product.destroy', $product)" confirm="Yakin ingin menghapus produk ini?" />
--}}
@props(['action', 'confirm' => 'Yakin ingin menghapus data ini?', 'title' => 'Hapus'])

<form action="{{ $action }}" method="POST" class="d-inline" data-confirm="{{ $confirm }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-icon text-danger" title="{{ $title }}">
        <i class="fas fa-trash"></i>
    </button>
</form>
