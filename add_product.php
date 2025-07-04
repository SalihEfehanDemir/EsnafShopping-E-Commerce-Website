<?php

include 'includes/header.php';

if(!isLoggedIn() || !isAdmin()) {
    echo "<p>Bu sayfaya erişim yetkiniz yok.</p>";
    include 'includes/footer.php';
    exit;
}

$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$allCats = $catStmt->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/products/';
        
        // --- GÜVENLİK DÜZELTMESİ BAŞLANGIÇ ---

        // 1. Dosya türü kontrolü (MIME type)
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileMimeType = mime_content_type($_FILES['image']['tmp_name']);
        if(!in_array($fileMimeType, $allowedMimes)) {
            echo "<div class='content'><p>Hata: Yalnızca JPG, PNG ve GIF dosyaları yüklenebilir.</p></div>";
            include 'includes/footer.php';
            exit;
        }

        // 2. Güvenli dosya adı oluşturma
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $safeFileName = uniqid('prod_', true) . '.' . $fileExtension;
        $uploadFile = $uploadDir . $safeFileName;

        // --- GÜVENLİK DÜZELTMESİ SON ---

        if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_path, category_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $desc, $price, $uploadFile, $category_id]);
            echo "<div class='content'><p>Ürün başarıyla eklendi.</p></div>";
            include 'includes/footer.php';
            exit;
        } else {
            echo "<div class='content'><p>Görsel yüklenirken hata oluştu.</p></div>";
        }
    }
}
?>

<div class="content">
    <h1>Ürün Ekle</h1>
    <?php 
        $product = null; // Ekleme modunda $product boş
        $form_action = 'add_product.php';
        $button_text = 'Ekle';
        include 'includes/_product_form.php'; 
    ?>
</div>

<?php include 'includes/footer.php'; ?>


