<?php
session_start();
include 'includes/db.php';

// Güvenlik: Sadece ADMIN girebilir
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

// URL'den ID'yi al (Hangi üyeyi düzenliyoruz?)
if (!isset($_GET['id'])) {
    header("Location: admin-panel.php");
    exit;
}
$uye_id = $_GET['id'];

// KAYDETME İŞLEMİ (Form gönderildiyse)
$mesaj = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $antrenor = $_POST['antrenor'];
    $uyelik_bitis = $_POST['uyelik_bitis'];
    $antrenman = $_POST['antrenman_programi'];
    $diyet = $_POST['diyet_listesi'];

    $sqlGuncelle = "UPDATE uyeler SET antrenor=?, uyelik_bitis=?, antrenman_programi=?, diyet_listesi=? WHERE id=?";
    $stmtGuncelle = $db->prepare($sqlGuncelle);
    $stmtGuncelle->execute([$antrenor, $uyelik_bitis, $antrenman, $diyet, $uye_id]);
    
    $mesaj = "<div class='success-message'>Bilgiler başarıyla güncellendi!</div>";
}

// Üyenin mevcut bilgilerini çek (Kutucukları doldurmak için)
$sql = "SELECT * FROM uyeler WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$uye_id]);
$uye = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="form-container" style="max-width: 800px;">
        <h2 style="border-bottom: 1px solid #333; padding-bottom: 10px;">
            Üye Düzenle: <span style="color:var(--accent)"><?php echo htmlspecialchars($uye['kullanici_adi']); ?></span>
        </h2>
        
        <?php echo $mesaj; ?>

        <form method="POST">
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex:1;">
                    <label>Antrenör Adı:</label>
                    <input type="text" name="antrenor" value="<?php echo htmlspecialchars($uye['antrenor']); ?>">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Üyelik Bitiş Tarihi:</label>
                    <input type="date" name="uyelik_bitis" value="<?php echo $uye['uyelik_bitis']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Antrenman Programı:</label>
                <textarea name="antrenman_programi" rows="6"><?php echo htmlspecialchars($uye['antrenman_programi']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Diyet Listesi:</label>
                <textarea name="diyet_listesi" rows="6"><?php echo htmlspecialchars($uye['diyet_listesi']); ?></textarea>
            </div>

            <button type="submit" class="form-btn">KAYDET VE GÜNCELLE</button>
            <br><br>
            <a href="admin-panel.php" style="color: #666; font-size: 14px;">&larr; Listeye Geri Dön</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>