<?php 
include 'includes/db.php';
include 'includes/header.php'; 

$mesaj_durumu = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adsoyad = htmlspecialchars(trim($_POST['adsoyad']));
    $email = htmlspecialchars(trim($_POST['email']));
    $konu = htmlspecialchars(trim($_POST['konu']));
    $mesaj = htmlspecialchars(trim($_POST['mesaj']));

    if(!empty($adsoyad) && !empty($mesaj)) {
        // Veritabanına kaydet
        $sql = "INSERT INTO mesajlar (ad_soyad, email, konu, mesaj) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$adsoyad, $email, $konu, $mesaj])) {
            $mesaj_durumu = "<div class='success-message'>Mesajınız bize ulaştı. En kısa sürede dönüş yapacağız!</div>";
        } else {
            $mesaj_durumu = "<div class='error-message'>Bir hata oluştu, lütfen tekrar deneyin.</div>";
        }
    }
}
?>

<div class="container">
    <div class="form-container" style="max-width: 800px; margin-top: 50px;">
        <h2 style="text-align: center; color: var(--accent); margin-bottom: 20px;">Bize Ulaşın</h2>
        
        <?php echo $mesaj_durumu; ?>

        <p style="text-align: center; margin-bottom: 30px; color: var(--text-muted);">
            Sorularınız, önerileriniz veya şikayetleriniz için aşağıdaki formu doldurabilirsiniz.
        </p>

        <form action="iletisim.php" method="POST">
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="adsoyad">Ad Soyad:</label>
                    <input type="text" id="adsoyad" name="adsoyad" required>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="email">E-posta:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="konu">Konu:</label>
                <input type="text" id="konu" name="konu" required>
            </div>

            <div class="form-group">
                <label for="mesaj">Mesajınız:</label>
                <textarea id="mesaj" name="mesaj" rows="6" required></textarea>
            </div>

            <button type="submit" class="form-btn">MESAJI GÖNDER</button>
        </form>
        
        <div style="margin-top: 40px; border-top: 1px solid #333; padding-top: 20px; display: flex; justify-content: space-around; text-align: center; color: var(--text-muted);">
            <div>
                <h4 style="color: #fff;">📞 Telefon</h4>
                <p>0212 555 55 55</p>
            </div>
            <div>
                <h4 style="color: #fff;">📍 Adres</h4>
                <p>Spor Caddesi, No: 1, İstanbul</p>
            </div>
            <div>
                <h4 style="color: #fff;">📧 E-posta</h4>
                <p>info@sporsalonu.com</p>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>