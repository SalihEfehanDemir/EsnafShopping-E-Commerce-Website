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
    
    // Flash Mesajı Ayarla
    $_SESSION['flash_message'] = "Ürün başarıyla sepete eklendi!";

    // UX İYİLEŞTİRMESİ: Kullanıcıyı geldiği sayfaya geri yönlendir.
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Ürünü sepetten kaldırma işlemi (GÜVENLİ HALE GETİRİLDİ)
if(isset($_POST['remove_from_cart'])) {
    if(isset($_POST['product_id']) && isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $removeId = $_POST['product_id'];
        $delete = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
        $delete->execute([$_SESSION['user_id'], $removeId]);
        
        // Flash Mesajı Ayarla
        $_SESSION['flash_message'] = "Ürün sepetten çıkarıldı.";
        
        // Sepet sayfasını yeniden yükle
        header("Location: cart.php");
        exit;

    } else {
        echo "<p>Geçersiz istek veya CSRF token hatası.</p>";
    }
}

// Satın alma işlemi
if(isset($_POST['purchase'])) {
    if(isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Burada normalde sipariş tablosuna kayıt, ödeme işlemleri vb. yapılır.
        // Demo için sadece sepeti temizliyoruz ve mesaj gösteriyoruz.
        $clearCart = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $clearCart->execute([$_SESSION['user_id']]);
        
        $_SESSION['flash_message'] = "Siparişiniz başarıyla alınmıştır! Teşekkür ederiz.";
        header("Location: index.php"); // Kullanıcıyı anasayfaya yönlendir
        exit;
    } else {
        echo "<p>Geçersiz istek veya CSRF token hatası.</p>";
    }
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
                <td>
                    <form action='cart.php' method='post' style='margin:0;'>
                        <input type='hidden' name='remove_from_cart' value='1'>
                        <input type='hidden' name='product_id' value='".$item['product_id']."'>
                        <input type='hidden' name='csrf_token' value='".$csrf_token."'>
                        <button type='submit' style='background:none; border:none; color:red; cursor:pointer; text-decoration:underline;'>Kaldır</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
    echo "<h2>Sepet Toplamı: ".$total." TL</h2>";

    // Satın Al butonu
    echo "<form method='post'>
            <input type='hidden' name='csrf_token' value='".$csrf_token."'>
            <button type='submit' name='purchase' style='padding:10px 20px;  background: black; color:white; border:none; border-radius:5px; cursor:pointer;'>Satın Al</button>
          </form>";
}

