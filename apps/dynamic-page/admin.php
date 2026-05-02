<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once 'connect.php';

$nama_user = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "User";
$query_total = $conn->query("SELECT SUM(stock) AS total_plants FROM products");
$total_plants = $query_total->fetch_assoc()['total_plants'] ?? 0;
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

  <!-- Sidebar (Desktop) -->
  <aside class="w-64 bg-surface shadow-xl hidden md:flex flex-col z-10">
    <div class="p-6 mb-4">
      <h2 class="text-3xl font-bold text-primary">Monstera</h2>
    </div>
    <ul class="flex-1 px-4 space-y-2">
      <!-- Logic Class Active: ngecek nilai $current_page -->
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

  <!-- Mobile Header -->
  <div class="md:hidden bg-surface shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
    <h4 class="text-2xl font-bold text-primary mb-0">Monstera</h4>
    <button class="text-primary font-bold text-2xl">☰</button>
  </div>

  <!-- Main Content -->
  <main class="flex-1 p-6 md:p-10">
    <!-- Top Header -->
    <header class="flex justify-between items-center mb-10">
      <div>
        <h1 class="text-4xl md:text-5xl font-bold text-primary mb-2">Hello, <?php echo $nama_user; ?>!</h1>
        <p class="text-contrast font-bold">Here is your botanical overview today.</p>
      </div>
    </header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
      <div class="bg-surface p-6 rounded-lg shadow-lg border-t-[6px] border-primary">
        <h3 class="text-secondary text-xl mb-2">Total Plants</h3>
        <p class="text-5xl font-bold text-primary mb-0"><?php echo $total_plants; ?></p>
      </div>
    </div>
  </main>

</body>
</html>