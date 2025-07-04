<?php
include 'includes/header.php';

if(!isLoggedIn() || !isAdmin()) {
    echo "<p>Bu sayfaya erişim yetkiniz yok.</p>";
    include 'includes/footer.php';
    exit;
}

// --- GÜVENLİK DÜZELTMESİ: POST ve CSRF Kontrolü ---
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['id']) && isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Ürün başarıyla silindi.";
    } else {
        $message = "Geçersiz istek veya CSRF token hatası.";
    }
} else {
    $message = "Geçersiz istek metodu.";
}
// --- GÜVENLİK DÜZELTMESİ SONU ---
?>

<div class="content-centered">
    <p><?php echo $message; ?></p>
    <a href="index.php"><button>Anasayfaya Dön</button></a>
</div>

<?php include 'includes/footer.php'; ?>
