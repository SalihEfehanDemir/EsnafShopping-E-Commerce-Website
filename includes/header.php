<?php
session_start();

include 'db.php';
include 'functions.php';

// CSRF Token Oluşturma
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - EsnafShopping</title>
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: black;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header .logo {
            font-size: 1.5em;
            font-weight: bold;
            color: white;
        }
        header .logo a {
    font-size: 1.5em;
    font-weight: bold;
    color: white;
    text-decoration: none;
}

        header nav a {
            margin: 0 10px;
            text-decoration: none;
            color: white;
            display: inline-flex;
            align-items: center;
        }

        header nav a i {
            margin-right: 5px;
        }

        header nav a:hover {
            color: #2a9d8f;
        }

        .content {
            padding: 20px;
        }

        .content h1 {
            font-size: 2em;
            color: #333;
        }

        #containerSlider {
            margin: auto;
            width: 90%;
            text-align: center;
            padding-top: 20px;
            box-sizing: border-box;
        }

        #containerSlider img {
            width: 100%;
            height: auto;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px;
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
            color: rgb(0, 0, 0);
            font-weight: bold;
            margin: 10px 0;
        }

        .product-card button {
            padding: 10px 20px;
            background-color: rgb(0, 0, 0);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .product-card button:hover {
            background-color: #21867a;
        }

        @media (max-width: 732px) {
            #containerSlider img {
                height: 12em;
            }
        }

        @media (max-width: 500px) {
            #containerSlider img {
                height: 10em;
            }
        }
    </style>
</head>
<body>

    <!-- Flash Message Gösterimi -->
    <?php if(isset($_SESSION['flash_message'])): ?>
        <div class="flash-message">
            <?php 
                echo $_SESSION['flash_message']; 
                unset($_SESSION['flash_message']); // Mesajı gösterdikten sonra sil
            ?>
        </div>
    <?php endif; ?>

    <header>
        <div class="logo">
            <a href="index.php">Esnaf Shopping</a>
        </div>
        <nav>
        
        <a href="index.php"><i class="fa-solid fa-house"></i> Home</a>
            <a href="categories.php"><i class="fa-solid fa-list"></i> Categories</a>
            <?php if (isLoggedIn()): ?>
                <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> My Cart</a>
                <?php if (isAdmin($pdo)): ?>
                    <a href="add_product.php"><i class="fa-solid fa-plus"></i> Add Product (Admin)</a>
                <?php endif; ?>
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            <?php else: ?>
                <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
                <a href="register.php"><i class="fa-solid fa-user-plus"></i> Create Account</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <!-- İçerik buraya gelir -->
    </main>
</body>
</html>