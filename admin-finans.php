<?php
session_start();
include 'includes/db.php';

// Sadece Admin Girebilir
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

$mesaj = "";

// 1. ÖDEME EKLEME İŞLEMİ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uye_id = $_POST['uye_id'];
    $tutar = $_POST['tutar'];
    $aciklama = $_POST['aciklama'];

    if (!empty($uye_id) && !empty($tutar)) {
        $sqlEkle = "INSERT INTO odemeler (uye_id, tutar, aciklama) VALUES (?, ?, ?)";
        $stmtEkle = $db->prepare($sqlEkle);
        $stmtEkle->execute([$uye_id, $tutar, $aciklama]);
        $mesaj = "<div class='success-message'>Ödeme başarıyla kasaya işlendi!</div>";
    }
}

// 2. TOPLAM CİROYU HESAPLA
$sqlToplam = "SELECT SUM(tutar) as toplam FROM odemeler";
$stmtToplam = $db->prepare($sqlToplam);
$stmtToplam->execute();
$toplamCiro = $stmtToplam->fetch(PDO::FETCH_ASSOC)['toplam'];

// Sayı yoksa 0 yazsın
if(!$toplamCiro) $toplamCiro = 0;

include 'includes/header.php';
?>

<div class="container" style="margin-top: 50px;">
    
    <div style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid var(--accent); text-align: center; margin-bottom: 30px;">
        <h3 style="color: #fff;">TOPLAM KASA GELİRİ</h3>
        <div style="font-size: 3rem; font-weight: bold; color: var(--accent); margin-top: 10px;">
            <?php echo number_format($toplamCiro, 2); ?> ₺
        </div>
        <p style="color: var(--text-muted);">Spor salonunun toplam cirosu</p>
    </div>

    <?php echo $mesaj; ?>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 1; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: var(--accent); margin-bottom: 20px;">Yeni Ödeme Girişi</h3>
            
            <form method="POST">
                <div class="form-group">
                    <label>Üye Seç:</label>
                    <select name="uye_id" style="width: 100%; padding: 15px; background: #0f0f0f; border: 1px solid #333; color: #fff; border-radius: 10px;" required>
                        <option value="">-- Bir Üye Seçin --</option>
                        <?php
                        // Tüm üyeleri listeye çekelim
                        $sqlUyeler = "SELECT id, kullanici_adi FROM uyeler ORDER BY kullanici_adi ASC";
                        $stmtUyeler = $db->query($sqlUyeler);
                        while($row = $stmtUyeler->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='".$row['id']."'>".$row['kullanici_adi']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tutar (TL):</label>
                    <input type="number" name="tutar" step="0.01" placeholder="Örn: 1500" required>
                </div>

                <div class="form-group">
                    <label>Açıklama / Paket:</label>
                    <input type="text" name="aciklama" placeholder="Örn: 3 Aylık Gold Üyelik" required>
                </div>

                <button type="submit" class="form-btn">Ödemeyi Kaydet</button>
            </form>
        </div>

        <div style="flex: 1; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: #fff; margin-bottom: 20px;">Son Ödemeler</h3>
            <table style="width: 100%; color: var(--text-muted); text-align: left;">
                <?php
                // Son 10 ödemeyi çek
                $sqlGecmis = "SELECT o.*, u.kullanici_adi FROM odemeler o JOIN uyeler u ON o.uye_id = u.id ORDER BY o.id DESC LIMIT 10";
                $stmtGecmis = $db->query($sqlGecmis);
                
                while($odeme = $stmtGecmis->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr style='border-bottom: 1px solid #222;'>";
                    echo "<td style='padding: 10px 0;'><strong style='color:#fff;'>".$odeme['kullanici_adi']."</strong><br><small>".$odeme['aciklama']."</small></td>";
                    echo "<td style='text-align: right; color: var(--accent); font-weight: bold;'>".number_format($odeme['tutar'], 2)." ₺</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>