<?php
include 'koneksi.php';

$id = $_GET['id'];

// 1. Ambil nama file gambar dulu sebelum datanya dihapus
$data = mysqli_query($conn, "SELECT gambar FROM Barang WHERE id='$id'");
$row = mysqli_fetch_assoc($data);
$gambar = $row['gambar'];

// 2. Hapus file gambar di folder uploads jika ada
if (!empty($gambar) && file_exists($gambar)) {
    unlink($gambar);
}

// 3. Hapus data dari database
$query = mysqli_query($conn, "DELETE FROM Barang WHERE id='$id'");

if ($query) {
    header("location:index.php?pesan=hapus_berhasil");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
?>
