<?php
session_start();
//Membuat koneksi
$conn = mysqli_connect("localhost","root","","stokbarang");


// tambah barang
if (isset($_POST['addnewbarang'])) {
	$namabarang = $_POST['namabarang'];
	$deskripsi = $_POST['deskripsi'];
	$stok = $_POST['stok'];

	$addtotable = mysqli_query($conn,"insert into stok (namabarang, deskripsi, stok) values('$namabarang','$deskripsi','$stok')");
	if ($addtotable) {
		header('location:index.php');
	} else {
		echo 'gagal';
		header('location:index.php');
	}
}

//Menambah barang masuk
if (isset($_POST['barangmasuk'])){
	$barangnya = $_POST['barangnya'];
	$penerima = $_POST ['penerima'];
	$qty = $_POST ['qty'];

	$cekstoksekarang = mysqli_query($conn,"select * from stok where idbarang='$barangnya'");
	$ambildatanya = mysqli_fetch_array($cekstoksekarang);

	$stoksekarang = $ambildatanya['stok'];
	$tambahkanstoksekarangdenganquantity = $stoksekarang+$qty;

	$addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty')");
	$updatestokmasuk = mysqli_query($conn,"update stok set stok='$tambahkanstoksekarangdenganquantity' where idbarang='$barangnya'");
		if ($addtomasuk&&$updatestokmasuk){
		header('location:masuk.php');
	} else {
		echo 'Gagal';
		header('location:masuk.php');
	}
}

//Menambah barang keluar
if (isset($_POST['addbarangkeluar'])){
	$barangnya = $_POST['barangnya'];
	$penerima = $_POST ['penerima'];
	$qty = $_POST ['qty'];

	$cekstoksekarang = mysqli_query($conn,"select * from stok where idbarang='$barangnya'");
	$ambildatanya = mysqli_fetch_array($cekstoksekarang);

	$stoksekarang = $ambildatanya['stok'];
	$tambahkanstoksekarangdenganquantity = $stoksekarang-$qty;

	$addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty')");
	$updatestokkeluar = mysqli_query($conn,"update stok set stok='$tambahkanstoksekarangdenganquantity' where idbarang='$barangnya'");
		if ($addtokeluar&&$updatestokmasuk){
		header('location:keluar.php');
	} else {
		echo 'Gagal';
		header('location:keluar.php');
	}
}

//Update Info barang
if(isset($_POST['updatebarang'])){
	$idb = $_POST['idb'];
	$namabarang = $_POST['namabarang'];
	$deskripsi = $_POST['deskripsi'];

	$update = mysqli_query($conn,"update stok set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang ='$idb'");
	if($update){
		header('location:index.php');
	} else {
		echo 'Gagal';
		header('location:index.php');
	}
}

//Hapus Barang
if(isset($_POST['hapusbarang'])){
	$idb = $_POST['idb'];

	$hapus = mysqli_query($conn, "delete from stok where idbarang='$idb'");
	if($hapus){
		header('location:index.php');
	} else {
		echo 'Gagal';
		header('location:index.php');
	}
};

//Mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
	$idb = $_POST['idb'];
	$idm = $_POST['idm'];
	$deskripsi = $_POST['keterangan'];
	$qty = $_POST['qty'];

	$lihatstok = mysqli_query($conn,"select * from stok where idbarang='idb'");
	$stoknya = mysqli_fetch_array($lihatstok);
	$stokskrg = $stoknya['stok'];

	$qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
	$qtynya = mysqli_fetch_array($qtyskrg);
	$qtyskrg = $qtynya['qty'];

	if($qty>$qtyskrg){
		$selisih = $qty-$qtyskrg;
		$kurangin = $stokskrg - $selisih;
		$kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangin' where idbarang='$idb'");
		$updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
			if($kurangistoknya&&$updatenya){
				header('location:masuk.php');
			} else {
				echo 'Gagal';
				header('location:masuk.php');
		}
	}else {
		$selisih = $qtyskrg-$qty;
		$kurangin = $stokskrg + $selisih;
		$kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangin' where idbarang='$idb'");
		$updatenya = mysqli_query($conn,"update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
		if($kurangistoknya&&$updatenya){
			header('location:masuk.php');
		} else {
			echo 'Gagal';
			header('location:masuk.php');
		}
	}
};

//Menghapus Barang Masuk
if(isset($_POST['hapusbarangmasuk'])){
	$idb = $_POST['idb'];
	$qty = $_POST['kty'];
	$idm = $_POST['idm']; 

	$hapus = mysqli_query($conn, "delete from stok where idbarang='$idb'");
	if($hapusbrgmasuk){
		header('location:index.php');
	} else {
		echo 'Gagal';
		header('location:index.php');
	}
};


//Login
if (isset($_POST['login'])){
	$email = $_POST['email'];
	$password = $_POST['password'];

	$cekuser = mysqli_query($conn,"select * from login where email='$email' and password='$password'");
	$hitung = mysqli_num_rows($cekuser);

	if ($hitung>0) {
		//jika data di temukan
		$ambildatastatus = mysqli_fetch_array($cekuser);
		$status = $ambildatastatus['status'];

		if ($status=='admin') {
			//jika user admin
			$_SESSION['log'] = 'Logged';
			$_SESSION['status'] = 'admin';
			header('location:../admin');
		} else{
			//jika user biasa
			$_SESSION['log'] = 'Logged';
			$_SESSION['status'] = 'user';
			header('location:../user');
		}
	}
}
?>