@extends('account.layout')

@section('account')
    <h1 class="h3 fw-bold mb-4">Ubah Profil & Password</h1>

    {{-- Dua form terpisah, error-nya juga dipisah (error bag 'profile' dan 'password') --}}
    <form action="{{ route('account.profile') }}" method="POST">
        @csrf
        @method('PUT')

        <x-admin.card title="Profil">
            <div class="row g-3">
                @php $profileErrors = $errors->getBag('profile'); @endphp
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" autocomplete="name"
                           @class(['form-control', 'is-invalid' => $profileErrors->has('name')])>
                    @if ($profileErrors->has('name'))
                        <div class="invalid-feedback">{{ $profileErrors->first('name') }}</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    <div class="form-text">Email belum bisa diganti.</div>
                </div>
            </div>

            <x-slot:footer>
                <div class="text-end">
                    <button type="submit" class="btn btn-tatami"><i class="fas fa-save me-1"></i> Simpan Profil</button>
                </div>
            </x-slot:footer>
        </x-admin.card>
    </form>

    <form action="{{ route('account.password') }}" method="POST">
        @csrf
        @method('PUT')

        <x-admin.card title="Ganti Password">
            @php $passwordErrors = $errors->getBag('password'); @endphp
            <div class="row g-3">
                @foreach ([
                    'current_password'      => ['Password Lama', 'current-password'],
                    'password'              => ['Password Baru', 'new-password'],
                    'password_confirmation' => ['Ulangi Password Baru', 'new-password'],
                ] as $field => [$label, $autocomplete])
                    <div class="col-md-4">
                        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                        <input type="password" id="{{ $field }}" name="{{ $field }}" autocomplete="{{ $autocomplete }}"
                               @class(['form-control', 'is-invalid' => $passwordErrors->has($field)])>
                        @if ($passwordErrors->has($field))
                            <div class="invalid-feedback">{{ $passwordErrors->first($field) }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="form-text mt-2">Password baru minimal 8 karakter.</div>

            <x-slot:footer>
                <div class="text-end">
                    <button type="submit" class="btn btn-tatami"><i class="fas fa-key me-1"></i> Ganti Password</button>
                </div>
            </x-slot:footer>
        </x-admin.card>
    </form>
@endsection
