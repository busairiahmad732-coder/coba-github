<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HalamanController extends Controller
{
    //halaman utama
    public function latihanView()
    {
        $nama = "Ahmad Busairi";
      
        return view('latihanView', compact('nama'));
       
    }

    //profil
    public function profilView()
    {
        $nama = "Ahmad Busairi";
        $sekolah = "SMK Muhammadiyah 6 Rogojampi";
        $magang = "PT HUMMATECH MALANG";

        $hobi = [
            "Belajar",
            "Membaca",
            "Main game"
        ];

        return view('profilView', compact(
            'nama',
            'sekolah',
            'magang',
            'hobi'
        ));
    }

    //Daftar siswa
    private function ambilDataSiswa(Request $request) 
    {
        $siswa = $request->session()->get('dataSiswa');

        if ($siswa === null) {
            $siswa =[
                [
                    'id' => 1,
                    'nama' => 'Ari',
                    'sekolah' => 'SMK Muhammadiyah 6 Rogojampi',
                    'status' => 'Aktif'
                ],
                [
                    'id' => 2,
                    'nama' => 'Wafa',
                    'sekolah' => 'SMK Muhammadiyah 6 Rogojampi',
                    'status' => 'Aktif'
                ],
                [
                    'id' => 3,
                    'nama' => 'Rasya',
                    'sekolah' => 'SMK Muhammadiyah 6 Rogojampi',
                    'status' => 'Aktif'
                ],
                [
                    'id' => 4,
                    'nama' => 'Fais',
                    'sekolah' => 'SMK Muhammadiyah 6 Rogojampi',
                    'status' => 'Aktif'
                ],
                [
                    'id' => 5,
                    'nama' => 'Fuad',
                    'sekolah' => 'SMK Muhammadiyah 6 Rogojampi',
                    'status' => 'Aktif'
                ],
            ];
            //simpan data
            $request->session()->put('dataSiswa', $siswa);
        }
        return $siswa;
    }
    public function daftarSiswa(Request $request)
    {
        $siswa = $this->ambildataSiswa($request);

        $jumlahSiswa = count($siswa);
        return view('daftarSiswa', compact(
            'siswa',
            'jumlahSiswa'
        ));
    }
    //fetch API
    public function apiDaftarSiswa(Request $request)
    {
        $siswa = $this->ambilDataSiswa($request);
        
        //kirim ke format Json
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $siswa
        ]);
    }
    //tambah api
    public function tambahApiSiswa(Request $request)
    {
        //memeriksa data
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'sekolah' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);
        //mengambil data
        $siswa = $this->ambilDataSiswa($request);
        //membuat id baru
        $idBaru = count($siswa) > 0
            ? max(array_column($siswa, 'id')) + 1
            : 1;
        //membuat data siswa baru
        $dataBaru = [
            'id' => $idBaru,
            'nama' => $validated['nama'],
            'sekolah' => $validated['sekolah'],
            'status' => $validated['status'],
        ];
        //menambahkan siswa ke daftar
        $siswa[] = $dataBaru;
        //menyimpan daftar ke session
        $request->session()->put('dataSiswa', $siswa);
        //mengirim respon json
        return response()->json([       
            'success' => true,
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $dataBaru,
        ], 201);
    }
    //hapus api
    public function hapusApiSiswa(Request $request, $id)
    {
        //mengambil data siswa
        $siswa = $this->ambilDataSiswa($request);
        //mencari posisi siswa berdasarkan id
        $index = null;

        foreach ($siswa as $key => $data) {
            if ((int) $data['id'] === (int) $id) {
                $index = $key;
                break;
            }
        }
        //jika siswa tidak ditemukan 
        if ($index === null) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }
        //mengahpus siswa dari array
        array_splice($siswa, $index, 1);
        //menyimpan daftar terbaru du session
        $request->session()->put('dataSiswa', $siswa);
        //mengirim respon ke js
        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus'
        ]);
    }
    //update api
    public function updateApiSiswa(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'sekolah' => 'required|string|max:150',
            'status' => 'required|in:Aktif,Tidak Aktif'
        ]);

        $siswa = $this->ambilDataSiswa($request);
        $index = array_search(
            $id,
            array_column($siswa, 'id')
        );

        if ($index === false) {
            return response()->json([
                'message' => 'Data siswa tidak ditemukan.'
            ], 404);
        }
        //ubah data
        $siswa[$index]['nama'] = $request->nama;
        $siswa[$index]['sekolah'] = $request->sekolah;
        $siswa[$index]['status'] = $request->status;

        session(['dataSiswa' => $siswa]);

        return response()->json([
            'message' => 'Data berhasil diperbarui.',
            'data' => $siswa[$index]
        ]);
    }
}
