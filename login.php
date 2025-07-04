<?php
include 'includes/header.php';

if(isLoggedIn()) {
    echo "<p>Zaten giriş yapmışsınız. <a href='index.php'>Anasayfa</a></p>";
    include 'includes/footer.php';
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        echo "<p>Kullanıcı adı veya şifre hatalı.</p>";
    }
}
?>

<div class="container">
  <!-- Sekme görünümlü linkler -->
  <div class="tabs">
    <a href="#" class="active">Giriş Yap</a>
    <a href="register.php">Üye Ol</a>
  </div>

  <?php if(isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="post">
    <div class="form-group">
      <label for="username">Kullanıcı Adı</label>
      <input type="text" id="username" name="username">
    </div>

    <div class="form-group" style="margin-bottom:5px;">
      <label for="password">Şifre</label>
      <input type="password" id="password" name="password">
      <span class="eye-icon" onclick="togglePassword()">Göster</span>
    </div>

    <button type="submit">Giriş Yap</button>
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


