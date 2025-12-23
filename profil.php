<?php
session_start();
// Veritabanı bağlantısı
include 'includes/db.php'; 

// Güvenlik: Giriş yapmayan giremez
if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$mesaj = "";

// --- 1. RESİM YÜKLEME İŞLEMİ ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profil_resmi'])) {
    $hedef_klasor = __DIR__ . "/uploads/"; // Tam yol
    $dosya_adi = basename($_FILES["profil_resmi"]["name"]);
    $dosya_uzantisi = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));
    
    // Dosya adını benzersiz yap: profil_5.jpg gibi
    $yeni_dosya_adi = "profil_" . $user_id . "." . $dosya_uzantisi;
    $hedef_dosya = $hedef_klasor . $yeni_dosya_adi;
    
    $izin_verilenler = array("jpg", "jpeg", "png", "gif");
    
    if(in_array($dosya_uzantisi, $izin_verilenler)) {
        if (move_uploaded_file($_FILES["profil_resmi"]["tmp_name"], $hedef_dosya)) {
            // DB güncelle
            $sqlResim = "UPDATE uyeler SET profil_resmi = ? WHERE id = ?";
            $stmtResim = $db->prepare($sqlResim);
            $stmtResim->execute([$yeni_dosya_adi, $user_id]);
            $mesaj = "<div class='success-message'>Profil fotoğrafı güncellendi!</div>";
        } else {
            $mesaj = "<div class='error-message'>Dosya yüklenemedi. 'uploads' klasörü var mı?</div>";
        }
    } else {
        $mesaj = "<div class='error-message'>Sadece resim dosyası (jpg, png) yükleyebilirsiniz.</div>";
    }
}

// --- 2. BOY/KİLO GÜNCELLEME İŞLEMİ ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['boy'])) {
    $yeni_boy = $_POST['boy'];
    $yeni_kilo = $_POST['kilo'];

    if(!empty($yeni_boy) && !empty($yeni_kilo)) {
        $sqlGuncelle = "UPDATE uyeler SET boy = ?, kilo = ? WHERE id = ?";
        $stmtGuncelle = $db->prepare($sqlGuncelle);
        $stmtGuncelle->execute([$yeni_boy, $yeni_kilo, $user_id]);
        $mesaj = "<div class='success-message'>Ölçüleriniz güncellendi!</div>";
    }
}

// --- 3. KULLANICI BİLGİLERİNİ ÇEK ---
$sqlUser = "SELECT * FROM uyeler WHERE id = ?";
$stmtUser = $db->prepare($sqlUser);
$stmtUser->execute([$user_id]);
$uyeBilgi = $stmtUser->fetch(PDO::FETCH_ASSOC);

// --- 4. HESAPLAMALAR ---

// Kalan Gün
$kalan_gun_yazisi = "Bilinmiyor";
$kalan_gun_renk = "#666";
if ($uyeBilgi['uyelik_bitis']) {
    $bitisTarihi = new DateTime($uyeBilgi['uyelik_bitis']);
    $bugun = new DateTime();
    $fark = $bugun->diff($bitisTarihi);
    
    if ($bitisTarihi < $bugun) {
        $kalan_gun_yazisi = "Süresi Doldu";
        $kalan_gun_renk = "var(--danger)";
    } else {
        $kalan_gun_yazisi = $fark->days . " Gün";
        $kalan_gun_renk = ($fark->days < 5) ? "#f1c40f" : "#2ecc71";
    }
}

// Son Giriş
$son_giris_yazisi = "Henüz yok";
$sqlGiris = "SELECT tarih_saat FROM giris_hareketleri WHERE uye_id = ? ORDER BY id DESC LIMIT 1";
$stmtGiris = $db->prepare($sqlGiris);
$stmtGiris->execute([$user_id]);
$sonGiris = $stmtGiris->fetch(PDO::FETCH_ASSOC);
if ($sonGiris) {
    $son_giris_yazisi = date("d.m.Y - H:i", strtotime($sonGiris['tarih_saat']));
}

// Vücut Kitle Endeksi (VKE)
$vke = 0; 
$vke_durum = "Veri Yok"; 
$vke_renk = "#555";

if ($uyeBilgi['boy'] > 0 && $uyeBilgi['kilo'] > 0) {
    $boy_metre = $uyeBilgi['boy'] / 100;
    $vke = $uyeBilgi['kilo'] / ($boy_metre * $boy_metre);
    $vke = number_format($vke, 1);

    if ($vke < 18.5) {
        $vke_durum = "Zayıf"; $vke_renk = "#f1c40f";
    } elseif ($vke < 25) {
        $vke_durum = "Normal"; $vke_renk = "#2ecc71";
    } elseif ($vke < 30) {
        $vke_durum = "Kilolu"; $vke_renk = "#e67e22";
    } else {
        $vke_durum = "Obez"; $vke_renk = "#e74c3c";
    }
}

// Son Duyuru
$sonDuyuru = "Henüz duyuru yok.";
$stmtDuyuru = $db->query("SELECT mesaj FROM duyurular ORDER BY id DESC LIMIT 1");
$duyuruVeri = $stmtDuyuru->fetch(PDO::FETCH_ASSOC);
if ($duyuruVeri) { $sonDuyuru = $duyuruVeri['mesaj']; }

include 'includes/header.php';
?>

<div class="container" style="margin-top: 30px;">
    
    <div style="background: rgba(230, 126, 34, 0.2); border: 1px solid #e67e22; color: #e67e22; padding: 15px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 1.5rem;">📢</span>
        <strong>DUYURU:</strong> <?php echo htmlspecialchars($sonDuyuru); ?>
    </div>

    <?php echo $mesaj; ?>

    <div style="background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        
        <div style="text-align: center; flex: 1;">
            <div style="width: 120px; height: 120px; overflow: hidden; border-radius: 50%; border: 3px solid var(--accent); margin: 0 auto; background: #000;">
                <?php 
                    $resimYolu = !empty($uyeBilgi['profil_resmi']) ? "uploads/".$uyeBilgi['profil_resmi'] : "uploads/default.png";
                ?>
                <img src="<?php echo $resimYolu; ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <form action="" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                <label for="file-upload" class="btn" style="font-size: 0.8rem; padding: 5px 10px; cursor: pointer; background: #333;">📷 Değiştir</label>
                <input id="file-upload" type="file" name="profil_resmi" style="display: none;" onchange="this.form.submit()">
            </form>
        </div>

        <div style="flex: 2; text-align: left; min-width: 250px;">
            <h2 style="color: var(--text-main);">Merhaba, <span style="color: var(--accent);"><?php echo htmlspecialchars($_SESSION['kullanici_adi']); ?></span></h2>
            <p style="color: var(--text-muted);">Bugün antrenman yapacak mısın?</p>
            <br>
            <div style="display: flex; gap: 10px;">
                <a href="ayarlar.php" class="btn" style="background: #555; font-size: 14px;">⚙️ Ayarlar</a>
                <a href="cikis.php" class="btn" style="background: #333; font-size: 14px;">Çıkış Yap</a>
            </div>
        </div>

        <div style="text-align: center; flex: 1;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=UyeID-<?php echo $_SESSION['user_id']; ?>" 
                 alt="QR Kod" style="border-radius: 10px; padding: 5px; background: #fff;">
            <p style="font-size: 0.8rem; color: #666; margin-top: 5px;">Giriş Kodun</p>
            <br>
            <a href="kartim.php" style="display: inline-block; margin-top: 10px; color: var(--accent); font-size: 0.8rem; text-decoration: none; border: 1px solid var(--accent); padding: 5px 10px; border-radius: 5px;">
                🆔 DİJİTAL KART
            </a>
        </div>
    </div>

    <div class="paketler" style="margin-top: 10px; display: flex; gap: 20px; flex-wrap: wrap;">
        <div class="paket" style="padding: 20px; flex: 1;">
            <h3>Kalan Süre</h3>
            <p class="fiyat" style="color: <?php echo $kalan_gun_renk; ?>;"><?php echo $kalan_gun_yazisi; ?></p>
            <small style="color:#666;"><?php echo $uyeBilgi['uyelik_bitis'] ? date("d.m.Y", strtotime($uyeBilgi['uyelik_bitis'])) : ""; ?></small>
        </div>
        <div class="paket" style="padding: 20px; flex: 1;">
            <h3>Son Giriş</h3>
            <p class="fiyat" style="font-size: 1.2rem; color: #fff;"><?php echo $son_giris_yazisi; ?></p>
        </div>
        <div class="paket" style="padding: 20px; flex: 1;">
            <h3>Antrenör</h3>
            <p class="fiyat" style="font-size: 1.2rem; color: var(--text-muted);"><?php echo htmlspecialchars($uyeBilgi['antrenor']); ?></p>
        </div>
    </div>

    <div style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
        
        <div style="flex: 1; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; text-align: center; min-width: 300px;">
            <h3 style="color: #fff; margin-bottom: 20px;">Vücut Kitle Endeksi</h3>
            <div style="width: 120px; height: 120px; border-radius: 50%; border: 8px solid <?php echo $vke_renk; ?>; margin: 0 auto; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <span style="font-size: 2rem; font-weight: bold; color: #fff;"><?php echo $vke; ?></span>
            </div>
            <h2 style="margin-top: 15px; color: <?php echo $vke_renk; ?>;"><?php echo $vke_durum; ?></h2>
            
            <p style="color: #bbb; font-size: 0.9rem; margin-top: 15px; border-top: 1px solid #444; padding-top: 10px;">
                Boy: <span style="color:#fff;"><?php echo $uyeBilgi['boy'] ? $uyeBilgi['boy'].' cm' : '-'; ?></span> | 
                Kilo: <span style="color:#fff;"><?php echo $uyeBilgi['kilo'] ? $uyeBilgi['kilo'].' kg' : '-'; ?></span>
            </p>
        </div>

        <div style="flex: 1; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: var(--accent); margin-bottom: 20px;">Ölçülerini Güncelle</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Boy (cm):</label>
                    <input type="number" name="boy" value="<?php echo $uyeBilgi['boy']; ?>" placeholder="Örn: 180" required>
                </div>
                <div class="form-group">
                    <label>Kilo (kg):</label>
                    <input type="number" name="kilo" step="0.1" value="<?php echo $uyeBilgi['kilo']; ?>" placeholder="Örn: 80" required>
                </div>
                <button type="submit" class="form-btn" style="width: auto;">Hesapla ve Kaydet</button>
            </form>
        </div>
    </div>

    <div style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
        <div style="flex: 1; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: var(--accent); border-bottom: 1px solid #444; padding-bottom: 10px;">🏋️ Antrenman</h3>
            <div style="color: var(--text-muted); margin-top: 15px;">
                <?php echo !empty($uyeBilgi['antrenman_programi']) ? nl2br(htmlspecialchars($uyeBilgi['antrenman_programi'])) : "Henüz program atanmadı."; ?>
            </div>
        </div>
        <div style="flex: 1; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: var(--accent); border-bottom: 1px solid #444; padding-bottom: 10px;">🍎 Beslenme</h3>
            <div style="color: var(--text-muted); margin-top: 15px;">
                <?php echo !empty($uyeBilgi['diyet_listesi']) ? nl2br(htmlspecialchars($uyeBilgi['diyet_listesi'])) : "Henüz liste atanmadı."; ?>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
        <h3 style="color: var(--accent); margin-bottom: 20px;">📩 Destek Mesajlarım</h3>
        <?php
        $uye_email = $uyeBilgi['email'];
        $sqlMesajlarim = "SELECT * FROM mesajlar WHERE email = ? ORDER BY tarih DESC";
        $stmtM = $db->prepare($sqlMesajlarim);
        $stmtM->execute([$uye_email]);
        $mesajlarim = $stmtM->fetchAll(PDO::FETCH_ASSOC);

        if (count($mesajlarim) > 0) {
            foreach ($mesajlarim as $msg) {
                echo '<div style="border-bottom: 1px solid #444; padding-bottom: 15px; margin-bottom: 15px;">';
                
                echo '<div style="display: flex; justify-content: space-between;">';
                echo '   <strong style="color: #fff;">' . htmlspecialchars($msg['konu']) . '</strong>';
                echo '   <small style="color: #888;">' . date("d.m.Y", strtotime($msg['tarih'])) . '</small>';
                echo '</div>';
                
                echo '<p style="color: var(--text-muted); margin-top: 5px;">' . htmlspecialchars($msg['mesaj']) . '</p>';

                if (!empty($msg['cevap'])) {
                    echo '<div style="background: rgba(52, 152, 219, 0.1); border-left: 3px solid #3498db; padding: 10px; margin-top: 10px; border-radius: 0 5px 5px 0;">';
                    echo '   <strong style="color: #3498db; font-size: 0.9rem;">Yönetici Cevabı:</strong>';
                    echo '   <p style="color: #fff; margin-top: 5px;">' . nl2br(htmlspecialchars($msg['cevap'])) . '</p>';
                    echo '   <small style="color: #888;">' . date("d.m.Y H:i", strtotime($msg['cevap_tarihi'])) . '</small>';
                    echo '</div>';
                } else {
                    echo '<small style="color: #e67e22; font-style: italic;">Henüz cevaplanmadı...</small>';
                }

                echo '</div>';
            }
        } else {
            echo "<p style='color: #666; text-align: center;'>Henüz bir destek talebiniz yok.</p>";
        }
        ?>
    </div>

    <div style="margin-top: 30px; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333;">
        <h3 style="color: var(--accent); margin-bottom: 20px;">💳 Ödeme Geçmişim</h3>
        <table style="width: 100%; text-align: left; color: var(--text-muted); border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #444; color: #fff;">
                    <th style="padding:10px;">Tarih</th>
                    <th>Açıklama</th>
                    <th style="text-align:right;">Tutar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlOdeme = "SELECT * FROM odemeler WHERE uye_id = ? ORDER BY id DESC";
                $stmtOdeme = $db->prepare($sqlOdeme);
                $stmtOdeme->execute([$user_id]);
                $odemeler = $stmtOdeme->fetchAll(PDO::FETCH_ASSOC);

                if (count($odemeler) > 0) {
                    foreach ($odemeler as $odeme) {
                        echo "<tr style='border-bottom: 1px solid #222;'>";
                        echo "<td style='padding:10px;'>" . date("d.m.Y", strtotime($odeme['tarih'])) . "</td>";
                        echo "<td>" . htmlspecialchars($odeme['aciklama']) . "</td>";
                        echo "<td style='text-align:right; color:#fff;'>" . number_format($odeme['tutar'], 2) . " ₺</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='padding:20px; text-align:center;'>Ödeme kaydı yok.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>