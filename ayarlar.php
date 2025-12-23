<?php
session_start();
include 'includes/db.php';

// Güvenlik
if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eski_sifre = $_POST['eski_sifre'];
    $yeni_sifre = $_POST['yeni_sifre'];
    $yeni_sifre_tekrar = $_POST['yeni_sifre_tekrar'];

    // 1. Boş alan var mı?
    if (empty($eski_sifre) || empty($yeni_sifre) || empty($yeni_sifre_tekrar)) {
        $mesaj = "<div class='error-message'>Lütfen tüm alanları doldurun.</div>";
    } 
    // 2. Yeni şifreler uyuşuyor mu?
    elseif ($yeni_sifre !== $yeni_sifre_tekrar) {
        $mesaj = "<div class='error-message'>Yeni şifreler birbiriyle uyuşmuyor!</div>";
    }
    // 3. Yeni şifre çok mu kısa?
    elseif (strlen($yeni_sifre) < 6) {
        $mesaj = "<div class='error-message'>Yeni şifre en az 6 karakter olmalı.</div>";
    }
    else {
        // 4. Eski şifre doğru mu? (Veritabanından kontrol et)
        $sql = "SELECT sifre FROM uyeler WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($eski_sifre, $user['sifre'])) {
            // HER ŞEY DOĞRU! Şifreyi güncelle.
            $yeni_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
            
            $sqlUpdate = "UPDATE uyeler SET sifre = ? WHERE id = ?";
            $stmtUpdate = $db->prepare($sqlUpdate);
            
            if ($stmtUpdate->execute([$yeni_hash, $user_id])) {
                $mesaj = "<div class='success-message'>Şifreniz başarıyla değiştirildi!</div>";
            } else {
                $mesaj = "<div class='error-message'>Bir hata oluştu.</div>";
            }
        } else {
            $mesaj = "<div class='error-message'>Eski şifrenizi yanlış girdiniz.</div>";
        }
    }
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 50px;">
    
    <div class="form-container" style="max-width: 600px;">
        <h2 style="color: var(--accent); margin-bottom: 20px; text-align: center;">⚙️ Hesap Ayarları</h2>
        <p style="color: var(--text-muted); text-align: center; margin-bottom: 30px;">Hesap güvenliğinizi sağlamak için şifrenizi güncelleyebilirsiniz.</p>

        <?php echo $mesaj; ?>

        <form method="POST">
            <div class="form-group">
                <label>Mevcut Şifreniz:</label>
                <input type="password" name="eski_sifre" placeholder="Şu anki şifrenizi girin" required>
            </div>

            <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">

            <div class="form-group">
                <label>Yeni Şifre:</label>
                <input type="password" name="yeni_sifre" placeholder="Yeni şifrenizi belirleyin" required>
            </div>

            <div class="form-group">
                <label>Yeni Şifre (Tekrar):</label>
                <input type="password" name="yeni_sifre_tekrar" placeholder="Yeni şifreyi tekrar yazın" required>
            </div>

            <button type="submit" class="form-btn">ŞİFREYİ GÜNCELLE</button>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="profil.php" style="color: #666; font-size: 0.9rem;">Vazgeç ve Profile Dön</a>
            </div>
        </form>
    </div>

</div>

<?php include 'includes/footer.php'; ?>