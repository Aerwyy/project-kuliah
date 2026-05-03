<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once 'connect.php';
$pesan_sukses = '';
$pesan_error = '';

$nama_user = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "User";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];
    
    // 1. Cari tahu dulu lokasi file gambar yang mau dihapus
    $stmt_img = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $result_img = $stmt_img->get_result()->fetch_assoc();
    $stmt_img->close();

    // 2. Hapus data dari Database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $pesan_sukses = "Plant deleted successfully!";
        
        // 3. Jika berhasil dihapus dari DB, hapus juga file fisiknya dari folder src/img/
        // file_exists() buat ngecek file-nya beneran ada atau nggak sebelum dihapus
        if ($result_img && !empty($result_img['image_url']) && file_exists($result_img['image_url'])) {
            unlink($result_img['image_url']); // Fungsi unlink digunakan untuk menghapus file di PHP
        }
    } else {
        $pesan_error = "Database error: Gagal menghapus data.";
    }
    $stmt->close();
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
  <main class="flex-1 p-6 md:p-10 overflow-y-auto">
    <div class="bg-surface rounded-lg shadow-lg p-6 md:p-8">
      <h2 class="text-3xl font-bold text-red-600 mb-6">Delete Product</h2>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-surface-var text-secondary">
              <th class="py-3 px-4">Image</th>
              <th class="py-3 px-4">Name</th>
              <th class="py-3 px-4">Price</th>
              <th class="py-3 px-4">Stock</th>
              <th class="py-3 px-4">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
            <tr class="border-b border-surface-var text-contrast hover:bg-surface-var transition-colors">
              <td class="py-3 px-4">
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="w-16 h-16 object-cover rounded shadow" alt="Plant">
              </td>
              <td class="py-3 px-4 font-bold"><?php echo htmlspecialchars($row['name']); ?></td>
              <td class="py-3 px-4">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
              <td class="py-3 px-4"><?php echo $row['stock']; ?></td>
              <td class="py-3 px-4">
                <form method="POST" action="delete_stock.php" onsubmit="return confirm('Yakin ingin menghapus <?php echo htmlspecialchars(addslashes($row['name'])); ?>? Data tidak bisa dikembalikan.');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                  <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-red-700 transition-colors shadow">
                    Delete
                  </button>
                </form>

              </td>
            </tr>
            <?php 
                endwhile; 
            else:
            ?>
            <tr>
              <td colspan="5" class="py-6 px-4 text-center text-contrast font-bold">Tidak ada tanaman di database.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
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