<?php
include 'includes/header.php';

$products = getProducts($pdo);
?>

<div id="containerSlider">
    <div id="slidingImage"><img src="uploads/img1.png" alt="image1"></div>
    <div id="slidingImage"><img src="uploads/img2.png" alt="image2"></div>
    <div id="slidingImage"><img src="uploads/img3.png" alt="image3"></div>
    <div id="slidingImage"><img src="uploads/img4.png" alt="image4"></div>
</div>

<div class="content">
    <h1>En çok satanlar</h1>
    <div class="products">
    <?php foreach($products as $product): ?>
    <div class="product-card">
        <a href="product.php?id=<?php echo $product['id']; ?>">
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </a>
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <div class="price"><?php echo $product['price']; ?> TL</div>
        <?php if(isLoggedIn()): ?>
            <form action="cart.php" method="post" style="margin-bottom:10px;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        <?php else: ?>
            <a href="login.php">
                <button type="button">Add to Cart</button>
            </a>
        <?php endif; ?>

        <?php if(isAdmin($pdo)): ?>
            <div style="margin-top:10px; display:flex; gap:10px; justify-content:center;">
                <a href="edit_product.php?id=<?php echo $product['id']; ?>">
                    <button type="button">Edit</button>
                </a>
                <a href="delete_product.php?id=<?php echo $product['id']; ?>">
                    <button type="button">Delete</button>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
