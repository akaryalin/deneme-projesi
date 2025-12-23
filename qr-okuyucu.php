<?php
// Sadece giriş yapmış kişiler (Personel/Admin) görebilmeli
session_start();
include 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit;
}
include 'includes/header.php';
?>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<div class="container">
    <div class="form-container" style="max-width: 600px; text-align: center;">
        <h2>QR Kod Okuyucu</h2>
        <p style="margin-bottom: 20px; color: #a0a0a0;">Üye girişini onaylamak için QR kodu kameraya gösterin.</p>

        <div id="reader" style="width: 100%; border-radius: 10px; overflow: hidden;"></div>

        <div id="sonuc-ekrani" style="margin-top: 20px; display: none; padding: 20px; border-radius: 10px;">
            <h3 id="sonuc-baslik" style="font-size: 1.5rem; margin-bottom: 10px;"></h3>
            <p id="sonuc-detay" style="font-size: 1.2rem; color: #fff;"></p>
        </div>
    </div>
</div>

<script>
    // Tarama durumunu kontrol edelim (Arka arkaya 50 kere sorgu atmasın)
    let taramaYapiliyor = false;

    function onScanSuccess(decodedText, decodedResult) {
        
        if (taramaYapiliyor) return; // Zaten işlem yapıyorsak bekle
        taramaYapiliyor = true;

        // Okuma ses efektini çal (Bip sesi)
        let audio = new Audio('https://www.soundjay.com/buttons/beep-01a.mp3');
        audio.play().catch(e => {}); // Hata olursa görmezden gel

        // Kamerayı geçici olarak durdur (İsteğe bağlı)
        // html5QrcodeScanner.pause(); 

        // 1. Veriyi Arka Plana (PHP'ye) Gönder
        fetch('api-qr-kontrol.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ qr_code: decodedText }),
        })
        .then(response => response.json())
        .then(data => {
            
            // 2. PHP'den Gelen Cevaba Göre Ekranı Güncelle
            let sonucKutusu = document.getElementById('sonuc-ekrani');
            let baslik = document.getElementById('sonuc-baslik');
            let detay = document.getElementById('sonuc-detay');

            sonucKutusu.style.display = "block";

            if (data.durum === 'basarili') {
                // YEŞİL EKRAN (Giriş Başarılı)
                sonucKutusu.style.backgroundColor = "rgba(46, 204, 113, 0.2)"; // Yeşil transparan
                sonucKutusu.style.border = "2px solid #2ecc71";
                baslik.style.color = "#2ecc71";
                baslik.innerText = data.mesaj;
                detay.innerText = "Hoş geldin, " + data.isim;
            } else {
                // KIRMIZI EKRAN (Hata)
                sonucKutusu.style.backgroundColor = "rgba(231, 76, 60, 0.2)"; // Kırmızı transparan
                sonucKutusu.style.border = "2px solid #e74c3c";
                baslik.style.color = "#e74c3c";
                baslik.innerText = "GİRİŞ BAŞARISIZ";
                detay.innerText = data.mesaj;
            }

            // 3 saniye sonra yeni taramaya izin ver
            setTimeout(() => {
                taramaYapiliyor = false;
                sonucKutusu.style.display = "none";
                // html5QrcodeScanner.resume(); // Kamerayı durdurduysan başlat
            }, 3000);

        })
        .catch((error) => {
            console.error('Hata:', error);
            taramaYapiliyor = false;
        });
    }

    function onScanFailure(error) {
        // Okuyamazsa burası çalışır (Boş bırakıyoruz)
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { fps: 10, qrbox: {width: 250, height: 250} },
        false);
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
<div class="container" style="margin-top: 50px;">
    <h3 style="color: var(--text-main); border-bottom: 2px solid var(--accent); display:inline-block; margin-bottom: 20px;">Bugün Giriş Yapanlar</h3>
    
    <div style="background: var(--bg-card); padding: 20px; border-radius: 10px; border: 1px solid #333;">
        <table style="width: 100%; text-align: left; border-collapse: collapse; color: var(--text-muted);">
            <thead>
                <tr style="border-bottom: 1px solid #444;">
                    <th style="padding: 10px;">Üye Adı</th>
                    <th style="padding: 10px;">Giriş Saati</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Bugün giriş yapanları veritabanından çek (En son giren en üstte)
                // Bu sorgu iki tabloyu BİRLEŞTİRİR (JOIN): Üye adını 'uyeler'den, saati 'giris_hareketleri'nden alır.
                $sqlGiris = "
                    SELECT u.kullanici_adi, g.tarih_saat 
                    FROM giris_hareketleri g 
                    JOIN uyeler u ON g.uye_id = u.id 
                    WHERE DATE(g.tarih_saat) = CURDATE() 
                    ORDER BY g.tarih_saat DESC";
                
                $stmtGiris = $db->prepare($sqlGiris);
                $stmtGiris->execute();
                $girisler = $stmtGiris->fetchAll(PDO::FETCH_ASSOC);

                if (count($girisler) > 0) {
                    foreach ($girisler as $giris) {
                        // Saati güzelleştir (Sadece saat ve dakika)
                        $saat = date("H:i", strtotime($giris['tarih_saat']));
                        echo "<tr style='border-bottom: 1px solid #222;'>";
                        echo "<td style='padding: 10px; color: #fff;'>" . htmlspecialchars($giris['kullanici_adi']) . "</td>";
                        echo "<td style='padding: 10px; color: var(--accent);'>" . $saat . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2' style='padding:10px; text-align:center;'>Bugün henüz kimse giriş yapmadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <p style="text-align:center; margin-top:15px; font-size: 0.8rem; color: #555;">
            * Listeyi güncellemek için sayfayı yenileyin.
        </p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>