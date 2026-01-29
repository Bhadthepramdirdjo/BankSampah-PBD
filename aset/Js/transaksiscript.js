function toggleForm() {
    var form = document.getElementById('formContainer');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

function updateInfo() {
    var select = document.getElementById('id_jenis');
    var selectedOption = select.options[select.selectedIndex];
    var satuan = selectedOption.getAttribute('data-satuan');
    if(satuan) {
        document.getElementById('labelSatuan').innerText = satuan;
    } else {
        document.getElementById('labelSatuan').innerText = 'Satuan';
    }
    hitungTotal();
}

function hitungTotal() {
    var berat = parseFloat(document.getElementById('berat').value) || 0;
    var select = document.getElementById('id_jenis');
    var harga = parseFloat(select.options[select.selectedIndex].getAttribute('data-harga')) || 0;
    var total = berat * harga;
    document.getElementById('total_display').value = "Rp " + total.toLocaleString('id-ID');
}
