<?php 
include 'koneksi.php'; 

// --- BAGIAN LOGIKA PROSES (JIKA TOMBOL UPDATE DITEKAN) ---
if (isset($_POST['update'])) {
    $id         = $_POST['id'];
    $nama       = $_POST['nama_barang'];
    $kategori   = $_POST['kategori'];
    $harga_jual = $_POST['harga_jual'];
    $stok       = $_POST['stok'];

    $nama_gambar = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];

    if ($nama_gambar != "") {
        // Jika user upload gambar baru
        $x = explode('.', $nama_gambar);
        $ekstensi = strtolower(end($x));
        $file_baru = date('d-m-Y') . '-' . $nama_gambar;
        
        move_uploaded_file($tmp_name, 'picts/' . $file_baru);
        $path_gambar = 'picts/' . $file_baru;

        // Hapus file gambar lama dari folder agar tidak menumpuk
        $data_lama = mysqli_query($conn, "SELECT gambar FROM Barang WHERE id='$id'");
        $row_lama = mysqli_fetch_assoc($data_lama);
        if(!empty($row_lama['gambar']) && file_exists($row_lama['gambar'])) {
            unlink($row_lama['gambar']);
        }

        $query = "UPDATE Barang SET nama_barang='$nama', kategori='$kategori', harga_jual='$harga_jual', stok='$stok', gambar='$path_gambar' WHERE id='$id'";
    } else {
        // Jika tidak ganti gambar, update data lainnya saja
        $query = "UPDATE Barang SET nama_barang='$nama', kategori='$kategori', harga_jual='$harga_jual', stok='$stok' WHERE id='$id'";
    }

    if (mysqli_query($conn, $query)) {
        header("location:index.php?pesan=update_berhasil");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- BAGIAN TAMPILAN FORM (AMBIL DATA BERDASARKAN ID) ---
$id = $_GET['id'];
$ambildata = mysqli_query($conn, "SELECT * FROM Barang WHERE id='$id'");
$row = mysqli_fetch_assoc($ambildata);

// Jika ID tidak ditemukan
if (!$row) {
    die("Data tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang - MY THINGS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #B1c9EF; }
        .card-edit { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .preview-img { width: 100px; border-radius: 8px; margin-bottom: 10px; border: 2px solid #eee; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-edit">
                    <div class="card-header bg-warning text-dark fw-bold py-3 text-center">
                        EDIT DATA BARANG
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" value="<?php echo $row['nama_barang']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="kategori" class="form-select">
                                    <?php 
                                    $kategori = ['Sepatu', 'Pakaian', 'Aksesoris', 'Lainnya'];
                                    foreach($kategori as $k) {
                                        $selected = ($row['kategori'] == $k) ? 'selected' : '';
                                        echo "<option value='$k' $selected>$k</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Harga Jual</label>
                                <input type="number" name="harga_jual" class="form-control" value="<?php echo $row['harga_jual']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Stok</label>
                                <input type="number" name="stok" class="form-control" value="<?php echo $row['stok']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold d-block">Gambar Saat Ini</label>
                                <?php if($row['gambar']): ?>
                                    <img src="<?php echo $row['gambar']; ?>" class="preview-img">
                                <?php else: ?>
                                    <p class="text-muted">Tidak ada gambar</p>
                                <?php endif; ?>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                                <small class="text-muted italic">*Kosongkan jika tidak ingin mengganti gambar</small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="index.php" class="btn btn-light px-4">Batal</a>
                                <button type="submit" name="update" class="btn btn-warning px-4 fw-bold">Update Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
