<?php
include 'includes/header.php';

if(!isLoggedIn() || !isAdmin($pdo)) {
    echo "<p>Bu sayfaya erişim yetkiniz yok.</p>";
    include 'includes/footer.php';
    exit;
}

$id = $_GET['id'] ?? null;
if($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Ürün silindi.";
} else {
    $message = "Ürün ID bulunamadı.";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .content {
        padding: 20px;
        text-align: center;
    }
    .content p {
        font-size: 1.2em;
        color: #333;
    }
    .content a button {
        padding: 10px 20px;
        background-color: #2a9d8f;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
        margin-top:20px;
    }
    .content a button:hover {
        background-color: #21867a;
    }
</style>

<div class="content">
    <p><?php echo $message; ?></p>
    <a href="index.php"><button>Anasayfaya Dön</button></a>
</div>

<?php include 'includes/footer.php'; ?>
