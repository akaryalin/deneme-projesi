<?php 
include 'includes/db.php'; // Veritabanını ekledik
include 'includes/header.php'; 
?>

<section class="hero">
    <div class="hero-text">
        <h1>Sınırlarını Zorla, Gücünü Keşfet!</h1>
        <p>En yeni ekipmanlar ve uzman antrenörler eşliğinde hayalindeki vücuda kavuş.</p>
        <a href="uyelikler.php" class="btn">Paketleri İncele</a>
    </div>
</section>
<div class="container" style="margin-top: 60px; margin-bottom: 60px;">
    <h2 style="text-align: center; color: var(--accent); margin-bottom: 10px;">ÜCRETSİZ FITNESS ARAÇLARI</h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 40px;">Hedefine ulaşmak için ihtiyacın olan tüm hesaplamalar burada.</p>

    <div class="araclar-grid">
        <a href="vke-hesapla.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-weight-scale"></i></div>
            <h3>Vücut Kitle Endeksi</h3>
        </a>

        <a href="kalori-hesapla.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-utensils"></i></div>
            <h3>Günlük Kalori</h3>
        </a>

        <a href="su-ihtiyaci.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-droplet"></i></div>
            <h3>Su İhtiyacı</h3>
        </a>

        <a href="protein-hesapla.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-drumstick-bite"></i></div>
            <h3>Günlük Protein</h3>
        </a>

        <a href="ideal-kilo.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-bullseye"></i></div>
            <h3>İdeal Kilo</h3>
        </a>

        <a href="makro-hesapla.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-chart-pie"></i></div>
            <h3>Makro Hesabı</h3>
        </a>

        <a href="1rm-hesapla.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-dumbbell"></i></div>
            <h3>1RM (Güç) Hesapla</h3>
        </a>

        <a href="nabiz-hesapla.php" class="arac-kutusu">
            <div class="ikon-daire"><i class="fa-solid fa-heart-pulse"></i></div>
            <h3>Nabız Bölgesi</h3>
        </a>
    </div>
</div>
<div class="container" style="margin-top: 60px; text-align: center;">
    <h2 style="color: #fff; font-size: 2.5rem; margin-bottom: 10px;">🏆 Ayın Şampiyonları</h2>
    <p style="color: var(--text-muted);">Bu ay antrenmana en çok gelen azimli üyelerimiz.</p>

    <div class="lider-kutusu">
        <?php
        // SQL SORGUSU: Bu ay en çok giriş yapan ilk 3 kişiyi bul
        $sqlLider = "SELECT u.kullanici_adi, u.profil_resmi, COUNT(g.id) as antrenman_sayisi
                     FROM giris_hareketleri g
                     JOIN uyeler u ON g.uye_id = u.id
                     WHERE MONTH(g.tarih_saat) = MONTH(CURDATE()) 
                     AND YEAR(g.tarih_saat) = YEAR(CURDATE())
                     GROUP BY g.uye_id
                     ORDER BY antrenman_sayisi DESC
                     LIMIT 3";
        
        $stmtLider = $db->prepare($sqlLider);
        $stmtLider->execute();
        $liderler = $stmtLider->fetchAll(PDO::FETCH_ASSOC);

        if (count($liderler) > 0) {
            $sira = 1;
            foreach ($liderler as $lider) {
                // Sıralamaya göre sınıf (class) belirle
                $class = "";
                $tac = "";
                if($sira == 1) { $class = "birinci"; $tac = "👑"; }
                elseif($sira == 2) { $class = "ikinci"; }
                elseif($sira == 3) { $class = "ucuncu"; }

                echo '<div class="lider-kart ' . $class . '">';
                echo '    <div style="font-size: 2rem; margin-bottom: -20px; position:relative; z-index:10;">'.$tac.'</div>';
                
                // Resim yoksa default.png kullan (Hata almamak için)
                $resim = !empty($lider['profil_resmi']) ? "uploads/".$lider['profil_resmi'] : "uploads/default.png";
                
                echo '    <img src="' . $resim . '" class="lider-img" alt="Üye">';
                echo '    <h3>' . htmlspecialchars($lider['kullanici_adi']) . '</h3>';
                echo '    <p style="color: var(--accent); font-weight: bold; font-size: 1.2rem;">' . $lider['antrenman_sayisi'] . ' Antrenman</p>';
                echo '</div>';
                $sira++;
            }
        } else {
            echo "<p style='color: #666; margin-top: 20px;'>Bu ay henüz yeterli veri yok. İlk şampiyon sen olabilirsin!</p>";
        }
        ?>
    </div>
</div>
<div class="container" style="margin-top: 80px;">
    <div style="text-align: center;">
        <h2 style="color: var(--accent); margin-bottom: 10px;">Neden Biz?</h2>
    </div>

    <div class="paketler"> 
        <div class="paket">
            <h3>Modern Ekipmanlar</h3>
            <p>Dünya standartlarında, bakımlı ve son teknoloji spor aletleri.</p>
        </div>
        <div class="paket">
            <h3>Uzman Kadro</h3>
            <p>Sertifikalı ve tecrübeli eğitmenlerimizle güvenli antrenman.</p>
        </div>
        <div class="paket">
            <h3>Hijyenik Ortam</h3>
            <p>Sizin sağlığınız için sürekli temizlenen, ferah çalışma alanları.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>