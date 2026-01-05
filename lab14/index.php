<?php 
include 'koneksi.php'; 

// --- LOGIKA SEARCH ---
$keyword = "";
$condition = "";
if (isset($_POST['cari'])) {
    $keyword = $_POST['keyword'];
    $condition = " WHERE nama_barang LIKE '%$keyword%' OR kategori LIKE '%$keyword%' ";
}

// --- LOGIKA PAGINATION (DISESUAIKAN DENGAN SEARCH) ---
$jumlahDataPerHalaman = 10;
$halamanAktif = (isset($_GET["halaman"])) ? (int)$_GET["halaman"] : 1;
if($halamanAktif <= 0) $halamanAktif = 1;

$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Hitung total data berdasarkan search (jika ada)
$resultTotal = mysqli_query($conn, "SELECT id FROM barang $condition");
$jumlahTotalData = mysqli_num_rows($resultTotal);
$jumlahHalaman = ceil($jumlahTotalData / $jumlahDataPerHalaman);

// Query ambil data dengan LIMIT & SEARCH
$query = "SELECT * FROM barang $condition ORDER BY id DESC LIMIT $awalData, $jumlahDataPerHalaman";
$result = mysqli_query($conn, $query);

// Pesan notifikasi
$pesan = "";
if(isset($_GET['pesan'])){
    if($_GET['pesan'] == 'berhasil') $pesan = "Data berhasil ditambahkan.";
    if($_GET['pesan'] == 'update_berhasil') $pesan = "Data berhasil diperbarui.";
    if($_GET['pesan'] == 'hapus_berhasil') $pesan = "Data telah dihapus.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - My Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #5F9EA0; }
        .img-produk { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e7bcf0ff; }
        .card-table { border: none; border-radius: 15px; box-shadow: 0 4px 12px #7B68EE(0,0,0,0.05); }
        .search-input { border-radius: 20px 0 0 20px; border-right: none; }
        .search-btn { border-radius: 0 20px 20px 0; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: #4682B4;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">MY THINGS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="bi bi-house-door"></i> Home</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                           <i class="bi bi-plus-lg"></i> Tambah Barang
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <?php if($pesan): ?>
            <div class='alert alert-info alert-dismissible fade show' role='alert'>
                <?php echo $pesan; ?>
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold text-secondary m-0">📦List Items</h3>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <form action="" method="POST">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control search-input" placeholder="Cari nama barang atau kategori..." value="<?php echo $keyword; ?>" autocomplete="off">
                        <button class="btn btn-outline-primary search-btn" type="submit" name="cari">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card card-table overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Gambar</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0) : ?>
                            <?php while($row = mysqli_fetch_assoc($result)) : ?>
                            <?php $gambar = !empty($row['gambar']) ? 'picts/'.$row['gambar'] : 'https://via.placeholder.com/60'; ?>
                            <tr>
                                <td class='ps-4'><img src='<?php echo $gambar; ?>' class='img-produk'></td>
                                <td><strong><?php echo $row['nama_barang']; ?></strong></td>
                                <td><span class='badge bg-info text-dark'><?php echo $row['kategori']; ?></span></td>
                                <td>Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                                <td class='text-center'><span class='badge bg-secondary'><?php echo $row['stok']; ?></span></td>
                                <td class='text-center text-nowrap'>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Sorry, We Couldn't Find the Item You Were looking For.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if($jumlahHalaman > 1) : ?>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($halamanAktif <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?halaman=<?php echo $halamanAktif - 1; ?>">Previous</a>
                </li>
                <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <li class="page-item <?php if($i == $halamanAktif) echo 'active'; ?>">
                        <a class="page-link" href="?halaman=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if($halamanAktif >= $jumlahHalaman) echo 'disabled'; ?>">
                    <a class="page-link" href="?halaman=<?php echo $halamanAktif + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="tambah_barang.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="Elektronik">Pakaian</option>
                                <option value="Pakaian">Aksesoris</option>
                                <option value="Makanan">Sepatu</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Beli</label>
                                <input type="number" name="harga_beli" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" name="harga_jual" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>