@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-center align-items-center min-vh-100">

    <!-- Kotak Form Login -->
    <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
      <div class="card-body p-4 p-sm-5">
        <h3 class="text-center mb-4">Login</h3>

        <form>
          <!-- Input Email -->
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="name@example.com">
          </div>

          <!-- Input Password -->
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password">
          </div>

          <!-- Tombol Login -->
          <button type="submit" class="btn btn-primary w-100 mt-3">Masuk</button>
        </form>

      </div>
    </div>

  </div>

@endsection

<!-- Script khusus untuk halaman ini -->
@push('scripts')
<script>
    $(document).ready(function() {


    });
</script>
@endpush

@push('styles')
<style>

</style>
@endpush

