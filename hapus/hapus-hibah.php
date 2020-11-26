

<?php
include '../config/function.php';
$konfigurasi = new Konfigurasi();
$id = $_GET['rowid'];
$file = $_GET['file'];
$hapus = $konfigurasi->hapusHibah($id);
$path = "../upload-image/hibah/".$file;
if(is_file($path)){
    unlink($path);
}
if ($hapus) {
    echo "<script type='text/javascript'>
                    alert('Data berhasil dihapus.');
                    window.location='../hibah.php';
                </script>";
    // echo "<meta http-equiv='refresh' content='0'>";
}
