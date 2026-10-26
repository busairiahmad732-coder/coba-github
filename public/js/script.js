async function tampilkanSiswa() {
            const daftar = document.getElementById('daftarSiswa');
            const pesan = document.getElementById('pesan');

            fetch('/api/siswa')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data siswa');
                    }
                    return response.json();
                })
                .then(hasil => {
                    daftar.innerHTML = '';

                    if (hasil.data.length === 0) {
                        daftar.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                Belum ada data siswa
                            </div>
                        </div>
                        `;
                        pesan.textContent = '';
                        return;                  
                    }
                    hasil.data.forEach(data => {
                        const kolom = document.createElement('div');
                        kolom.className = 'col-12 col-md-6 col-lg-3';

                        const card = document.createElement('div');
                        card.className = 'card-daftar h-100';

                        const nama = document.createElement('h5');
                        nama.textContent = data.nama;

                        const sekolah = document.createElement('p');
                        sekolah.textContent = 'Sekolah : ' + data.sekolah;

                        const status = document.createElement('span');
                        status.className = data.status === 'Aktif'
                            ? 'badge text-bg-success'
                            : 'badge text-bg-warning';
                        status.textContent = data.status;

                        const tombolEdit = document.createElement('button');
                        tombolEdit.type = 'button';
                        tombolEdit.className = 'btn btn-primary btn-sm mt-2 mx-auto  btn-edit-siswa';
                        tombolEdit.textContent = 'Edit';
                        tombolEdit.dataset.id = data.id;
                        
                        const tombolHapus = document.createElement('button');
                        tombolHapus.type = 'button';
                        tombolHapus.className = 'btn btn-danger btn-sm mt-2 mx-auto btn-hapus-siswa ';
                        tombolHapus.textContent = 'Hapus';
                        tombolHapus.dataset.id = data.id;

                        card.append(nama, sekolah,status, tombolEdit, tombolHapus);

                        kolom.appendChild(card);
                        daftar.appendChild(kolom);
                        card.appendChild(tombolEdit);
                        card.appendChild(tombolHapus); 

                        
                    });
                    pesan.textContent =
                    'Berhasil memuat ' + hasil.data.length + ' siswa';
                })
                .catch(error => {
                    pesan.textContent = error.message;
                    console.error(error);
                });
        }
        tampilkanSiswa();

//fungsi tambah data siswa
document.addEventListener('DOMContentLoaded', function () {
    tampilkanSiswa();
    const form = document.getElementById('tambahSiswa');
    const daftar = document.getElementById('daftarSiswa');
    const pesan = document.getElementById('pesanForm');
    const tombolTambah = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const textAwal = tombolTambah.textContent;
        tombolTambah.disabled = true;
        tombolTambah.textContent = 'Sedang menyimpan...';
        pesan.textContent = 'Menyimpan data...';

    try {
        const formData = new FormData(form);

        const data = {
            nama: formData.get('nama'),
            sekolah: formData.get('sekolah'),
            status: formData.get('status')
        };
   
            const response = await fetch('/api/siswa', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': form.querySelector(
                        'input[name="_token"]'
                    ).value
                },
                body: JSON.stringify(data)
            });

            const hasil = await response.json();

            if (!response.ok) {
                if (hasil.errors) {
                    pesan.textContent = Object.values(hasil.errors)
                        .flat()
                        .join(' ');
                } else {
                    pesan.textContent = hasil.message
                        || 'Gagal menambahkan siswa.';
                }
                return;
            }

            pesan.textContent = hasil.message;
            form.reset();
            await tampilkanSiswa();

        } catch (error) {
            pesan.textContent = 'Terjadi kesalahan saat menghubungi server.';
            console.error(error);
        } finally {
            tombolTambah.disabled = false;
            tombolTambah.textContent = textAwal;
        }
    });

    //tombol edit pada card data siswa
    const daftarSiswa = document.getElementById('daftarSiswa');

    daftarSiswa.addEventListener('click', async function (event) {
        const tombolEdit = event.target.closest('.btn-edit-siswa');

        if (!tombolEdit) {
            return;
        }
        const editId = tombolEdit.dataset.id;
        const kartu = tombolEdit.closest('.card-daftar');

        const editNama = kartu.querySelector('h5').textContent;
        const editSekolah = kartu.querySelector('p').textContent.replace('Sekolah : ', '');
        const editStatus = kartu.querySelector('.badge').textContent;

        document.getElementById('editId').value = editId;
        document.getElementById('editNama').value = editNama;
        document.getElementById('editSekolah').value = editSekolah;
        document.getElementById('editStatus').value = editStatus;

        document.getElementById('formEditSiswa').hidden = false;
        document.getElementById('pesanEdit').textContent = '';

        document.getElementById('formEditSiswa').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
    //fungsi hapus
    daftarSiswa.addEventListener('click', async function(event){
        const tombol = event.target.closest('.btn-hapus-siswa');

        if (!tombol) {
            return;
        }

        const id = tombol.dataset.id;

        if (!confirm('Yakin ingin menghapus siswa ini?')) {
            return;
        }

        tombol.disabled = true;

        try {
            const response = await fetch(`/api/siswa/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        '#tambahSiswa input[name="_token"]'
                    ).value
                }
            });

            const hasil = await response.json();

            if (!response.ok) {
                throw new Error(
                    hasil.message || 'Gagal menghapus siswa.'
                );
            }

            document.getElementById('pesanForm').textContent =
                hasil.message;

            // Ambil ulang daftar siswa dari Laravel
            tampilkanSiswa();
        } catch (error) {
            document.getElementById('pesanForm').textContent =
                error.message;

            console.error(error);
            tombol.disabled = false;
        }
    });
    //fungsi edit data siswa
    const formEdit = document.getElementById('editSiswa');

    formEdit.addEventListener('submit', async function(event) {
        event.preventDefault();

        const idEdit = document.getElementById('editId').value;
        const pesanEdit = document.getElementById('pesanEdit');
        
        const data = {
            nama: document.getElementById('editNama').value,
            sekolah: document.getElementById('editSekolah').value,
            status: document.getElementById('editStatus').value
        };
        pesanEdit.textContent = 'Menyimpan data.....';
        try {
            const response = await fetch(`/api/siswa/${idEdit}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': formEdit.querySelector(
                        'input[name="_token"]'
                    ).value
                },
                body :JSON.stringify(data)
            });
            const hasil = await response.json();

            if(!response.ok) {
                if (hasil.errors) {
                    throw new Error(
                        Object.values(hasil.errors).flat().join(' ')
                    );
                }

                throw new Error(
                    hasil.message || 'Gagal memperbarui siswa.'
                );
            }
            pesanEdit.textContent = hasil.message;

            await tampilkanSiswa();

            document.getElementById('formEditSiswa').hidden = true;

        } catch (error) {
            pesanEdit.textContent = error.message;
            console.error(error);
        }
    });
});
const batalEdit = document.getElementById('batalEdit');
const formEditSiswa = document.getElementById('formEditSiswa');

batalEdit.addEventListener('click', function() {
    formEditSiswa.hidden = true;
});