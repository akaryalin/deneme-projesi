<?php
session_start();
include 'includes/db.php';

// Sadece Admin Girebilir
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Hangi dersin listesi?
if (!isset($_GET['id'])) {
    header("Location: admin-dersler.php");
    exit;
}
$ders_id = $_GET['id'];

// ÜYE SİLME İŞLEMİ (Dersten Çıkarma)
if (isset($_GET['sil_kayit'])) {
    $kayit_id = $_GET['sil_kayit'];
    $db->prepare("DELETE FROM ders_kayitlari WHERE id = ?")->execute([$kayit_id]);
    header("Location: admin-ders-katilimcilar.php?id=" . $ders_id); // Sayfayı yenile
    exit;
}

// Ders Bilgilerini Çek (Başlık için)
$stmtDers = $db->prepare("SELECT * FROM dersler WHERE id = ?");
$stmtDers->execute([$ders_id]);
$ders = $stmtDers->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container" style="margin-top: 50px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="color: var(--accent);"><?php echo htmlspecialchars($ders['ders_adi']); ?> - Katılımcı Listesi</h2>
            <p style="color: var(--text-muted);">
                Eğitmen: <?php echo htmlspecialchars($ders['egitmen']); ?> | 
                Tarih: <?php echo date("d.m.Y H:i", strtotime($ders['tarih_saat'])); ?>
            </p>
        </div>
        <a href="admin-dersler.php" class="btn" style="background: #333;">&larr; Geri Dön</a>
    </div>

    <div style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
        <table style="width: 100%; color: var(--text-muted); text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #444; color: #fff;">
                    <th style="padding: 10px;">Resim</th>
                    <th>Üye Adı</th>
                    <th>Kayıt Zamanı</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Bu derse kayıtlı üyeleri çek (ders_kayitlari + uyeler Tablosunu Birleştir)
                $sql = "SELECT dk.id as kayit_id, dk.kayit_tarihi, u.kullanici_adi, u.profil_resmi 
                        FROM ders_kayitlari dk 
                        JOIN uyeler u ON dk.uye_id = u.id 
                        WHERE dk.ders_id = ? 
                        ORDER BY dk.kayit_tarihi ASC";
                
                $stmt = $db->prepare($sql);
                $stmt->execute([$ders_id]);
                $katilimcilar = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($katilimcilar) > 0) {
                    foreach ($katilimcilar as $kisi) {
                        // Resim kontrolü
                        $resim = !empty($kisi['profil_resmi']) ? "uploads/".$kisi['profil_resmi'] : "uploads/default.png";

                        echo "<tr style='border-bottom: 1px solid #222;'>";
                        
                        // Resim Sütunu
                        echo "<td style='padding: 10px;'>
                                <img src='$resim' style='width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #fff;'>
                              </td>";
                        
                        // İsim Sütunu
                        echo "<td style='color: #fff; font-weight: bold;'>" . htmlspecialchars($kisi['kullanici_adi']) . "</td>";
                        
                        // Tarih Sütunu
                        echo "<td>" . date("d.m.Y H:i", strtotime($kisi['kayit_tarihi'])) . "</td>";
                        
                        // Sil Butonu
                        echo "<td>
                                <a href='?id=$ders_id&sil_kayit=" . $kisi['kayit_id'] . "' 
                                   class='btn' 
                                   style='background: var(--danger); padding: 5px 10px; font-size: 0.8rem;'
                                   onclick='return confirm(\"Bu üyeyi dersten çıkarmak istediğine emin misin?\")'>
                                   ÇIKAR ❌
                                </a>
                              </td>";
                        
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center; padding: 20px;'>Henüz bu derse kimse kayıt olmamış.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>