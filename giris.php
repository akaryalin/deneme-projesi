<?php
// "Bilekliği" YAZABİLMEK İÇİN OTURUMU BAŞLATMAK ZORUNDAYIZ
session_start();

// Eğer kullanıcı zaten giriş yapmışsa, onu ana sayfaya yönlendir.
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include 'includes/db.php'; 

$hata_mesaji = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $sifre = $_POST['sifre'];

    if (empty($kullanici_adi) || empty($sifre)) {
        $hata_mesaji = "Kullanıcı adı ve şifre boş bırakılamaz.";
    } else {
        try {
            $sql = "SELECT * FROM uyeler WHERE kullanici_adi = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$kullanici_adi]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC); 

            if ($user && password_verify($sifre, $user['sifre'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['kullanici_adi'] = $user['kullanici_adi'];
                $_SESSION['rol'] = $user['rol'];
                session_write_close(); 
                header("Location: index.php");
                exit; 
            } else {
                $hata_mesaji = "Kullanıcı adı veya şifre hatalı!";
            }
        } catch (PDOException $e) {
            $hata_mesaji = "Bir veritabanı hatası oluştu: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="form-container">
    
    <h2>Üye Girişi</h2>

    <?php if (!empty($hata_mesaji)) { echo "<div class='error-message'>$hata_mesaji</div>"; } ?>

    <form action="giris.php" method="POST">
        
        <div class="form-group">
            <label for="kullanici_adi">Kullanıcı Adı:</label>
            <input type="text" id="kullanici_adi" name="kullanici_adi" required>
        </div>

        <div class="form-group">
            <label for="sifre">Şifre:</label>
            <input type="password" id="sifre" name="sifre" required>
        </div>

        <button type="submit" class="form-btn">Giriş Yap</button>
    </form>

</div>

<?php include 'includes/footer.php'; ?>