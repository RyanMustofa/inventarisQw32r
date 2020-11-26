
<?php
include 'include/session.php';
if ($isadmin) {

    $aset = $konfigurasi->barangMasuk();
    if (isset($_POST['yes'])) {
        $nama_barang = $konfigurasi->con->real_escape_string(
            $_POST['nama_barang']
        );
        $spesifikasi = $konfigurasi->con->real_escape_string(
            $_POST['spesifikasi']
        );
        $jumlah = $konfigurasi->con->real_escape_string($_POST['jumlah']);
        $kondisi = $konfigurasi->con->real_escape_string($_POST['kondisi']);
        $status = $konfigurasi->con->real_escape_string($_POST['status']);
        $kode_inventaris = $konfigurasi->con->real_escape_string(
            $_POST['kode_inventaris']
        );
        $namaFile = $_FILES['image']['name'];
        $namaSamaran = $_FILES['image']['tmp_name'];
        $fotoBaru = date('dmYHis').$namaFile;
        $terupload = move_uploaded_file($namaSamaran,"upload-image/barang-masuk/".$fotoBaru);

        $kirim = $konfigurasi->insertBarangMasuk(
            $nama_barang,
            $spesifikasi,
            $jumlah,
            $kondisi,
            $status,
            $kode_inventaris,
            $fotoBaru
        );
        if ($kirim) {
            echo "<script>alert('success insert')</script>";
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "<script>alert('gagal insert cek kode inventaris')</script>";
            echo "<meta http-equiv='refresh' content='0'>";
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<?php include 'include/meta.php'; ?>

  </head>
  <body onload="userFormDisabled()">
		<?php include 'include/menu.php'; ?>
		<div class="container">
		  <?php include 'include/heading.php'; ?>
			<div class="form-group">
				<button type="button" class="btn btn-default" id="btnPlus">
				  <i class="fa fa-plus"></i> Tambah Barang Masuk
				</button>
				<button type="button" id="" class="btn btn-primary" onclick="printInventaris()">
				  <i class="fa fa-print"></i> Cetak Data
				</button>
			</div>
			<div class="panel panel-success" id="formInput" hidden>
			  <div class="panel-heading">
			    <h3 class="panel-title">Inentaris SMK PENDA 2 KARANGANYAR</h3>
			  </div>
			  <div class="panel-body">
					<form class="" action="barang-masuk.php" method="post" enctype="multipart/form-data">
						<div class="form-group">
						  <label for="">Nama Barang:</label>
						  <input type="text" class="form-control" id="nama_inventaris" placeholder="Nama Barang" name="nama_barang">
						</div>
						<div class="form-group">
						  <label for="">Spesifikasi :</label>
						  <textarea type="text" rows="4" class="form-control" placeholder="Spesifikasi" name="spesifikasi"></textarea>
						</div>
						<div class="form-group">
						  <label for="">Jumlah :</label>
                          <input name="jumlah" class="form-control" placeholder="Jumlah Barang">
                        </div>
						<div class="form-group">
                          <label for="">Kondisi :</label>
                           <select class="form-control" name="kondisi">
                                <option value="sangat baik">sangat baik</option>
                                <option value="baik">baik</option>
                                <option value="rusak">rusak</option>
                            </select>
						</div>
						<div class="form-group">
                          <label for="">Status :</label>
                           <select class="form-control" name="status">
                                <option value="diperbaiki">diperbaiki</option>
                                <option value="dihapus">dihapus</option>
                                <option value="dihibahkan">dihibahkan</option>
                            </select>
						</div>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Foto Barang</label>
                            <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1">
                        </div>
						<div class="form-group">
						  <label for="">Kode Inventaris :</label>
						  <input type="text" class="form-control" placeholder="Kode Inventaris" name="kode_inventaris">
						  <p class="help-block"><b>KODE HARUS SAMA</b><i>  Kode terdiri dari huruf dan angka. Contoh : A001B200</i></p>
						</div>
			  </div>
			  <div class="panel-footer">
						<div class="text-right">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#konfirm">
							  <i class="fa fa-check"></i> Simpan
							</button>
							<button type="button" class="btn btn-danger" id="btnBatal">
							  <i class="fa fa-close"></i> Batal
							</button>
						</div>
			  </div>
				<?php include 'include/confirm-data.php'; ?>
				</form>
			</div>

			<?php if ($aset == null) {
       echo "<hr><h2 class='text-center'>Tidak ada data</h2><hr>";
   } else {
        ?>
				<table class="table">
					<th>Nomor</th>
					<th>Kode Inventaris</th>
					<th>Nama Barang</th>
					<th>spesifikasi</th>
					<th>Jumlah</th>
					<th>kondisi</th>
                    <th>status</th>
                    <th>Image</th>
					<?php if ($isadmin): ?>
						<th>Opsi</th>
					<?php endif; ?>
			<?php foreach ($aset as $data): ?>

			<tr>
				<td><?php echo $nomor++; ?></td>
				<td><?php echo $data['kode_investaris']; ?></td>
				<td><?php echo $data['nama_barang']; ?></td>
				<td><?php echo $data['spesifikasi']; ?></td>
				<td><?php echo $data['jumlah']; ?></td>
				<td><?php echo $data['kondisi']; ?></td>
                <td><?php echo $data['status']; ?></td>
                <td><img class="img-fluid" height="100" width="100" src="upload-image/barang-masuk/<?php echo $data['image']; ?>" alt="image" style="border-radius: 10px"></td>
				<?php if ($isadmin): ?>
					<td>
						<a href="detail/detail-barang-masuk.php?rowid=<?php echo $data[
          'id'
      ]; ?>"><i class="fa fa-eye"></i></a> |
                        <a href="hapus/hapus-barang-masuk.php?rowid=<?php echo $data[
                            'id'
                        ]; ?>&file=<?php echo $data['image']; ?>"><i class="fa fa-trash"></i></a>
					</td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php
   } ?>

		</div>
		<!-- Footer -->
    <?php include 'include/footer.php'; ?>
		<?php include 'include/profile.php'; ?>
		<!--- modal confirm -->

		<?php include 'include/confirm-logout.php'; ?>
	</body>
</html>
<script language="JavaScript" type="text/javascript">
    function hapusData(id){
      if (confirm("Apakah anda yakin akan menghapus data ini?")){
        window.location.href = 'hapus/hapus-inventaris.php?id=' + id;
      }
    }
		function printInventaris() {
			window.open('cetak/cetak-inventaris.php','_blank');
		}
</script>
<?php
} else {
    echo "<h2>Maaf, anda tidak memiliki akses di halaman ini.</h2>";
    echo "<br>";
    echo "Silahkan kembali ke beranda. <a href='/inventaris/index.php'>Kembali</a>";
} ?>
