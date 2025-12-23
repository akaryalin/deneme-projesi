<?php
// AKILLI OTURUM BAŞLATMA
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spor Salonu Projesi</title> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#cfff04">
    <link rel="apple-touch-icon" href="uploads/default.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('service-worker.js')
                    .then(reg => console.log('Uygulama hazır:', reg.scope))
                    .catch(err => console.log('Hata:', err));
            });
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
        <a href="index.php" id="logo">HANGAR <span style="color: var(--accent);">GYM</span></a>
            <nav>
                <ul>
                    <li><a href="index.php">Ana Sayfa</a></li>
                    <li><a href="hakkimizda.php">Hakkımızda</a></li>
                    <li><a href="uyelikler.php">Üyelikler</a></li>
                    <li><a href="iletisim.php">İletişim</a></li>

                    <?php
                    // BİLEKLİK (SESSION) KONTROLÜ
                    if (isset($_SESSION['user_id'])) {
                        
                        // --- GİRİŞ YAPMIŞ KULLANICI ---

                        // 1. EĞER ADMİNSE BUNLARI GÖSTER:
                        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') {
                            echo '<li><a href="admin-panel.php" style="color: #ff9f43;">YÖNETİCİ</a></li>';
                            echo '<li><a href="admin-mesajlar.php" style="color: #e84393;">MESAJLAR</a></li>';
                            echo '<li><a href="admin-finans.php" style="color: #2ecc71;">FİNANS</a></li>';
                            echo '<li><a href="admin-dersler.php" style="color: #3498db;">DERS PROGRAMLA</a></li>';
                            echo '<li><a href="qr-okuyucu.php" style="color:var(--accent);">QR OKUYUCU</a></li>';
                        } 
                        // 2. EĞER NORMAL ÜYEYSE (ADMİN DEĞİLSE) BUNU GÖSTER:
                        else {
                            echo '<li><a href="dersler.php" style="color: var(--accent);">GRUP DERSLERİ</a></li>';
                        }

                        // 3. HERKES BUNLARI GÖRSÜN:
                        echo '<li><a href="profil.php">Profilim</a></li>';
                        echo '<li><a href="cikis.php">Çıkış Yap</a></li>';

                    } else {
                        
                        // --- MİSAFİR KULLANICI (GİRİŞ YAPMAMIŞ) ---
                        echo '<li><a href="kayit.php">Kayıt Ol</a></li>';
                        echo '<li><a href="giris.php">Giriş Yap</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">