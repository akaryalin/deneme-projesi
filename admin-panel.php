<?php
session_start();
include 'includes/db.php';

// Güvenlik: Sadece ADMIN girebilir!
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

// 1. DUYURU YAYINLA
$mesaj_durumu = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['yeni_duyuru'])) {
    $duyuru = trim($_POST['yeni_duyuru']);
    if (!empty($duyuru)) {
        $sqlDuyuru = "INSERT INTO duyurular (mesaj) VALUES (?)";
        $stmtDuyuru = $db->prepare($sqlDuyuru);
        $stmtDuyuru->execute([$duyuru]);
        $mesaj_durumu = "<div class='success-message'>Duyuru başarıyla yayınlandı!</div>";
    }
}

// 2. GRAFİK VERİLERİNİ HAZIRLA (PHP)

// A) Son 7 Günün Giriş İstatistikleri (Çubuk Grafik)
// SQL: Son 7 günü grupla ve say
$gunler = [];
$giris_sayilari = [];
$sqlGrafik1 = "SELECT DATE_FORMAT(tarih_saat, '%d.%m') as gun, COUNT(*) as toplam 
               FROM giris_hareketleri 
               WHERE tarih_saat > DATE_SUB(NOW(), INTERVAL 7 DAY) 
               GROUP BY DATE(tarih_saat) 
               ORDER BY tarih_saat ASC";
$stmtGrafik1 = $db->query($sqlGrafik1);
while($row = $stmtGrafik1->fetch(PDO::FETCH_ASSOC)) {
    $gunler[] = $row['gun'];
    $giris_sayilari[] = $row['toplam'];
}

// B) En Çok Satılan Paketler (Pasta Grafik)
// SQL: Ödemeler tablosundaki açıklamaları (paket isimlerini) say
$paket_isimleri = [];
$paket_satis = [];
$sqlGrafik2 = "SELECT aciklama, COUNT(*) as adet FROM odemeler GROUP BY aciklama";
$stmtGrafik2 = $db->query($sqlGrafik2);
while($row = $stmtGrafik2->fetch(PDO::FETCH_ASSOC)) {
    $paket_isimleri[] = $row['aciklama']; // Örn: 3 Aylık, 1 Yıllık
    $paket_satis[] = $row['adet'];
}

include 'includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container" style="margin-top: 50px;">
    
    <h2 style="color: var(--accent); margin-bottom: 20px;">Yönetici Kontrol Paneli</h2>

    <div style="background: var(--bg-card); padding: 20px; border-radius: 20px; border: 1px solid var(--accent); margin-bottom: 30px;">
        <h3 style="color: #fff; margin-bottom: 10px; font-size: 1.2rem;">📢 Duyuru Yayınla</h3>
        <?php echo $mesaj_durumu; ?>
        <form method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="yeni_duyuru" placeholder="Tüm üyelere gidecek mesajı yazın..." style="flex: 1; padding: 15px; border-radius: 10px; border: 1px solid #444; background: #000; color: #fff;" required>
            <button type="submit" class="btn">YAYINLA</button>
        </form>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 40px; flex-wrap: wrap;">
        
        <div style="flex: 2; background: var(--bg-card); padding: 20px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: #fff; margin-bottom: 15px;">📊 Son 7 Günlük Antrenman Yoğunluğu</h3>
            <canvas id="girisGrafigi"></canvas> </div>

        <div style="flex: 1; background: var(--bg-card); padding: 20px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: #fff; margin-bottom: 15px;">🍰 Paket Satış Dağılımı</h3>
            <div style="height: 250px; display: flex; justify-content: center;">
                <canvas id="paketGrafigi"></canvas> </div>
        </div>

    </div>

    <h3 style="color: #fff; margin-bottom: 15px;">📋 Kayıtlı Üyeler</h3>
    <div style="background: var(--bg-card); padding: 20px; border-radius: 20px; border: 1px solid #333; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-muted); min-width: 600px;">
            <thead>
                <tr style="border-bottom: 2px solid #444; color: #fff; text-align: left;">
                    <th style="padding: 15px;">ID</th>
                    <th style="padding: 15px;">Kullanıcı Adı</th>
                    <th style="padding: 15px;">E-Posta</th>
                    <th style="padding: 15px;">Üyelik Bitiş</th>
                    <th style="padding: 15px;">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM uyeler ORDER BY id DESC";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $uyeler = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($uyeler as $uye) {
                    echo "<tr style='border-bottom: 1px solid #222;'>";
                    echo "<td style='padding: 15px;'>#" . $uye['id'] . "</td>";
                    echo "<td style='padding: 15px; font-weight: bold; color: #fff;'>" . htmlspecialchars($uye['kullanici_adi']) . "</td>";
                    echo "<td style='padding: 15px;'>" . htmlspecialchars($uye['email']) . "</td>";
                    $tarih = $uye['uyelik_bitis'] ? date("d.m.Y", strtotime($uye['uyelik_bitis'])) : "-";
                    echo "<td style='padding: 15px;'>" . $tarih . "</td>";
                    echo "<td style='padding: 15px;'><a href='admin-duzenle.php?id=" . $uye['id'] . "' class='btn' style='padding: 5px 15px; font-size: 12px;'>YÖNET</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // PHP'den gelen verileri JS'ye aktar (JSON formatında)
    const gunler = <?php echo json_encode($gunler); ?>;
    const girisVerileri = <?php echo json_encode($giris_sayilari); ?>;
    
    const paketIsimleri = <?php echo json_encode($paket_isimleri); ?>;
    const paketVerileri = <?php echo json_encode($paket_satis); ?>;

    // 1. ÇUBUK GRAFİK (Girişler)
    new Chart(document.getElementById('girisGrafigi'), {
        type: 'bar',
        data: {
            labels: gunler,
            datasets: [{
                label: 'Giriş Yapan Üye Sayısı',
                data: girisVerileri,
                backgroundColor: 'rgba(207, 255, 4, 0.6)', // Neon Sarı (Şeffaf)
                borderColor: '#cfff04', // Neon Sarı (Çizgi)
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { color: '#333' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } } // Etiketi gizle
        }
    });

    // 2. PASTA GRAFİK (Paketler)
    new Chart(document.getElementById('paketGrafigi'), {
        type: 'doughnut', // İçi boş pasta
        data: {
            labels: paketIsimleri,
            datasets: [{
                data: paketVerileri,
                backgroundColor: [
                    '#cfff04', // Neon
                    '#3498db', // Mavi
                    '#e74c3c', // Kırmızı
                    '#9b59b6'  // Mor
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#fff' } }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>