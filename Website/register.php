<?php
include 'includes/header.php';

if(isLoggedIn()) {
    echo "<p>Zaten giriş yapmışsınız. <a href='index.php'>Anasayfa</a></p>";
    include 'includes/footer.php';
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");

    try {
        $stmt->execute([$username, $email, $hash]);
        echo "<p>Kayıt başarılı! <a href='login.php'>Giriş Yap</a></p>";
    } catch(PDOException $e) {
        echo "<p>Hata: " . $e->getMessage() . "</p>";
    }
}
?>

<style>
    body {
      margin: 0; padding: 0;
      font-family: Arial, sans-serif;
      color: #333;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      padding: 0 20px;
    }
    .tabs {
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
      border-bottom: 1px solid #ccc;
    }
    .tabs a {
      margin: 0 20px;
      text-decoration: none;
      padding-bottom: 10px;
      color: #333;
      font-size: 1.2em;
    }
    .tabs a.active {
      border-bottom: 2px solid #000;
      font-weight: bold;
    }
    .form-group {
      margin-bottom: 20px;
      position: relative;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-size: 0.95em;
      color: #666;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px 40px 10px 10px; /* Göz ikonu için sağ boşluk */
      border: none;
      border-bottom: 1px solid #ccc;
      font-size: 1em;
      outline: none;
    }
    .eye-icon {
      position: absolute;
      right: 0;
      top: 27px;
      cursor: pointer;
      font-size: 0.9em;
      color: #888;
      padding: 0 10px;
      user-select: none;
    }
    button {
      width: 100%;
      padding: 10px;
      background-color: #000;
      color: #fff;
      border: none;
      font-size: 1em;
      cursor: pointer;
      margin-top: 20px;
    }
    button:hover {
      opacity: 0.9;
    }
</style>

<div class="container">
  <!-- Sekme görünümlü linkler -->
  <div class="tabs">
    <a href="login.php">Giriş Yap</a>
    <a href="#" class="active">Üye Ol</a>
  </div>

  <!-- Kayıt (Register) Formu -->
  <form method="post">
    <div class="form-group">
      <label for="username">Kullanıcı Adı</label>
      <input type="text" id="username" name="username" required>
    </div>

    <div class="form-group">
      <label for="email">E-Posta</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
      <label for="password">Şifre</label>
      <input type="password" id="password" name="password" required>
      <span class="eye-icon" onclick="togglePassword()">Göster</span>
    </div>

    <button type="submit">Kayıt Ol</button>
  </form>
</div>

<script>
function togglePassword() {
  const pwd = document.getElementById('password');
  const icon = document.querySelector('.eye-icon');
  if (pwd.type === 'password') {
    pwd.type = 'text';
    icon.innerText = 'Gizle';
  } else {
    pwd.type = 'password';
    icon.innerText = 'Göster';
  }
}
</script>
