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


    


<div class="content">
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
<!-- Sayfaya özel stil -->
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
        align-items: center;
        flex-direction: column;
    }

    .content img {
        width: 300px;
        height: auto;
        margin-bottom: 20px;
    }

    .content h1 {
        font-size: 2em;
        color: #333;
        margin-bottom: 10px;
    }

    .content p {
        font-size: 1.2em;
        color: #666;
        margin-bottom: 20px;
    }

    .content .price {
        font-size: 1.5em;
        color: #2a9d8f;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .content button {
        padding: 10px 20px;
        background-color: black;
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