<?php
// Untuk koneksi ke database
class Konektor {
public $host;
public $user;
public $pass;
public $db;
public $con;

	public function __construct() {

		$this->host = "localhost";
		$this->user = "root";
		$this->pass = "";
		$this->db = "inventaris_coba_1";
		$this->con = new mysqli($this->host,$this->user,$this->pass,$this->db) or die("Can't Connect to Database");
	}
	public function __destruct() {
		$this->con->close();
	}
}

/*
* Untuk method-method
* konektor di wariskan ke class konfigurasi
*/

//method login
class Login extends Konektor {
	//Set Login
	public $SQL;
	public function setLogin($user,$pass) {
		$this->SQL = $this->con->prepare("SELECT * FROM petugas INNER JOIN level ON level.id_level = petugas.id_level WHERE username=? AND password=?");
		$this->SQL->bind_param("ss",$user,$pass);
		$this->SQL->execute();
		return true;
	}
	//Get Login
    public function getLogin(){
        var_dump($this->SQL);
		$result = $this->SQL->get_result();
		$rows = count($result);
		if($rows == 1) {
			foreach ($result as $data) {
					if ($data['nama_level'] == 'administrator') {
						$_SESSION['level'] = 'administrator';
					} else if($data['nama_level'] == 'operator') {
						$_SESSION['level'] = 'operator';
					} else if($data['nama_level'] == 'peminjam') {
						$_SESSION['level'] = 'peminjam';
					}
					$_SESSION['id_petugas'] = $data['id_petugas'];
					$_SESSION['nama_petugas'] = $data['nama_petugas'];
					$_SESSION['username'] = $data['username'];
					$_SESSION['jumlah'] = $rows;
					$_SESSION['login'] = true;
				return true;
			}
		} else {
			return false;
		}
	}
		//Logout
		public function setLogout() {
			session_destroy();
			header("location:login.php");
			return true;
		}
}

//Method untuk Konfigurasi Lain

//Function Show All Data
class Konfigurasi extends Konektor {
	public function select($tbl,$where,$val) {
		if ($where == "") {
			$SQL = $this->con->prepare(" SELECT * FROM ".$tbl);
		} else {
			$SQL = $this->con->prepare(" SELECT * FROM ".$tbl." WHERE ".$where." =?");
			$SQL->bind_param("s",$val);
		}
		$SQL->execute();
		$SQL->store_result();
		return $SQL;
	}

	public function profie($ids) {
		$id = $this->con->real_escape_string($ids);
		$SQL = $this->con->prepare("SELECT * FROM petugas INNER JOIN level ON level.id_level = petugas.id_level WHERE id_petugas=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function selectLevel() {
		$SQL = $this->con->prepare("SELECT * FROM level");
		$SQL->execute();

		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		return $data;

	}

	public function selectRuangan() {
		$SQL = $this->con->prepare("SELECT * FROM ruang ORDER BY id_ruang DESC");
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}

		return $data;
	}

	public function selectJenis() {
		$SQL = $this->con->prepare("SELECT * FROM jenis");
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}

	public function selectInventaris() {
		$SQL = $this->con->prepare("SELECT * FROM inventaris INNER JOIN jenis ON jenis.id_jenis = inventaris.id_jenis
																INNER JOIN ruang ON ruang.id_ruang = inventaris.id_ruang
																INNER JOIN petugas ON petugas.id_petugas = inventaris.id_petugas
															  GROUP BY inventaris.id_inventaris");
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function selectPetugas()
	{
		$SQL = $this->con->prepare("SELECT * FROM petugas INNER JOIN level ON level.id_level = petugas.id_level GROUP BY petugas.id_petugas");
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function selectPegawai()
	{
		$SQL = $this->con->prepare("SELECT * FROM pegawai");
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function selectPeminjaman()
	{
		$SQL = $this->con->prepare("SELECT * FROM detail_pinjam
																INNER JOIN peminjaman ON peminjaman.id_peminjaman = detail_pinjam.id_peminjaman
																INNER JOIN inventaris ON inventaris.id_inventaris = detail_pinjam.id_inventaris
																INNER JOIN petugas ON petugas.id_petugas = peminjaman.id_petugas
																INNER JOIN pegawai ON pegawai.id_pegawai = peminjaman.id_pegawai
																INNER JOIN jenis ON jenis.id_jenis = inventaris.id_jenis
																INNER JOIN ruang ON ruang.id_ruang = inventaris.id_ruang ");
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}



	//bagian detail
	public function detailInventaris($id) {
		$SQL = $this->con->prepare("SELECT * FROM inventaris INNER JOIN jenis ON jenis.id_jenis = inventaris.id_jenis
																INNER JOIN ruang ON ruang.id_ruang = inventaris.id_ruang
																INNER JOIN petugas ON petugas.id_petugas = inventaris.id_petugas
																WHERE inventaris.id_inventaris = ? GROUP BY inventaris.id_inventaris");
		$SQL->bind_param("s",$id);
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function detailRuangan($id)
	{
		$SQL = $this->con->prepare("SELECT * FROM ruang WHERE id_ruang=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function detailJenis($id)
	{
		$SQL = $this->con->prepare("SELECT * FROM jenis WHERE id_jenis=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function detailPetugas($id)
	{
		$SQL = $this->con->prepare("SELECT * FROM petugas INNER JOIN level ON level.id_level=petugas.id_level WHERE id_petugas=? GROUP BY petugas.id_petugas");
		$SQL->bind_param("s",$id);
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}
	public function detailPegawai($id)
	{
		$SQL = $this->con->prepare("SELECT * FROM pegawai WHERE id_pegawai=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();
		$result = $SQL->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}


	//bagian insert
	public function insertInventaris($nama,$kondisi,$keterangan,$jumlah,$id_jenis,$tanggal_register,$id_ruang,$kode_inventaris,$id_petugas) {
		$id = uniqid();
		$SQL = $this->con->prepare("INSERT INTO `inventaris` (`id_inventaris`,`nama`, `kondisi`, `keterangan_inventaris`, `jumlah`, `id_jenis`, `tanggal_register`, `id_ruang`, `kode_inventaris`, `id_petugas`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$SQL->bind_param("ssssisssss",$id,$nama,$kondisi,$keterangan,$jumlah,$id_jenis,$tanggal_register,$id_ruang,$kode_inventaris,$id_petugas);
		$SQL->execute();

		return $SQL;
	}
	public function insertRuangan($nama_ruang,$kode_ruang,$keterangan_ruang)
	{
		$id = uniqid();
		$SQL = $this->con->prepare("INSERT INTO ruang (id_ruang,nama_ruang,kode_ruang,keterangan_ruang) VALUES (?,?,?,?)");
		$SQL->bind_param("ssss",$id,$nama_ruang,$kode_ruang,$keterangan_ruang);
		$SQL->execute();

		return $SQL;
	}
	public function insertJenis($nama_jenis,$kode_jenis,$keterangan_jenis)
	{
		$id = uniqid();
		$SQL = $this->con->prepare("INSERT INTO jenis (id_jenis,nama_jenis,kode_jenis,keterangan_jenis) VALUES (?,?,?,?)");
		$SQL->bind_param("ssss",$id,$nama_jenis,$kode_jenis,$keterangan_jenis);
		$SQL->execute();

		return $SQL;
	}
	public function insertPetugas($username,$password,$nama_petugas,$id_level)
	{
		$id = uniqid();
		$SQL = $this->con->prepare("INSERT INTO petugas (id_petugas,username,password,nama_petugas,id_level) VALUES (?,?,?,?,?)");
		$SQL->bind_param("ssssi",$id,$username,$password,$nama_petugas,$id_level);
		$SQL->execute();

		return $SQL;
	}
	public function insertPegawai($nama_pegawai,$nip_pegawai,$alamat_pegawai)
	{
		$id = uniqid();
		$SQL = $this->con->prepare("INSERT INTO pegawai (id_pegawai,nama_pegawai,nip,alamat) VALUES (?,?,?,?)");
		$SQL->bind_param("ssis",$id,$nama_pegawai,$nip_pegawai,$alamat_pegawai);
		$SQL->execute();

		return $SQL;
	}
	public function insertPeminjaman($nama_petugas,$tanggal_pinjam,$tanggal_kembali,$nama_peminjam,$nama_inventaris,$banyak_pinjam)
	{
		$id_peminjam = uniqid();
		$id_detail_pinjam = uniqid();
		$status_pinjam = 'belum dikembalikan';

		$SQL1 = $this->con->prepare("INSERT INTO `peminjaman` (`id_peminjaman`, `id_petugas`, `tanggal_pinjam`, `tanggal_kembali`, `status_peminjaman`, `id_pegawai`) VALUES (?,?,?,?,?,?)");
		$SQL1->bind_param("ssssss",$id_peminjam,$nama_petugas,$tanggal_pinjam,$tanggal_kembali,$status_pinjam,$nama_peminjam);
		$SQL1->execute();

		$SQL2 = $this->con->prepare("INSERT INTO `detail_pinjam` (`id_detail_pinjam`, `id_inventaris`, `id_peminjaman`, `jumlah_pinjam`) VALUES (?,?,?,?)");
		$SQL2->bind_param("sssi",$id_detail_pinjam,$nama_inventaris,$id_peminjam,$banyak_pinjam);
		$SQL2->execute();

		return TRUE;
	}


	//bagian update
	public function updateProfile($username,$password,$nama_profile,$id_petugas)
	{
		$SQL = $this->con->prepare("UPDATE petugas SET username=?, password=?, nama_petugas=? WHERE id_petugas=?");
		$SQL->bind_param("ssss",$username,$password,$nama_profile,$id_petugas);
		$SQL->execute();

		return $SQL;
	}

	public function updateInventaris($nama,$kondisi,$keterangan,$jumlah,$id_jenis,$tanggal_register,$id_ruang,$kode_inventaris,$id_petugas,$id_inventaris) {
		$SQL = $this->con->prepare("UPDATE inventaris SET nama=?, kondisi=?, keterangan_inventaris=?, jumlah=?, id_jenis=?, tanggal_register=?, id_ruang=?, kode_inventaris=?, id_petugas=? WHERE id_inventaris=?");
		$SQL->bind_param("ssssssssss",$nama,$kondisi,$keterangan,$jumlah,$id_jenis,$tanggal_register,$id_ruang,$kode_inventaris,$id_petugas,$id_inventaris);
		$SQL->execute();

		return $SQL;
	}
	public function updateRuangan($nama_ruang,$kode_ruang,$keterangan_ruang,$id_ruang)
	{
		$SQL = $this->con->prepare("UPDATE ruang SET nama_ruang=?, kode_ruang=?, keterangan_ruang=? WHERE id_ruang=?");
		$SQL->bind_param("ssss",$nama_ruang,$kode_ruang,$keterangan_ruang,$id_ruang);
		$SQL->execute();

		return $SQL;
	}
	public function updateJenis($nama_jenis,$kode_jenis,$keterangan_jenis,$id_jenis)
	{
		$SQL = $this->con->prepare("UPDATE jenis SET nama_jenis=?, kode_jenis=?, keterangan_jenis=? WHERE id_jenis=?");
		$SQL->bind_param("ssss",$nama_jenis,$kode_jenis,$keterangan_jenis,$id_jenis);
		$SQL->execute();

		return $SQL;
	}
	public function updatePetugas($username,$password,$nama_petugas,$id_level,$id_petugas)
	{
		$SQL = $this->con->prepare("UPDATE petugas SET username=?, password=?, nama_petugas=?, id_level=? WHERE id_petugas=?");
		$SQL->bind_param("sssis",$username,$password,$nama_petugas,$id_level,$id_petugas);
		$SQL->execute();

		return $SQL;
	}
	public function updatePegawai($nama_pegawai,$nip_pegawai,$alamat_pegawai,$id_pegawai)
	{
		$SQL = $this->con->prepare("UPDATE pegawai SET nama_pegawai=?, nip=?, alamat=? WHERE id_pegawai=?");
		$SQL->bind_param("siss",$nama_pegawai,$nip_pegawai,$alamat_pegawai,$id_pegawai);
		$SQL->execute();

		return $SQL;
	}
	public function verifPengembalian($id)
	{
		$status_pinjam='telah dikembalikan';
		$SQL = $this->con->prepare("UPDATE peminjaman SET status_peminjaman=? WHERE id_peminjaman=?");
		$SQL->bind_param("ss",$status_pinjam,$id);
		$SQL->execute();

		return $SQL;
	}


	//bagian delete
	public function hapusInventaris($id)
	{
		$SQL = $this->con->prepare("DELETE FROM inventaris WHERE id_inventaris=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();

		return $SQL;
	}
	public function hapusRuangan($id)
	{
		$SQL = $this->con->prepare("DELETE FROM ruang WHERE id_ruang=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();

		return $SQL;
	}
	public function hapusJenis($id)
	{
		$SQL = $this->con->prepare("DELETE FROM jenis WHERE id_jenis=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();

		return $SQL;
	}
	public function hapusPetugas($id)
	{
		$SQL = $this->con->prepare("DELETE FROM petugas WHERE id_petugas=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();

		return $SQL;
	}
	public function hapusPegawai($id)
	{
		$SQL = $this->con->prepare("DELETE FROM pegawai WHERE id_pegawai=?");
		$SQL->bind_param("s",$id);
		$SQL->execute();

		return $SQL;
	}
	public function hapusPeminjaman($id)
	{
		$SQL1 = $this->con->prepare("DELETE FROM peminjam");
	}
    public function pemeliharaanAset()
    {
        $SQL = $this->con->prepare("SELECT * FROM pemeliharaan_aset");
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function insertAset($nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$kode_inventaris,$namaFile)
    {
        $SQL = $this->con->prepare("INSERT INTO `pemeliharaan_aset` (`kode_inventaris`,`nama_barang`,`spesifikasi`,`jumlah`,`kondisi`,`status`,`image`) VALUES(?,?,?,?,?,?,?)");
        $SQL->bind_param("sssisss",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$namaFile);
        $SQL->execute();
        return $SQL;
    }
    public function detailAset($id)
    {
        $SQL = $this->con->prepare("SELECT * FROM pemeliharaan_aset WHERE id=?");
        $SQL->bind_param("i",$id);
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function updateAset($kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$id,$image)
    {
        $SQL = $this->con->prepare("UPDATE pemeliharaan_aset SET kode_inventaris=?, nama_barang=?,spesifikasi=? ,jumlah=? ,kondisi=? ,status=? ,image=? WHERE id=?");
        $SQL->bind_param("sssisssi",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$image,$id);
        $SQL->execute();
        
        return true;
    }
    public function hapusAset($id)
    {
        $SQL = $this->con->prepare("DELETE FROM pemeliharaan_aset WHERE id = ?");
        $SQL->bind_param('i',$id);
        $SQL->execute();
        
        return true;
    }

    //barang masuk
    public function barangMasuk()
    {
        $SQL = $this->con->prepare("SELECT * FROM barang_masuk");
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function insertBarangMasuk($nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$kode_inventaris,$namaFile)
    {
        $SQL = $this->con->prepare("INSERT INTO `barang_masuk` (`kode_investaris`,`nama_barang`,`spesifikasi`,`jumlah`,`kondisi`,`status`,`image`) VALUES(?,?,?,?,?,?,?)");
        $SQL->bind_param("sssisss",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$namaFile);
        $SQL->execute();
        return $SQL;
    }
    public function detailBarangMasuk($id)
    {
        $SQL = $this->con->prepare("SELECT * FROM barang_masuk WHERE id=?");
        $SQL->bind_param("i",$id);
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function updateBarangMasuk($kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$id,$image)
    {
        $SQL = $this->con->prepare("UPDATE barang_masuk SET kode_investaris=?, nama_barang=?,spesifikasi=? ,jumlah=? ,kondisi=? ,status=? ,image=? WHERE id=?");
        $SQL->bind_param("sssisssi",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$image,$id);
        $SQL->execute();
        
        return true;
    }
    public function hapusBarangMasuk($id)
    {
        $SQL = $this->con->prepare("DELETE FROM barang_masuk WHERE id = ?");
        $SQL->bind_param('i',$id);
        $SQL->execute();
        
        return true;
    }
    //Hibah
    public function hibah()
    {
        $SQL = $this->con->prepare("SELECT * FROM penghibahan");
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function insertHibah($nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$kode_inventaris,$namaFile)
    {
        $SQL = $this->con->prepare("INSERT INTO `penghibahan` (`kode_investaris`,`nama_barang`,`spesifikasi`,`jumlah`,`kondisi`,`status`,`image`) VALUES(?,?,?,?,?,?,?)");
        $SQL->bind_param("sssisss",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$namaFile);
        $SQL->execute();
        return $SQL;
    }
    public function detailHibah($id)
    {
        $SQL = $this->con->prepare("SELECT * FROM penghibahan WHERE id=?");
        $SQL->bind_param("i",$id);
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function updateHibah($kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$id,$image)
    {
        $SQL = $this->con->prepare("UPDATE penghibahan SET kode_investaris=?, nama_barang=?,spesifikasi=? ,jumlah=? ,kondisi=? ,status=? ,image=? WHERE id=?");
        $SQL->bind_param("sssisssi",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$image,$id);
        $SQL->execute();
        
        return true;
    }
    public function hapusHibah($id)
    {
        $SQL = $this->con->prepare("DELETE FROM penghibahan WHERE id = ?");
        $SQL->bind_param('i',$id);
        $SQL->execute();
        
        return true;
    }
    //penghapusan
    public function penghapusan()
    {
        $SQL = $this->con->prepare("SELECT * FROM penghapusan");
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function insertPenghapusan($nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$kode_inventaris,$namaFile)
    {
        $SQL = $this->con->prepare("INSERT INTO `penghapusan` (`kode_investaris`,`nama_barang`,`spesifikasi`,`jumlah`,`kondisi`,`status`,`image`) VALUES(?,?,?,?,?,?,?)");
        $SQL->bind_param("sssisss",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$namaFile);
        $SQL->execute();
        return $SQL;
    }
    public function detailPenghapusan($id)
    {
        $SQL = $this->con->prepare("SELECT * FROM penghapusan WHERE id=?");
        $SQL->bind_param("i",$id);
        $SQL->execute();
        $result = $SQL->get_result();
        $data = [];
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }
    public function updatePenghapusan($kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$id,$image)
    {
        $SQL = $this->con->prepare("UPDATE penghapusan SET kode_investaris=?, nama_barang=?,spesifikasi=? ,jumlah=? ,kondisi=? ,status=? ,image=? WHERE id=?");
        $SQL->bind_param("sssisssi",$kode_inventaris,$nama_barang,$spesifikasi,$jumlah,$kondisi,$status,$image,$id);
        $SQL->execute();
        
        return true;
    }
    public function hapusPenghapusan($id)
    {
        $SQL = $this->con->prepare("DELETE FROM penghapusan WHERE id = ?");
        $SQL->bind_param('i',$id);
        $SQL->execute();
        
        return true;
    }
}

 ?>
