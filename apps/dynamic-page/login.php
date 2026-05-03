<?php
session_start();

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

require_once 'connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_name'] = $user['name']; 
            $_SESSION['user_id'] = $user['id']; 
            
            header("Location: admin.php");
            exit;
        } else {
            $error = "Email atau password salah!";
        }
    } else {
        $error = "Email atau password salah!";
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
  <title>Login</title>
</head>
<body class="bg-surface-var min-h-screen flex items-center justify-center">

  <div class="bg-surface p-8 md:p-12 rounded-lg shadow-xl w-full max-w-md mx-6">
    <h1 class="text-4xl font-bold text-primary text-center mb-2">Who are you?</h1>
    <h3 class="text-secondary text-center text-xl mb-8">Login to access Dashboard</h3>

    <form method="POST" action="">
      <div class="mb-4">
        <label class="block text-contrast font-bold mb-2">Email</label>
        <input type="email" name="email" class="w-full px-4 py-3 rounded-lg outline border border-surface-var focus:outline-primary bg-surface-var text-contrast">
      </div>
      <div class="mb-8">
        <label class="block text-contrast font-bold mb-2">Password</label>
        <input type="password" name="password" class="w-full px-4 py-3 rounded-lg outline border border-surface-var focus:outline-primary bg-surface-var text-contrast">
      </div>
      <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-lg shadow-lg hover:opacity-90 transition-transform">
        Log In
      </button>
    </form>
  </div>

</body>
</html>