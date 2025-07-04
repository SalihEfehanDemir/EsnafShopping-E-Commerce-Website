<?php
include 'includes/header.php';

$catId = $_GET['cat_id'] ?? null;

// Kategorileri çek
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

echo "<style>
.content {
    padding: 20px;
    font-family: Arial, sans-serif;
}
.category-list {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.category-item {
    border:1px solid #ddd;
    padding:10px;
    border-radius:5px;
    background:#fff;
    transition: transform 0.2s;
    text-align:center;
    width:150px;
}
.category-item:hover {
    transform: scale(1.05);
}
.category-item a {
    text-decoration:none;
    color:#333;
    font-weight:bold;
}
.products {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top:20px;
}
.product-card {
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
    padding: 15px;
    background: #fff;
    transition: transform 0.2s ease-in-out;
}
.product-card:hover {
    transform: scale(1.05);
}
.product-card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 5px;
    cursor: pointer;
}
.product-card h3 {
    font-size: 1.2em;
    color: #333;
    margin: 10px 0;
}
.product-card p {
    font-size: 0.9em;
    color: #666;
    margin: 5px 0;
}
.product-card .price {
    font-size: 1.1em;
    color: #2a9d8f;
    font-weight: bold;
    margin: 10px 0;
}
.product-card button {
    padding: 10px 20px;
    background-color: #2a9d8f;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1em;
    cursor: pointer;
}
.product-card button:hover {
    background-color: #21867a;
}
</style>";

echo "<div class='content'>";
echo "<h1>Kategoriler</h1>";
echo "<div class='category-list'>";
foreach($categories as $cat) {
    echo "<div class='category-item'><a href='categories.php?cat_id={$cat['id']}'>{$cat['name']}</a></div>";
}
echo "</div>";

if($catId) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY created_at DESC");
    $stmt->execute([$catId]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($products) {
        echo "<h2>Seçilen Kategori Ürünleri</h2>";
        echo "<div class='products'>";
        foreach($products as $product) {
            echo "<div class='product-card'>
                    <a href='product.php?id={$product['id']}'>
                        <img src='".htmlspecialchars($product['image_path'])."' alt='".htmlspecialchars($product['name'])."'>
                    </a>
                    <h3>".htmlspecialchars($product['name'])."</h3>
                    <p>".htmlspecialchars($product['description'])."</p>
                    <div class='price'>".$product['price']." TL</div>";
            if(isLoggedIn()) {
                echo "<form action='cart.php' method='post'>
                          <input type='hidden' name='product_id' value='{$product['id']}'>
                          <button type='submit' name='add_to_cart'>Add to Cart</button>
                      </form>";
            } else {
                echo "<a href='login.php'><button>Add to Cart</button></a>";
            }
            if(isAdmin($pdo)) {
                echo "<div style='margin-top:10px; display:flex; gap:10px; justify-content:center;'>
                          <a href='edit_product.php?id={$product['id']}'><button type='button'>Edit</button></a>
                          <a href='delete_product.php?id={$product['id']}'><button type='button'>Delete</button></a>
                      </div>";
            }
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>Bu kategoride henüz ürün yok.</p>";
    }
}

echo "</div>";

