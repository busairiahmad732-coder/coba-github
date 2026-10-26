@extends('layout.app-latihan')
@section('title', 'ujian')
@section('content')

    <div class="ujian container py-5 text-center">
        <h2>daftar siswa magang </h2>
        <p>Total Siswa: {{ $jumlahSiswa }}</p>
    <div class="row g-3">
        @forelse ($siswa as $data)
            <div class="col-12 col-sm-6 col-6">
                <div class="card border">
                    <h5>{{ $data['nama']}}</h5>
                    <p>Kelas: {{ $data['kelas']}}</p>
                @if ($data['status'] == 'Aktif')
                    <span class="badge text-bg-success">
                        {{ $data['status']}}
                    </span>
                @else 
                    <span class="badge text-bg-warning">
                        {{ $data['status']}}
                    </span>
                @endif
                </div>
            </div>
        @empty
        <p class="alert alert-warning text-center">
            <b>Belum ada data</b>
        </p>
        @endforelse
    </div>
    </div>
@endsection