<?php
session_start();
include 'includes/db.php';

// Güvenlik
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

// 1. CEVAP YAZMA İŞLEMİ (YENİ)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mesaj_id'])) {
    $id = $_POST['mesaj_id'];
    $cevap = $_POST['admin_cevap'];
    
    $sqlCevap = "UPDATE mesajlar SET cevap = ?, cevap_tarihi = NOW(), okundu = 1 WHERE id = ?";
    $stmtCevap = $db->prepare($sqlCevap);
    $stmtCevap->execute([$cevap, $id]);
    
    header("Location: admin-mesajlar.php?durum=cevaplandi");
    exit;
}

// SİLME İŞLEMİ
if (isset($_GET['sil'])) {
    $db->prepare("DELETE FROM mesajlar WHERE id = ?")->execute([$_GET['sil']]);
    header("Location: admin-mesajlar.php");
    exit;
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 50px;">
    <h2 style="color: var(--accent); margin-bottom: 20px;">📩 Gelen Kutusu ve Yanıtlar</h2>

    <?php if(isset($_GET['durum'])) echo "<div class='success-message'>Cevabınız üyeye iletildi!</div>"; ?>

    <div style="display: flex; flex-direction: column; gap: 20px;">
        <?php
        $sql = "SELECT * FROM mesajlar ORDER BY okundu ASC, tarih DESC";
        $stmt = $db->query($sql);
        
        if ($stmt->rowCount() == 0) {
            echo "<p style='color: #666; text-align: center;'>Henüz hiç mesaj yok.</p>";
        }

        while ($mesaj = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stil = ($mesaj['okundu'] == 0) ? "border-left: 5px solid var(--accent); background: #222;" : "border-left: 5px solid #444; background: var(--bg-card); opacity: 0.7;";
            $etiket = ($mesaj['okundu'] == 0) ? "<span style='background: var(--accent); color: #000; padding: 2px 8px; border-radius: 5px; font-size: 0.7rem; font-weight: bold;'>YENİ</span>" : "";

            echo '<div style="padding: 20px; border-radius: 10px; border: 1px solid #333; '.$stil.'">';
            
            // Üye Bilgisi
            echo '<div style="margin-bottom: 10px;">';
            echo '   <h3 style="color: #fff; margin-bottom: 5px;">' . htmlspecialchars($mesaj['konu']) . ' ' . $etiket . '</h3>';
            echo '   <p style="color: var(--text-muted); font-size: 0.9rem;"><strong>' . htmlspecialchars($mesaj['ad_soyad']) . '</strong> - ' . date("d.m.Y H:i", strtotime($mesaj['tarih'])) . '</p>';
            echo '</div>';

            // Üyenin Mesajı
            echo '<div style="background: #111; padding: 15px; border-radius: 5px; color: #ddd; margin-bottom: 15px;">';
            echo nl2br(htmlspecialchars($mesaj['mesaj']));
            echo '</div>';

            // --- CEVAP ALANI ---
            if (!empty($mesaj['cevap'])) {
                // Zaten cevap verilmişse göster
                echo '<div style="background: rgba(46, 204, 113, 0.1); border: 1px solid #2ecc71; padding: 15px; border-radius: 5px; margin-bottom: 10px;">';
                echo '   <strong style="color: #2ecc71;">✅ Sizin Cevabınız:</strong><br>';
                echo '   <span style="color: #fff;">' . nl2br(htmlspecialchars($mesaj['cevap'])) . '</span>';
                echo '   <br><small style="color: #888;">' . date("d.m.Y H:i", strtotime($mesaj['cevap_tarihi'])) . '</small>';
                echo '</div>';
            } else {
                // Cevap verilmemişse FORM göster
                echo '<form method="POST" style="margin-top: 10px;">';
                echo '   <input type="hidden" name="mesaj_id" value="' . $mesaj['id'] . '">';
                echo '   <textarea name="admin_cevap" rows="2" placeholder="Cevabınızı buraya yazın..." style="width: 100%; padding: 10px; background: #000; color: #fff; border: 1px solid #444; border-radius: 5px;" required></textarea>';
                echo '   <div style="margin-top: 5px; text-align: right;">';
                echo '       <button type="submit" class="btn" style="padding: 5px 15px; font-size: 0.8rem; background: #2ecc71;">YANITLA & GÖNDER</button>';
                echo '       <a href="?sil=' . $mesaj['id'] . '" class="btn" style="padding: 5px 15px; font-size: 0.8rem; background: var(--danger);" onclick="return confirm(\'Silmek istediğine emin misin?\')">SİL</a>';
                echo '   </div>';
                echo '</form>';
            }
            
            echo '</div>'; // Mesaj kutusu sonu
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>