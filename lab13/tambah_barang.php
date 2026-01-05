<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama      = $_POST['nama_barang'];
    $kategori  = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $stok      = $_POST['stok'];
    
    // Logika Upload Gambar
    $nama_gambar = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];
    
    if ($nama_gambar != "") {
        $ekstensi_izin = array('png', 'jpg', 'jpeg');
        $x = explode('.', $nama_gambar);
        $ekstensi = strtolower(end($x));
        $file_baru = date('d-m-Y') . '-' . $nama_gambar; // Rename biar gak bentrok

        if (in_array($ekstensi, $ekstensi_izin) === true) {
            move_uploaded_file($tmp_name, 'picts/' . $file_baru);
            $path_gambar = 'picts/' . $file_baru;
        } else {
            $path_gambar = ""; // Ekstensi salah, gambar diabaikan
        }
    } else {
        $path_gambar = ""; // Kosong jika tidak upload
    }

    // Insert ke Database MYTHINGS, tabel Barang
    $query = "INSERT INTO Barang (nama_barang, kategori, harga_jual, stok, gambar) 
              VALUES ('$nama', '$kategori', '$harga_jual', '$stok', '$path_gambar')";
    
    if (mysqli_query($conn, $query)) {
        header("location:index.php?pesan=berhasil");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
