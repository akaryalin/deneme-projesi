<?php
session_start();
include 'includes/db.php';

// Sadece Admin Girebilir
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

$mesaj = "";

// 1. DERS EKLEME
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ders_ekle'])) {
    $ders_adi = $_POST['ders_adi'];
    $egitmen = $_POST['egitmen'];
    $tarih_saat = $_POST['tarih_saat'];
    $kontenjan = $_POST['kontenjan'];

    $sqlEkle = "INSERT INTO dersler (ders_adi, egitmen, tarih_saat, kontenjan) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sqlEkle);
    $stmt->execute([$ders_adi, $egitmen, $tarih_saat, $kontenjan]);
    $mesaj = "<div class='success-message'>Yeni ders açıldı!</div>";
}

// 2. DERS SİLME
if (isset($_GET['sil'])) {
    $sil_id = $_GET['sil'];
    // Önce kayıtları sil, sonra dersi sil (Temizlik)
    $db->prepare("DELETE FROM ders_kayitlari WHERE ders_id = ?")->execute([$sil_id]);
    $db->prepare("DELETE FROM dersler WHERE id = ?")->execute([$sil_id]);
    header("Location: admin-dersler.php"); // Sayfayı yenile
    exit;
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 50px;">
    
    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 1; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid var(--accent); min-width: 300px;">
            <h3 style="color: var(--accent); margin-bottom: 20px;">📅 Yeni Ders Programla</h3>
            <?php echo $mesaj; ?>
            <form method="POST">
                <input type="hidden" name="ders_ekle" value="1">
                <div class="form-group"><label>Ders Adı:</label><input type="text" name="ders_adi" placeholder="Örn: Zumba / Pilates" required></div>
                <div class="form-group"><label>Eğitmen:</label><input type="text" name="egitmen" placeholder="Örn: Ayşe Hoca" required></div>
                <div class="form-group"><label>Tarih ve Saat:</label><input type="datetime-local" name="tarih_saat" required></div>
                <div class="form-group"><label>Kontenjan:</label><input type="number" name="kontenjan" value="15" required></div>
                <button type="submit" class="form-btn">Dersi Aç</button>
            </form>
        </div>

        <div style="flex: 2; background: var(--bg-card); padding: 30px; border-radius: 20px; border: 1px solid #333; min-width: 300px;">
            <h3 style="color: #fff; margin-bottom: 20px;">Aktif Dersler</h3>
            <table style="width: 100%; color: var(--text-muted); text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid #444; color: #fff;">
                        <th>Ders</th>
                        <th>Tarih</th>
                        <th>Kayıtlı</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Gelecekteki dersleri listele
                    $sql = "SELECT d.*, (SELECT COUNT(*) FROM ders_kayitlari WHERE ders_id = d.id) as kayitli_sayisi FROM dersler d WHERE tarih_saat > NOW() ORDER BY tarih_saat ASC";
                    $stmt = $db->query($sql);
                    while($ders = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr style='border-bottom: 1px solid #222;'>";
                        echo "<td style='padding: 10px;'>" . htmlspecialchars($ders['ders_adi']) . "<br><small>" . htmlspecialchars($ders['egitmen']) . "</small></td>";
                        echo "<td>" . date("d.m H:i", strtotime($ders['tarih_saat'])) . "</td>";
                        echo "<td>" . $ders['kayitli_sayisi'] . " / " . $ders['kontenjan'] . "</td>";
                        echo "<a href='admin-ders-katilimcilar.php?id=" . $ders['id'] . "' class='btn' style='padding: 5px 10px; font-size: 0.8rem; margin-right: 5px; background: #3498db;'>👥 LİSTE</a>";
                        echo "<td><a href='?sil=" . $ders['id'] . "' style='color: var(--danger); font-size: 0.8rem;' onclick='return confirm(\"Silmek istediğine emin misin?\")'>İPTAL ET</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>