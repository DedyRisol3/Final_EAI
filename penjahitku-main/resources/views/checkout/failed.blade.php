@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h1 class="text-danger mb-4">❌ Pembayaran Gagal</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <p>Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau hubungi admin.</p>

    <a href="{{ route('checkout.index') }}" class="btn btn-warning mt-3">Coba Lagi</a>
</div>
@endsection
