<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once 'connect.php';

$nama_user = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "User";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Konfigurasi Upload
    $target_dir = "./src/img/";
    $image_url = ""; 

    // Mengecek apakah ada file yang diupload dan tidak ada error
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        
        // Ambil info file
        $file_name = $_FILES['image_file']['name'];
        $file_tmp = $_FILES['image_file']['tmp_name'];
        
        // Ekstensi file
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Filter ekstensi
        $allowed_extensions = array("jpg", "jpeg", "png", "webp");

        if (in_array($file_ext, $allowed_extensions)) {
            // Rename file biar unik
            $new_file_name = time() . "_" . str_replace(" ", "_", $file_name);
            $target_file = $target_dir . $new_file_name;

            // Pindahkan file ke folder src/img/
            if (move_uploaded_file($file_tmp, $target_file)) {
                $image_url = $target_file;
                
                // Masukkan data ke Database
                $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, stock) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdsi", $name, $desc, $price, $image_url, $stock);
                
                if ($stmt->execute()) {
                    $pesan_sukses = "New plant and image added successfully!";
                } else {
                    $pesan_error = "Database error: Gagal menyimpan data";
                }
                $stmt->close();
            } else {
                $pesan_error = "Gagal memindahkan file gambar ke folder";
            }
        } else {
            $pesan_error = "Format gambar tidak didukung! Hanya JPG, JPEG, PNG, dan WEBP";
        }
    } else {
        $pesan_error = "Pilih gambar terlebih dahulu!";
    }
}
?>

<!doctype html>
<html class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./src/output.css" rel="stylesheet">
  <title>Dashboard - Monstera</title>
</head>
<body class="bg-surface-var overflow-x-hidden md:flex min-h-screen">
  <aside id="sidebar" class="w-64 bg-surface shadow-xl hidden md:flex flex-col z-40">
    <div class="p-6 mb-4">
      <h2 class="text-3xl font-bold text-primary">Monstera</h2>
    </div>
    <ul class="flex-1 px-4 space-y-2">
      <li>
        <a href="admin.php" class="flex items-center px-4 py-3 rounded-lg font-bold transition-transform">Dashboard Overview</a>
      </li>
      <li>
        <a href="add_stock.php" class="flex items-center px-4 py-3 rounded-lg font-bold transition-transform">Add Stock</a>
      </li>
      <li>
        <a href="edit_stock.php" class="flex items-center px-4 py-3 rounded-lg font-bold transition-transform">Edit Stock</a>
      </li>
      <li>
        <a href="delete_stock.php" class="flex items-center px-4 py-3 rounded-lg font-bold transition-transform">Delete Stock</a>
      </li>
    </ul>
    <div class="p-6 border-t border-surface-var">
      <a href="logout.php" class="text-contrast font-bold hover:text-secondary">Log Out</a>
    </div>
  </aside>

  <div class="md:hidden bg-surface shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
    <h4 class="text-2xl font-bold text-primary mb-0">Monstera</h4>
    <button id="hamburger-btn" class="text-primary font-bold text-2xl">☰</button>
  </div>

  <!-- Main Content -->
  <main class="flex-1 p-6 md:p-10">
    <div class="bg-surface rounded-lg shadow-lg p-6 md:p-8 max-w-2xl">
      <h2 class="text-3xl font-bold text-primary mb-6">Add New Plant</h2>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-4">
          <label class="block text-contrast font-bold mb-2">Plant Name</label>
          <input type="text" name="name" required class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast">
        </div>
        <div class="mb-4">
          <label class="block text-contrast font-bold mb-2">Description</label>
          <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-contrast font-bold mb-2">Price (Rp)</label>
            <input type="number" name="price" required class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast">
          </div>
          <div>
            <label class="block text-contrast font-bold mb-2">Stock</label>
            <input type="number" name="stock" required class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast">
          </div>
        </div>
        <div class="mb-8">
          <label class="block text-contrast font-bold mb-2">Upload Plant Image</label>
          <input type="file" name="image_file" accept="image/png, image/jpeg, image/webp" required class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast">
        </div>
        <button type="submit" class="bg-primary text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:opacity-90">Save Plant</button>
      </form>
    </div>
  </main>

<script>
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const sidebar = document.getElementById('sidebar');

    hamburgerBtn.addEventListener('click', () => {

      sidebar.classList.toggle('hidden');
      sidebar.classList.toggle('flex');
      
      sidebar.classList.toggle('fixed');
      sidebar.classList.toggle('top-0');
      sidebar.classList.toggle('left-0');
      sidebar.classList.toggle('h-screen');
    });
  </script>
</body>
</html>