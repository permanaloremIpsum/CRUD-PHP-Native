<?php 
// koneksi ke database
$db = mysqli_connect("localhost", "root", "", "phpdasar");

function query($query){
	global $db;
	$result = mysqli_query($db, $query);
	$rows = [];
	while($row = mysqli_fetch_assoc($result)){
		$rows[] = $row;
	}
	return $rows;
}

function tambah($data){
	global $db;
	// ambil data tiap element dalam form

	//htmlspecialchars digunakan untuk agar tidak langsung menampilkan elemen html
	$nim = htmlspecialchars($data["nim"]);
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);

	//upload gambar
	$gambar = upload();
	if (!$gambar) {
		return false;
	}

	// query insert data
	$query = "INSERT INTO mahasiswa
				VALUES
				('', '$nama', '$nim', '$email', '$jurusan', '$gambar')
			 ";
	mysqli_query($db, $query);
	return mysqli_affected_rows($db);
}

function upload(){
	$namaFile = $_FILES['gambar']['name'];
	$sizeFile = $_FILES['gambar']['size'];
	$error = $_FILES['gambar']['error'];
	$tmpFile = $_FILES['gambar']['tmp_name'];

	//cek apakah tidak ada gambar yg diupload
	if ($error === 4) {
		echo "<script>
					alert('Masukkan Gambar Terlebih Dahulu!!');
			  </script>";
	    return false;
	}

	//cek apakah yg diupload gambar atau bukan
	$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
	$ekstensiGambar = explode('.', $namaFile);
	$ekstensiGambar = strtolower(end($ekstensiGambar));
	if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
		echo "<script>
					alert('Yang Anda Upload bukan Gambar!!');
			  </script>";
	    return false;
	}

	//cek jika ukuran terlalu besar
	if ($sizeFile > 5000000) {
		echo "<script>
					alert('Ukuran Gambar Terlalu Besar!!');
			  </script>";
	    return false;
	}

	//lolos pengecekan, gambar siap diupload
	//generate nama gambar baru
	$namaFileBaru = uniqid();
	$namaFileBaru .= '.';
	$namaFileBaru .= $ekstensiGambar;
	move_uploaded_file($tmpFile, 'dashboard/img/' . $namaFileBaru);
	return $namaFileBaru;
}

function hapus($id){
	global $db;
	mysqli_query($db, "DELETE FROM mahasiswa WHERE id=$id");
	return mysqli_affected_rows($db);
}

?>