<?php
// Bu form hem ürün ekleme hem de düzenleme için kullanılır.
// Gerekli değişkenler:
// $product: Düzenleme modunda ürün bilgilerini içeren bir dizi. Ekleme modunda boş bir dizi veya null olmalıdır.
// $allCats: Tüm kategorileri içeren bir dizi.
// $form_action: Formun gönderileceği URL.
// $button_text: Gönder butonunun metni (örn: "Ekle", "Güncelle").

$name = isset($product['name']) ? htmlspecialchars($product['name']) : '';
$description = isset($product['description']) ? htmlspecialchars($product['description']) : '';
$price = isset($product['price']) ? $product['price'] : '';
$category_id = isset($product['category_id']) ? $product['category_id'] : '';
?>

<form method="post" enctype="multipart/form-data" action="<?php echo $form_action; ?>">
    <?php if (isset($product['id'])): ?>
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <?php endif; ?>

    <label>Ürün Adı:</label>
    <input type="text" name="name" value="<?php echo $name; ?>" required>
    
    <label>Açıklama:</label>
    <textarea name="description" required><?php echo $description; ?></textarea>
    
    <label>Fiyat:</label>
    <input type="number" step="0.01" name="price" value="<?php echo $price; ?>" required>
    
    <label>Kategori:</label>
    <select name="category_id" required>
        <?php foreach($allCats as $c): ?>
            <option value="<?php echo $c['id']; ?>" <?php if($c['id'] == $category_id) echo 'selected'; ?>>
                <?php echo htmlspecialchars($c['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <label>Görsel:</label>
    <?php if (isset($product['image_path'])): ?>
        <p>Mevcut görsel (değiştirmek istemiyorsanız yeni dosya seçmeyin):</p>
        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Mevcut Görsel" class="product-image-preview">
    <?php endif; ?>
    <input type="file" name="image" <?php if (!isset($product['id'])) echo 'required'; ?>>
    
    <button type="submit"><?php echo $button_text; ?></button>
</form> 