<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$mesaj = "";

// KATILMA İŞLEMİ
if (isset($_GET['katil'])) {
    $ders_id = $_GET['katil'];
    
    // 1. Zaten kayıtlı mı?
    $kontrol = $db->prepare("SELECT id FROM ders_kayitlari WHERE ders_id = ? AND uye_id = ?");
    $kontrol->execute([$ders_id, $user_id]);
    
    // 2. Kontenjan dolu mu?
    $doluluk = $db->prepare("SELECT kontenjan, (SELECT COUNT(*) FROM ders_kayitlari WHERE ders_id = ?) as sayi FROM dersler WHERE id = ?");
    $doluluk->execute([$ders_id, $ders_id]);
    $durum = $doluluk->fetch(PDO::FETCH_ASSOC);

    if ($kontrol->rowCount() > 0) {
        $mesaj = "<div class='error-message'>Zaten bu derse kayıtlısın!</div>";
    } elseif ($durum['sayi'] >= $durum['kontenjan']) {
        $mesaj = "<div class='error-message'>Malesef kontenjan dolmuş!</div>";
    } else {
        // Kaydı Yap
        $kayit = $db->prepare("INSERT INTO ders_kayitlari (ders_id, uye_id) VALUES (?, ?)");
        $kayit->execute([$ders_id, $user_id]);
        $mesaj = "<div class='success-message'>Kaydın alındı! Derste görüşürüz.</div>";
    }
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 50px;">
    <h2 style="color: #fff; text-align: center;">Haftalık Grup Dersleri</h2>
    <p style="color: var(--text-muted); text-align: center; margin-bottom: 30px;">Kontenjanlar sınırlıdır, yerini hemen ayırt!</p>
    
    <?php echo $mesaj; ?>

    <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
        <?php
        // Gelecekteki dersleri çek
        $sql = "SELECT d.*, 
                (SELECT COUNT(*) FROM ders_kayitlari WHERE ders_id = d.id) as kayitli_sayisi,
                (SELECT COUNT(*) FROM ders_kayitlari WHERE ders_id = d.id AND uye_id = $user_id) as ben_var_miyim
                FROM dersler d 
                WHERE tarih_saat > NOW() 
                ORDER BY tarih_saat ASC";
        
        $stmt = $db->query($sql);

        if ($stmt->rowCount() == 0) {
            echo "<p style='color: #666;'>Şu an planlanmış aktif bir ders yok.</p>";
        }

        while ($ders = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $doluluk_orani = ($ders['kayitli_sayisi'] / $ders['kontenjan']) * 100;
            $renk = ($doluluk_orani >= 100) ? "var(--danger)" : "var(--accent)";
            
            echo '<div style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; width: 300px; text-align: center; position: relative;">';
            
            echo '<h3 style="color: #fff;">' . htmlspecialchars($ders['ders_adi']) . '</h3>';
            echo '<p style="color: var(--text-muted); margin-bottom: 10px;">Eğitmen: ' . htmlspecialchars($ders['egitmen']) . '</p>';
            
            // Tarih Kutusu
            echo '<div style="background: #000; color: #fff; padding: 10px; border-radius: 10px; margin: 15px 0; font-weight: bold;">';
            echo date("d.m.Y - H:i", strtotime($ders['tarih_saat']));
            echo '</div>';

            // Doluluk Çubuğu
            echo '<div style="background: #333; height: 10px; border-radius: 5px; overflow: hidden; margin-bottom: 10px;">';
            echo '   <div style="background: '.$renk.'; height: 100%; width: '.$doluluk_orani.'%;"></div>';
            echo '</div>';
            echo '<p style="font-size: 0.8rem; color: #aaa;">Doluluk: ' . $ders['kayitli_sayisi'] . ' / ' . $ders['kontenjan'] . '</p>';

            // Buton Mantığı
            echo '<div style="margin-top: 20px;">';
            if ($ders['ben_var_miyim'] > 0) {
                echo '<button class="btn" style="background: #27ae60; cursor: default;">✅ KATILDIN</button>';
            } elseif ($ders['kayitli_sayisi'] >= $ders['kontenjan']) {
                echo '<button class="btn" style="background: #333; color: #666; cursor: not-allowed;">DOLDU</button>';
            } else {
                echo '<a href="?katil=' . $ders['id'] . '" class="btn">KATIL</a>';
            }
            echo '</div>';

            echo '</div>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>