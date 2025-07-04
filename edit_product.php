<?php
include 'includes/header.php';

if(!isLoggedIn() || !isAdmin()) {
    echo "<p>Bu sayfaya erişim yetkiniz yok.</p>";
    include 'includes/footer.php';
    exit;
}

$id = $_GET['id'] ?? null;
if(!$id) {
    echo "<p>Ürün ID belirtilmedi.</p>";
    include 'includes/footer.php';
    exit;
}

$product = getProductById($pdo, $id);
if(!$product) {
    echo "<p>Ürün bulunamadı.</p>";
    include 'includes/footer.php';
    exit;
}

// Kategorileri çek
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$allCats = $catStmt->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $imagePath = $product['image_path']; // Varsayılan eski görsel
    if(isset($_FILES['image']) && !empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/products/';

        // --- GÜVENLİK DÜZELTMESİ BAŞLANGIÇ ---

        // 1. Dosya türü kontrolü (MIME type)
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileMimeType = mime_content_type($_FILES['image']['tmp_name']);
        if(!in_array($fileMimeType, $allowedMimes)) {
            echo "<div class='content'><p>Hata: Yalnızca JPG, PNG ve GIF dosyaları yüklenebilir. Diğer değişiklikler kaydedilmedi.</p></div>";
            include 'includes/footer.php';
            exit;
        }

        // 2. Güvenli dosya adı oluşturma
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $safeFileName = uniqid('prod_', true) . '.' . $fileExtension;
        $uploadFile = $uploadDir . $safeFileName;
        
        // --- GÜVENLİK DÜZELTMESİ SON ---

        if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            // İsteğe bağlı: Eski görseli sunucudan silmek isterseniz burada silebilirsiniz.
            // if(file_exists($product['image_path'])) {
            //     unlink($product['image_path']);
            // }
            $imagePath = $uploadFile;
        } else {
            echo "<div class='content'><p>Görsel yüklenirken hata oluştu. Ürün bilgileri eski görselle güncellenecek.</p></div>";
        }
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_path = ?, category_id = ? WHERE id = ?");
    $stmt->execute([$name, $desc, $price, $imagePath, $category_id, $id]);

    echo "<div class='content'><p>Ürün başarıyla güncellendi.</p></div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="content">
    <h1>Ürün Düzenle</h1>
    <?php 
        $form_action = 'edit_product.php?id=' . $product['id'];
        $button_text = 'Güncelle';
        include 'includes/_product_form.php'; 
    ?>
</div>

<?php include 'includes/footer.php'; ?>
