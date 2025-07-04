<?php
include 'includes/header.php';

$id = $_GET['id'] ?? null;
$product = getProductById($pdo, $id);

if(!$product) {
    echo "<p>Ürün bulunamadı.</p>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="product-detail-content">
    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <p><?php echo htmlspecialchars($product['description']); ?></p>
    <div class="price"><?php echo $product['price']; ?> TL</div>

    <?php if(isLoggedIn()): ?>
        <form action="cart.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    <?php else: ?>
        <a href="login.php">
            <button type="button">Add to Cart</button>
        </a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>