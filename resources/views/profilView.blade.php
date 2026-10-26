@extends('layout.app-latihan')
@section('title', 'profil')
@section('content')

    <div class="profil ">
        <h1>profil saya</h1>
        <div class="card-profil">
            <div class="foto">
                <img src="{{ asset('image/ari.png') }}" alt="">
            </div>
            <h5>{{ $nama }}</h5>
            <p><b>{{ $sekolah}}</b></p>
            <p>magang di <b>{{ $magang }}</b></p>
        </div>
    </div>
    <div class="hobi">
        <h2 >daftar hobi</h2>
        <div class="card-hobi">
                <div class="item-list">
                @forelse ($hobi as $item)
                    <p>
                        {{ $loop->iteration }}.
                        <b>{{ $item}}</b>
                    </p>
                @empty
                    <p class="list-item">
                        Belum ada data hobi
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
