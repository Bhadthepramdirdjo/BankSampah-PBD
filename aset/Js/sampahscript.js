function openModal() {
    document.getElementById('modalForm').style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Tambah Jenis Sampah';
    document.getElementById('btnSimpan').style.display = 'block';
    document.getElementById('btnUpdate').style.display = 'none';
    // Reset
    document.getElementById('jenis').value = '';
    document.getElementById('satuan').value = '';
    document.getElementById('harga').value = '';
    // Reset ID agar tidak tertinggal
    document.getElementById('id_jenis').value = '';
}

function closeModal() {
    document.getElementById('modalForm').style.display = 'none';
}

function edit(id, jenis, satuan, harga) {
    document.getElementById('modalForm').style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Edit Jenis Sampah';
    document.getElementById('btnSimpan').style.display = 'none';
    document.getElementById('btnUpdate').style.display = 'block';

    document.getElementById('id_jenis').value = id;
    document.getElementById('jenis').value = jenis;
    document.getElementById('satuan').value = satuan;
    document.getElementById('harga').value = harga;
}

window.onclick = function(event) {
    if (event.target == document.getElementById('modalForm')) {
        closeModal();
    }
}
