function openModal() {
    document.getElementById('modalForm').style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Tambah Nasabah Baru';
    document.getElementById('id_nasabah').value = ''; 
    document.getElementById('nama').value = '';
    document.getElementById('hp').value = '';
    document.getElementById('alamat').value = '';
    
    var btn = document.getElementById('btnSimpan');
    btn.innerText = 'Simpan Data';
    btn.className = 'btn btn-green';
}

function closeModal() {
    document.getElementById('modalForm').style.display = 'none';
}

function edit(id, nama, hp, alamat) {
    document.getElementById('modalForm').style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Edit Data Nasabah';
    document.getElementById('id_nasabah').value = id;
    document.getElementById('nama').value = nama;
    document.getElementById('hp').value = hp;
    document.getElementById('alamat').value = alamat;

    var btn = document.getElementById('btnSimpan');
    btn.innerText = 'Update Data';
    btn.className = 'btn btn-blue';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('modalForm')) {
        closeModal();
    }
}
