@extends('layout.app-latihan')
@section('title', 'Daftar Siswa')
@section('content')

    <div class="card-tambah">  
    <div class="card-form">
        <h1>Tambah Siswa</h1>
        <form id="tambahSiswa">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Siswa :</label>
                <input
                    type="text"
                    class="form-input"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="sekolah" class="form-label">Sekolah :</label>
                <input
                    type="text"
                    class="form-input"
                    id="sekolah"
                    name="sekolah"
                    placeholder="Masukkan sekolah"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status :</label>
                <select
                    class="form-select"
                    id="status"
                    name="status"
                    required
                >
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Siswa</button>
            <p id="pesanForm" class="mt-3 mb-0"></p>
        </form>
        </div>
    </div>

    <!--edit data-->
    <div class="card-edit">
    <div class="card-form" id="formEditSiswa" hidden>
        <h1>Edit Siswa</h1>
        <form id="editSiswa">
            @csrf

            <input type="hidden" id="editId">
            

            <div class="mb-3">
                <label for="editNama" class="form-label">
                    Nama Siswa 
                </label>
                <input type="text" class="form-control" id="editNama" required>
            </div>
            <div class="mb-3">
                <label for="editSekolah" class="form-label">
                    Sekolah 
                </label>
                <input type="text" class="form-control" id="editSekolah" required>
            </div>
            <div class="mb-3">
                <label for="editStatus" class="form-label">
                    Status 
                </label>
                <select class="form-select" id="editStatus" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" id="batalEdit">Batal</button>

            <p id="pesanEdit" class="mt-3 mb-0"></p>
        </form>
    </div>
</div>

<!--card daftar-->
        <div class="daftar">
            <h1>Daftar siswa</h1>
            <p id="pesan">Memuat data siswa.....</p>

            <div class="row g-4" id="daftarSiswa">
                {{--Card siswa akan dibuat oleh Fetch API --}}
            </div>
        </div>   
@endsection