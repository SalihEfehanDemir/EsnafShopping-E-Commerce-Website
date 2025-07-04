# Esnaf Shopping - Geliştirilmiş PHP E-Ticaret Projesi

Bu proje, temel PHP ve MySQL kullanılarak oluşturulmuş basit bir e-ticaret platformudur. Proje, orijinal halinden alınarak yapısal olarak iyileştirilmiş, kritik güvenlik açıkları kapatılmış ve modern PHP standartlarına daha uygun hale getirilmiştir.

## İyileştirilmiş Özellikler

*   **Güvenli Kimlik Doğrulama:** `password_hash` ile güvenli şifreleme ve CSRF token'ları ile korunan oturum yönetimi.
*   **Güvenli Dosya Yükleme:** Sadece izin verilen dosya türlerinin (MIME type) yüklenmesine olanak tanıyan ve RCE (Uzaktan Kod Çalıştırma) zafiyetini engelleyen mekanizma.
*   **SQL Enjeksiyon Koruması:** Tüm veritabanı sorgularında PDO ve *prepared statement* kullanımı.
*   **Optimize Edilmiş Kod:** Tekrarlanan kod blokları (DRY prensibi) azaltıldı, `isAdmin()` gibi fonksiyonlar session kullanarak veritabanı yükünü hafifletecek şekilde optimize edildi.
*   **Merkezi Stil Yönetimi:** Tüm dağınık CSS kodları, bakımı kolay tek bir `style.css` dosyasına taşındı.
*   **İyileştirilmiş Kullanıcı Deneyimi (UX):** İşlem sonrası yönlendirmeler ve "flash message" bildirimleri eklendi.

## Teknolojiler

*   PHP (7.x ve üstü)
*   MySQL / MariaDB
*   HTML5, CSS3

## Yerel Kurulum ve Çalıştırma

Projeyi yerel makinenizde çalıştırmak için aşağıdaki adımları izleyin.

1.  **Yerel Sunucu Kurulumu:**
    *   **XAMPP** veya benzeri bir yerel sunucu ortamı kurun ve **Apache** ile **MySQL** servislerini başlatın.

2.  **Projeyi Klonlama:**
    ```bash
    git clone https://github.com/SalihEfehanDemir/EsnafShopping-E-Commerce-Website.git
    ```
    *   Klonladığınız `EsnafShopping-E-Commerce-Website` klasörünü XAMPP'nin `htdocs` dizinine taşıyın.

3.  **Veritabanı Kurulumu:**
    *   Tarayıcınızdan `http://localhost/phpmyadmin` adresine gidin.
    *   `includes/db.php` dosyasında belirtilen kullanıcı adı (`esnafsho_ecommerce`) ve şifre (`sEQwU8uZyR2xLMNpMqDB`) ile yeni bir kullanıcı hesabı oluşturun. **Önemli:** Kullanıcıyı oluştururken **"Create database with same name and grant all privileges"** seçeneğini işaretleyin. Bu, `esnafsho_ecommerce` adında bir veritabanı oluşturup tüm yetkileri bu kullanıcıya verecektir.
    *   Oluşturulan `esnafsho_ecommerce` veritabanını seçin ve üst menüden **"Import" (İçe Aktar)** sekmesine tıklayın.
    *   Proje dizinindeki `database_setup.sql` dosyasını seçip içe aktarın.

4.  **Çalıştırma:**
    *   Kurulum tamamlandı! Tarayıcınızdan `http://localhost/EsnafShopping-E-Commerce-Website/` adresine giderek projeyi görüntüleyebilirsiniz.

## Authors

*   [Salih Efehan Demir](https://github.com/SalihEfehanDemir)
*   [İbrahim Bayır](https://github.com/ibrahimbayir)
*   [Mehmet Çavdar](https://github.com/mehmetcavdarr)

## Project Structure

- `index.php`: Homepage
- `login.php` / `register.php`: Authentication
- `add_product.php`, `edit_product.php`, `delete_product.php`: Admin functionalities
- `cart.php`, `product.php`: Shopping experience
- `assets/css/style.css`: Styling

## License

This project is for academic and demonstration purposes only.
