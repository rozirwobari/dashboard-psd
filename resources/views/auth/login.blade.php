@extends('layouts.app')

@section('content')
<div class="card-body">
    <h3 class="text-center">Masukan Akun Kamu</h3>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">{{ __('Login') }}</button>
        <div class="d-flex align-items-center justify-content-center">
            <a class="text-primary fw-bold ms-2" href="{{ route('register') }}">Buat Akun Baru</a>
        </div>
    </form>
</div>
@endsection
