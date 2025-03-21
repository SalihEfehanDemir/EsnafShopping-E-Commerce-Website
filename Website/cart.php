<?php
include 'includes/header.php';

if(!isLoggedIn()) {
    echo "<p>Sepete erişmek için giriş yapmalısınız.</p>";
    include 'includes/footer.php';
    exit;
}

// Ürün ekleme işlemi
if(isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['user_id'];

    // Sepette ürün var mı kontrol et
    $check = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
    $check->execute([$userId, $productId]);
    $cartItem = $check->fetch();

    if($cartItem) {
        $update = $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?");
        $update->execute([$cartItem['id']]);
    } else {
        $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->execute([$userId, $productId, 1]);
    }
    echo "<p>Ürün sepete eklendi.</p>";
}

// Ürünü sepetten kaldırma işlemi
if(isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    $delete = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
    $delete->execute([$_SESSION['user_id'], $removeId]);
    echo "<p>Ürün sepetten çıkarıldı.</p>";
}

// Satın alma işlemi
if(isset($_POST['purchase'])) {
    // Burada normalde sipariş tablosuna kayıt, ödeme işlemleri vb. yapılır.
    // Demo için sadece sepeti temizliyoruz ve mesaj gösteriyoruz.

    $clearCart = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $clearCart->execute([$_SESSION['user_id']]);
    echo "<p>Siparişiniz alınmıştır! Teşekkür ederiz.</p>";

    exit;
}

// Sepeti listeleme
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image_path FROM cart_items c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!$cartItems) {
    echo "<p>Sepetiniz boş.</p>";
} else {
    echo "<h1>Sepetiniz</h1>";
    echo "<table style='width:100%; border-collapse:collapse;' border='1'>
            <tr>
                <th>Ürün Resmi</th>
                <th>Ürün Adı</th>
                <th>Adet</th>
                <th>Birim Fiyat</th>
                <th>Toplam</th>
                <th>İşlem</th>
            </tr>";

    $total = 0;
    foreach($cartItems as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        echo "<tr>
                <td style='text-align:center;'><img src='".htmlspecialchars($item['image_path'])."' alt='".htmlspecialchars($item['name'])."' style='width:100px;height:100px;object-fit:cover;'></td>
                <td>".htmlspecialchars($item['name'])."</td>
                <td>".$item['quantity']."</td>
                <td>".$item['price']." TL</td>
                <td>".$subtotal." TL</td>
                <td><a href='?remove=".$item['product_id']."'>Kaldır</a></td>
              </tr>";
    }
    echo "</table>";
    echo "<h2>Sepet Toplamı: ".$total." TL</h2>";

    // Satın Al butonu
    echo "<form method='post'>
            <button type='submit' name='purchase' style='padding:10px 20px;  background: black; color:white; border:none; border-radius:5px; cursor:pointer;'>Satın Al</button>
          </form>";
}

