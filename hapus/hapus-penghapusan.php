

<?php
include '../config/function.php';
$konfigurasi = new Konfigurasi();
$id = $_GET['rowid'];
$file = $_GET['file'];
$hapus = $konfigurasi->hapusPenghapusan($id);
$path = "../upload-image/penghapusan/".$file;
if(is_file($path)){
    unlink($path);
}
if ($hapus) {
    echo "<script type='text/javascript'>
                    alert('Data berhasil dihapus.');
                    window.location='../penghapusan.php';
                </script>";
    // echo "<meta http-equiv='refresh' content='0'>";
}
