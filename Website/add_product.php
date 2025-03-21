<?php

include 'includes/header.php';

if(!isLoggedIn() || !isAdmin($pdo)) {
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
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
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
        background-color:rgb(0, 0, 0);
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
    }
    .content button:hover {
        background-color: #21867a;
    }
</style>

<div class="content">
    <h1>Ürün Ekle</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Ürün Adı:</label>
        <input type="text" name="name" required>
        
        <label>Açıklama:</label>
        <textarea name="description" required></textarea>
        
        <label>Fiyat:</label>
        <input type="number" step="0.01" name="price" required>
        
        <label>Kategori:</label>
        <select name="category_id" required>
            <?php foreach($allCats as $c): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
            <?php endforeach; ?>
        </select>
        
        <label>Görsel:</label>
        <input type="file" name="image" required>
        
        <button type="submit">Ekle</button>
    </form>
</div>


