<?php
session_start();
include '../config/function.php';
$konfigurasi = new Konfigurasi();
$login = new Login();
$isadmin = false;
$nomor = 1;
if (!isset($_SESSION['login'])) {
  $_SESSION['error'] = 'Anda harus login terlebih dahulu!';
  header("location:/inventaris/login.php");
}
if ($_SESSION['level'] == "administrator" || $_SESSION['level'] == "operator") {
	$isadmin = true;
}
$ids = $_SESSION['id_petugas'];
$id = $_GET['rowid'];
$detail = $konfigurasi->detailAset($id);

if (isset($_POST['updateyes'])) {
    $kode_inventaris = $konfigurasi->con->real_escape_string($_POST['kode_inventaris']);
    $nama_barang = $konfigurasi->con->real_escape_string($_POST['nama_barang']);
    $spesifikasi = $konfigurasi->con->real_escape_string($_POST['spesifikasi']);
    $jumlah = $konfigurasi->con->real_escape_string($_POST['jumlah']);
    $kondisi = $konfigurasi->con->real_escape_string($_POST['kondisi']);
    $status = $konfigurasi->con->real_escape_string($_POST['status']);
    $id = $konfigurasi->con->real_escape_string($_POST['id']);

    $namaFile = $_FILES['image']['name'];
    $namaSamaran = $_FILES['image']['tmp_name'];
    $fotoBaru = date('dmYHis').$namaFile;
    $terupload = move_uploaded_file($namaSamaran,"../upload-image/aset/".$fotoBaru);

    $insert = $konfigurasi->updateAset($kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$id,$fotoBaru);
    if ($insert) {
        echo "<script type='text/javascript'>
                        alert('Data tersimpan ke database.');
                    </script>";
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "<script type='text/javascript'>
                        alert('Data tidak tersimpan ke database.');
                    </script>";
        echo "<meta http-equiv='refresh' content='0'>";
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<?php include '../include/meta.php'; ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body onload="userFormDisabled(),viewInventaris()">
		<?php include '../include/menu.php' ?>
		<div class="container">
		  <?php include '../include/heading.php'; ?>

			<div class="panel panel-success">
			  <div class="panel-heading">
			    <h3 class="panel-title"><i class="fa fa-edit"></i> Update Inventaris</h3>
			  </div>
			  <div class="panel-body">
              <form class="" action="" method="post" enctype="multipart/form-data">
						<?php	if ($detail == null) {
							echo "<h2 class='text-center'>Tidak ada data.</h2>";
						} else {
							foreach ($detail as $data) { ?>
									<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
										<div class="form-group">
											<label for="">Kode Inventaris :</label>
											<input type="text" class="form-control" id="kode_inventaris" value="<?php echo $data['kode_inventaris']; ?>" name="kode_inventaris" readonly>
                                        </div>
										<div class="form-group">
											<label for="">Nama Barang :</label>
											<input type="text" class="form-control" id="nama_barang" value="<?php echo $data['nama_barang']; ?>" name="nama_barang">
                                        </div>

										<div class="form-group">
											<label for="">Kondisi :</label>
											<select class="form-control" name="kondisi" id="kondisi">
                                                <option value="<?php echo $data['kondisi']; ?>"><?php echo $data['kondisi']; ?></option>
												<option>Sangat Baik</option>
												<option>Baik</option>
												<option>Rusak</option>
											</select>
										</div>
										<div class="form-group">
											<label for="">Spesifikasi :</label>
											<textarea name="spesifikasi" id="spesifikasi" class="form-control" rows="4"><?php echo $data['spesifikasi']; ?></textarea>
										</div>
										<div class="form-group">
											<label for="">Jumlah :</label>
											<input type="number" class="form-control" id="jumlah" value="<?php echo $data['jumlah']; ?>" name="jumlah">
										</div>

										<div class="form-group">
											<label for="">Status :</label>
											<select class="form-control" name="status" id="status">
                                                <option value="<?php echo $data['status']; ?>"><?php echo $data['status']; ?></option>
												<option value="diperbaiki">diperbaiki</option>
												<option value="dihapus">dihapus</option>
												<option value="dihibahkan">dihibahkan</option>
											</select>
										</div>
                                        <div class="form-group">
                                            <label for="exampleFormControlFile1">Example file input</label>
                                            <input type="file" class="form-control-file" id="exampleFormControlFile1" name="image">
                                        </div>

						<?php	} } ?>
			  </div>
			  <div class="panel-footer">
          <button type="button" id="btnedit" onclick="editInventaris()" class="btn btn-success"><i class="fa fa-edit"></i> Edit</button>
  				<button type="button" class="btn btn-primary" id="btnupdate"  style="float:left;margin-right:5px;" data-toggle="modal" data-target="#konfirmupdate"><i class="fa fa-check"></i> Simpan</button>
  					<a href="/inventaris/aset.php" class="btn btn-danger"><i class="fa fa-forward"></i> Kembali</a>

			  </div>
			</div>
			<?php include '../include/confirm-update.php'; ?>
		</form>

		</div>
		<!-- Footer -->
    <?php include '../include/footer.php'; ?>
		<?php include '../include/profile.php'; ?>
		<!--- modal confirm -->

		<?php include '../include/confirm-logout.php'; ?>
	</body>
</html>
