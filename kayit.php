<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php'; 
include 'includes/header.php'; 

// Form işlemi sonucu mesajını tutacak değişken
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre']; 

    $guvenli_sifre = password_hash($sifre, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO uyeler (kullanici_adi, email, sifre) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$kullanici_adi, $email, $guvenli_sifre]);
        
        // Başarılı olursa mesaj değişkenine yazalım
        $mesaj = "<div class='success-message'>Kayıt başarılı! Artık giriş yapabilirsiniz.</div>";

    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) { 
            $mesaj = "<div class='error-message'>Hata: Bu kullanıcı adı veya e-posta zaten alınmış!</div>";
        } else {
            $mesaj = "<div class='error-message'>Hata: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<div class="form-container">

    <h2>Yeni Üye Kaydı</h2>
    
    <?php echo $mesaj; ?>
    
    <form action="kayit.php" method="POST">
        
        <div class="form-group">
            <label for="kullanici_adi">Kullanıcı Adı:</label>
            <input type="text" id="kullanici_adi" name="kullanici_adi" required>
        </div>

        <div class="form-group">
            <label for="email">E-posta Adresiniz:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="sifre">Şifre:</label>
            <input type="password" id="sifre" name="sifre" required>
        </div>

        <button type="submit" class="form-btn">Kayıt Ol</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>