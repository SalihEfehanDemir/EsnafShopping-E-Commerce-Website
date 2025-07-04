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

    // --- GÜVENLİK DÜZELTMESİ: Mevcut Kullanıcı/Email Kontrolü ---
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        if ($existingUser['username'] === $username) {
            echo "<p>Hata: Bu kullanıcı adı zaten kullanılıyor.</p>";
        } else {
            echo "<p>Hata: Bu e-posta adresi zaten kayıtlı.</p>";
        }
    } else {
        // Kullanıcı yoksa, kaydı gerçekleştir
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");

        try {
            $stmt->execute([$username, $email, $hash]);
            echo "<p>Kayıt başarılı! <a href='login.php'>Giriş Yap</a></a_>";
        } catch(PDOException $e) {
            // Güvenlik için genel hata mesajı
            error_log("Kayıt hatası: " . $e->getMessage()); // Hatayı sunucu loglarına yazdır
            echo "<p>Kayıt sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.</p>";
        }
    }
    // --- GÜVENLİK DÜZELTMESİ SONU ---
}
?>

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
