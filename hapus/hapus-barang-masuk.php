

<?php
include '../config/function.php';
$konfigurasi = new Konfigurasi();
$id = $_GET['rowid'];
$file = $_GET['file'];
$hapus = $konfigurasi->hapusBarangMasuk($id);
$path = "../upload-image/barang-masuk/".$file;
if(is_file($path)){
    unlink($path);
}
if ($hapus) {
    echo "<script type='text/javascript'>
                    alert('Data berhasil dihapus.');
                    window.location='../barang-masuk.php';
                </script>";
    // echo "<meta http-equiv='refresh' content='0'>";
}
