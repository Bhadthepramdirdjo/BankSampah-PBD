function openModal() {
    // Reset Form
    document.getElementById('modalTitle').innerText = "Tambah Transaksi";
    document.getElementById('id_setoran').value = "";
    document.getElementById('id_nasabah').value = "";
    document.getElementById('id_jenis').value = "";
    document.getElementById('berat').value = "";
    document.getElementById('catatan').value = "";
    document.getElementById('total_display').value = "";

    document.getElementById('btnSimpan').style.display = "block";
    document.getElementById('btnUpdate').style.display = "none";
    updateInfo(); // Reset satuan display

    document.getElementById('modalForm').style.display = "flex";
}

function closeModal() {
    document.getElementById('modalForm').style.display = "none";
}

function edit(id, idNasabah, idJenis, berat, catatan) {
    document.getElementById('modalTitle').innerText = "Edit Transaksi";
    document.getElementById('id_setoran').value = id;
    document.getElementById('id_nasabah').value = idNasabah;
    document.getElementById('id_jenis').value = idJenis;
    document.getElementById('berat').value = berat;
    document.getElementById('catatan').value = catatan;

    // Update info satuan & harga berdasarkan idJenis yang terpilih
    updateInfo(); 
    
    // Hitung total manual karena onchange tidak trigger otomatis
    hitungTotal(); 

    document.getElementById('btnSimpan').style.display = "none";
    document.getElementById('btnUpdate').style.display = "block";

    document.getElementById('modalForm').style.display = "flex";
}

function updateInfo() {
    var select = document.getElementById('id_jenis');
    var selectedOption = select.options[select.selectedIndex];
    
    // Safety check jika option kosong
    if(!selectedOption) return;

    var satuan = selectedOption.getAttribute('data-satuan');
    if(satuan) {
        document.getElementById('labelSatuan').innerText = satuan;
    } else {
        document.getElementById('labelSatuan').innerText = 'kg';
    }
    hitungTotal();
}

function hitungTotal() {
    var berat = parseFloat(document.getElementById('berat').value) || 0;
    var select = document.getElementById('id_jenis');
    var selectedOption = select.options[select.selectedIndex];
    
    // Safety check
    if(!selectedOption) return;
    
    var harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
    var total = berat * harga;
    document.getElementById('total_display').value = "Rp " + total.toLocaleString('id-ID');
}

// Close modal if user clicks outside content
window.onclick = function(event) {
    var modal = document.getElementById('modalForm');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
