<?php
include 'includes/header.php';

if(!isLoggedIn() || !isAdmin($pdo)) {
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
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/products/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $imagePath = $uploadFile;
        } else {
            echo "<div class='content'><p>Görsel yüklenirken hata oluştu. Ürün bilgileri güncellenmeye çalışılacak.</p></div>";
        }
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_path = ?, category_id = ? WHERE id = ?");
    $stmt->execute([$name, $desc, $price, $imagePath, $category_id, $id]);

    echo "<div class='content'><p>Ürün başarıyla güncellendi.</p></div>";
    include 'includes/footer.php';
    exit;
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
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-direction: column;
        max-width: 500px;
        margin: 0 auto;
    }
    .content h1 {
        font-size: 2em;
        color: #333;
        margin-bottom: 20px;
    }
    .content label {
        margin: 10px 0 5px;
        font-weight: bold;
    }
    .content input[type="text"],
    .content input[type="number"],
    .content textarea,
    .content input[type="file"],
    .content select {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .content button {
        padding: 10px 20px;
        background-color: #2a9d8f;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
    }
    .content button:hover {
        background-color: #21867a;
    }
    img.product-image-preview {
        width:100px;
        height:100px;
        object-fit:cover;
        margin:10px 0;
        border:1px solid #ccc;
    }
</style>

<div class="content">
    <h1>Ürün Düzenle</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Ürün Adı:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        
        <label>Açıklama:</label>
        <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        
        <label>Fiyat:</label>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        
        <label>Kategori:</label>
        <select name="category_id" required>
            <?php foreach($allCats as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php if($c['id'] == $product['category_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($c['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Görsel (değiştirmek istemiyorsanız boş bırakın):</label>
        <input type="file" name="image">
        
        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Mevcut Görsel" class="product-image-preview">
        
        <button type="submit">Güncelle</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
