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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Ambil URL gambar lama sebagai default
    $image_url = $_POST['old_image_url']; 

    // Cek apakah ada file gambar BARU yang diupload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "./src/img/";
        $file_name = $_FILES['image_file']['name'];
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "webp");

        if (in_array($file_ext, $allowed_extensions)) {
            $new_file_name = time() . "_" . str_replace(" ", "_", $file_name);
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                // Gambar baru berhasil diupload, timpa variabel image_url
                $image_url = $target_file;
            } else {
                $pesan_error = "Gagal memindahkan file gambar baru";
            }
        } else {
            $pesan_error = "Format gambar tidak didukung!";
        }
    }

    // Jika tidak ada error upload gambar, lanjutkan Update ke Database
    if ($pesan_error == '') {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image_url=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdsii", $name, $desc, $price, $image_url, $stock, $id);
        
        if ($stmt->execute()) {
            $pesan_sukses = "Update success!";
        } else {
            $pesan_error = "Database error: Gagal mengupdate data.";
        }
        $stmt->close();
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
  <main class="flex-1 p-6 md:p-10 overflow-y-auto">
    <?php
    if (isset($_GET['id'])): 
        $id_edit = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id_edit);
        $stmt->execute();
        $data_edit = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Jika data ditemukan, tampilkan form
        if ($data_edit):
    ?>
      <div class="bg-surface rounded-lg shadow-lg p-6 md:p-8 max-w-2xl">
        <h2 class="text-3xl font-bold text-primary mb-6">Edit Plant</h2>
        <!-- enctype ditambahkan agar bisa kirim file -->
        <form method="POST" action="edit_stock.php?id=<?php echo $id_edit; ?>" enctype="multipart/form-data">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">
          
          <!-- Simpan URL gambar lama di hidden input -->
          <input type="hidden" name="old_image_url" value="<?php echo htmlspecialchars($data_edit['image_url']); ?>">

          <div class="mb-4">
            <label class="block text-contrast font-bold mb-2">Plant Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($data_edit['name']); ?>" required class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast">
          </div>
          
          <div class="mb-4">
            <label class="block text-contrast font-bold mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast"><?php echo htmlspecialchars($data_edit['description']); ?></textarea>
          </div>
          
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-contrast font-bold mb-2">Price (Rp)</label>
              <input type="number" name="price" value="<?php echo $data_edit['price']; ?>" required class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast">
            </div>
            <div>
              <label class="block text-contrast font-bold mb-2">Stock</label>
              <input type="number" name="stock" value="<?php echo $data_edit['stock']; ?>" required class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface-var text-contrast">
            </div>
          </div>
          
          <div class="mb-8 border border-surface-var p-4 rounded-lg bg-surface-var">
            <label class="block text-contrast font-bold mb-2">Current Image</label>
            <img src="<?php echo htmlspecialchars($data_edit['image_url']); ?>" alt="Current Image" class="h-32 object-cover rounded mb-4 shadow">
            
            <label class="block text-contrast font-bold mb-2">Upload New Image (Optional)</label>
            <input type="file" name="image_file" accept="image/png, image/jpeg, image/webp" class="w-full px-4 py-3 rounded-lg outline border border-surface-var bg-surface text-contrast">
            <p class="text-sm text-contrast mt-2 italic">Biarkan kosong jika tidak ingin mengubah</p>
          </div>
          
          <button type="submit" class="bg-primary text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:opacity-90">Update Plant</button>
          <a href="edit_stock.php" class="ml-4 text-contrast font-bold underline">Cancel</a>
        </form>
      </div>

    <?php 
        endif; 
    else: 
    ?>
      <div class="bg-surface rounded-lg shadow-lg p-6 md:p-8">
        <h2 class="text-3xl font-bold text-primary mb-6">Select Product to Edit</h2>
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
              while($row = $result->fetch_assoc()):
              ?>
              <tr class="border-b border-surface-var text-contrast">
                <td class="py-3 px-4">
                  <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="w-16 h-16 object-cover rounded shadow">
                </td>
                <td class="py-3 px-4 font-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                <!-- Menampilkan harga dalam format Rupiah -->
                <td class="py-3 px-4">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                <td class="py-3 px-4"><?php echo $row['stock']; ?></td>
                <td class="py-3 px-4">
                  <a href="edit_stock.php?id=<?php echo $row['id']; ?>" class="bg-secondary text-white px-4 py-2 rounded-lg font-bold text-sm hover:opacity-90 transition-opacity shadow">Edit</a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
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