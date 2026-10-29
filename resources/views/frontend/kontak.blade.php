@extends('layouts.frontend')

@section('title', 'Kontak')

@section('content')
{{-- navbar --}}

{{-- content --}}
    <div class="container-fluid">
        <div class="container my-5">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h1 class="fw-bold">
                        Jangan ragu untuk bertanya kami siap melayani Anda dengan sepenuh hati.
                    </h1>
                    <p class="text-justify">
                        Central Industrial Park (CIP), Blok Gamma No. 09 (Jl. Raya Lingkar Timur Km. 4), Desa Kemiri, Sidoarjo, Jawa Timur, 61234
                    </p>
                    <span class="color-tatami fw-bold fs-5">
                        Email : tatamiindonesia@gmail.com
                    </span>
                </div>
                <div class="col-12 col-md-6">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Pesan</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <button class="btn btn-tatami">Kirim</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <iframe class="mt-5" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.161957171134!2d112.7528401!3d-7.447327100000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e6bcee50df29%3A0x55297bf7259e46d0!2sGudang%20CIP%20Central%20Industrial%20Park!5e0!3m2!1sid!2sid!4v1792680371847!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
        </div>
    </div>
{{--  --}}




@endsection
