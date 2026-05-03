<?php
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'buy') {
    $product_id = $_POST['product_id'];

    $cek_stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $cek_stmt->bind_param("i", $product_id);
    $cek_stmt->execute();
    $hasil = $cek_stmt->get_result()->fetch_assoc();
    $cek_stmt->close();

    if ($hasil && $hasil['stock'] > 0) {
        $update_stmt = $conn->prepare("UPDATE products SET stock = stock - 1 WHERE id = ?");
        $update_stmt->bind_param("i", $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    header("Location: index.php#shop");
    exit;
}
?>

<!doctype html>
<html class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./src/output.css" rel="stylesheet">
  <title>Monstera Deliciosa</title>
</head>
<body class="bg-surface overflow-x-hidden">

  <!-- Navbar -->
  <nav class="shadow-xl sticky top-0 z-50 bg-surface/80 backdrop-blur-md">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center md:px-2">
        <div class="">
          <h4 class="text-3xl font-bold text-primary">Monstera</h4>
        </div>
        <button id="hamburger-menu" class="md:hidden">
          <img src="./src/img/hamburger.svg" alt="Hamburger Menu">
        </button>
        <ul class="hidden md:flex space-x-16">
          <li><h4><a href="#home" class="text-contrast hover:text-primary">Home</a></h4></li>
          <li><h4><a href="#varieties" class="text-contrast hover:text-primary">Varieties</a></h4></li>
          <li><h4><a href="#guide" class="text-contrast hover:text-primary">Guide</a></h4></li>
          <li><h4><a href="#shop" class="text-contrast hover:text-primary">Shop</a></h4></li>
        </ul>
    </div>
    <div id="mobile-nav" class="fixed inset-0 bg-surface z-50 hidden flex-col items-center justify-center transform translate-x-full transition-transform duration-300 ease-in-out">
      <button id="close-menu" class="absolute top-6 right-6 text-contrast text-4xl font-bold">&times;</button>
      <ul class="flex flex-col space-y-8 text-center mt-24">
        <li><h4><a href="#home" class="text-contrast hover:text-primary text-3xl">Home</a></h4></li>
        <li><h4><a href="#varieties" class="text-contrast hover:text-primary text-3xl">Varieties</a></h4></li>
        <li><h4><a href="#guide" class="text-contrast hover:text-primary text-3xl">Guide</a></h4></li>
        <li><h4><a href="#shop" class="text-contrast hover:text-primary text-3xl">Shop</a></h4></li>
      </ul>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="container px-6 py-12 flex flex-col md:flex-row mx-auto items-center" id="home">
    <div class="md:w-1/2 mb-8 md:mb-0">
      <h1 class="text-4xl font-bold text-primary mb-4 italic md:text-6xl">Monstera deliciosa:</h1>
      <h3 class="text-secondary mb-6 md:text-4xl">The Swiss cheese plant</h3>
      <p class="text-contrast md:text-xl">Bring the jungle home with one of the most
iconic indoor plants. Known for its unique
fenestrated leaves and resilient, easy-care
nature.</p>
      <a href="#varieties" class="bg-primary text-white px-6 py-3 rounded-lg">View varieties</a>
    </div>
    <div class="md:w-1/2">
      <img src="./src/img/hero-img.webp" alt="Monstera Plant">
    </div>
  </section>

  <!-- Varieties Section -->
  <section class="container px-6 py-12 md:px-2 mx-auto" id="varieties">
    <h2 class="text-3xl font-bold text-primary mb-8 text-center">Monstera Varieties</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-surface-var rounded-lg shadow-lg overflow-hidden px-4 py-8">
        <img src="./src/img/Monstera-Variegata.webp" alt="Monstera Variegata" class="rounded-lg w-full object-cover">
        <div class="p-4">
          <h3 class="text-center text-2xl font-semibold text-primary mb-2 italic">Monstera Variegata</h3>
          <p class="text-contrast">A stunning variety with striking white and green variegation on its leaves.</p>
        </div>
      </div>
      <div class="bg-surface-var rounded-lg shadow-lg overflow-hidden px-4 py-8">
        <img src="./src/img/Monstera-Adansonii.webp" alt="Monstera Adansonii" class="rounded-lg w-full object-cover">
        <div class="p-4">
          <h3 class="text-center text-2xl font-semibold text-primary mb-2 italic">Monstera Adansonii</h3>
          <p class="text-contrast">Known as the "Swiss cheese vine" it features smaller, perforated leaves.</p>
        </div>
      </div>
      <div class="bg-surface-var rounded-lg shadow-lg overflow-hidden px-4 py-8">
        <img src="./src/img/Monstera-Thai-Constellation.webp" alt="Monstera Thai Constellation" class="rounded-lg w-full object-cover">
        <div class="p-4">
          <h3 class="text-center text-2xl font-semibold text-primary mb-2 italic">Monstera Thai Constellation</h3>
          <p class="text-contrast">A rare and sought-after variety with creamy white variegation and a compact growth habit.</p>
        </div>
      </div>
    </div>

    <!-- Guide Section -->
    <section class="container px-6 py-12 md:px-2 py-20 mx-auto" id="guide">
      <div class="mt-12" id="guide">
        <div class="md:flex items-center justify-between mb-8">
          <div class="">
            <h2 class="text-2xl font-bold text-secondary text-center md:text-3xl">Grow Guide</h2>
            <h1 class="text-3xl font-bold text-primary text-center md:text-4xl">Quick Care Tips</h1>
          </div>
          <p class="font-bold text-contrast mb-8 text-center">Simple guidelines to help your Monstera thrive</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-surface rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <img src="./src/img/icon-1.webp" class="bg-secondary px-8 py-8 rounded-lg mb-6" alt="Bright Indirect Light">
            <h3 class="text-center text-2xl font-semibold text-secondary mb-4">Bright Indirect Light</h3>
            <p class="text-contrast">Thrives in bright, filtered light. Avoid
direct hot sun which can scorch the
leaves, but too little light will stop
fenestrations.</p>
          </div>
          <div class="bg-surface rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <img src="./src/img/icon-2.webp" class="bg-secondary px-8 py-8 rounded-lg mb-6" alt="Water">
            <h3 class="text-center text-2xl font-semibold text-secondary mb-4">Moderate Watering</h3>
            <p class="text-contrast">Water every 1-2 weeks, allowing soil
to dry out between waterings. Expect
to water more often in brighter light
and less in lower light.</p>
          </div>
          <div class="bg-surface rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <img src="./src/img/icon-3.webp" class="bg-secondary px-8 py-8 rounded-lg mb-6" alt="Humidity">
            <h3 class="text-center text-2xl font-semibold text-secondary mb-4">Tropical Humidity</h3>
            <p class="text-contrast">They love humidity! Mist frequently
use a humidifier, or group with other
plants to recreate a rainforest
atmosphere.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Shop Section -->
    <section class="container px-6 py-12 md:px-2 mx-auto" id="shop">
      <!-- Section Header -->
      <div class="mb-12 flex flex-col items-center">
        <h1 class="text-4xl font-bold text-primary text-center md:text-6xl">Start Decor Your House</h1>
        <p class="text-lg font-bold text-contrast text-center md:text-2xl">Get a healthy, beautiful Monstera delivered safely to your door.</p>
      </div>

      <!-- Product Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
      <?php
      $sql = "SELECT * FROM products ORDER BY created_at DESC";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
      ?>

          <div class="bg-surface rounded-lg shadow-xl overflow-hidden flex flex-col transition-transform hover:-translate-y-2 duration-300">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="w-full h-64 object-cover">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-2xl font-bold text-primary mb-1"><?php echo htmlspecialchars($row['name']); ?></h3>
              <p class="text-contrast text-sm mb-4"><?php echo htmlspecialchars($row['description']); ?></p>
              
              <div class="mt-auto">
                <p class="text-xs text-contrast mb-2">Stok: <span class="font-bold"><?php echo htmlspecialchars($row['stock']); ?></span></p>
                <span class="block text-2xl font-bold text-secondary mb-4">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></span>
              
                <?php if ($row['stock'] > 0): ?>
                  <form method="POST" action="index.php">
                    <input type="hidden" name="action" value="buy">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-lg shadow-lg hover:opacity-90 transition-opacity cursor-pointer">
                      Buy Now
                    </button>
                  </form>
                <?php else: ?>
                  <!-- Tombol disable jika stok habis -->
                  <button disabled class="w-full bg-gray-400 text-white font-bold py-3 rounded-lg shadow-inner cursor-not-allowed">
                    Out of Stock
                  </button>
                <?php endif; ?>

              </div>
            </div>
          </div>

      <?php
          } 
      } else {
          echo "<p class='col-span-full text-center text-contrast text-xl'>Currently no plants available</p>";
      }
      ?>

      </div>
    </section>

    <!-- Footer -->
    <footer class="mt-12 py-6 text-center text-contrast">
      &copy; 2026 Monstera Botanical. All rights reserved.
    </footer>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const closeMenu = document.getElementById('close-menu');
        const mobileNav = document.getElementById('mobile-nav');

        hamburgerMenu.addEventListener('click', () => {
          mobileNav.classList.remove('hidden');
          requestAnimationFrame(() => {
            mobileNav.classList.remove('translate-x-full');
            mobileNav.classList.add('translate-x-0');
          });
        });

        closeMenu.addEventListener('click', () => {
          mobileNav.classList.remove('translate-x-0');
          mobileNav.classList.add('translate-x-full');
          mobileNav.addEventListener('transitionend', () => {
            mobileNav.classList.add('hidden');
          }, { once: true });
        });
        const mobileNavLinks = mobileNav.querySelectorAll('a');
        mobileNavLinks.forEach(link => {
          link.addEventListener('click', () => {
            mobileNav.classList.remove('translate-x-0');
            mobileNav.classList.add('translate-x-full');
            mobileNav.addEventListener('transitionend', () => {
              mobileNav.classList.add('hidden');
            }, { once: true });
          });
        });
      });
    </script>
</body>
</html>