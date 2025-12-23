<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Üye Bilgilerini Çek
$sql = "SELECT * FROM uyeler WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$user_id]);
$uye = $stmt->fetch(PDO::FETCH_ASSOC);

// Resim Yolu
$resim = !empty($uye['profil_resmi']) ? "uploads/".$uye['profil_resmi'] : "uploads/default.png";

// Üyelik Durumu (Aktif/Pasif)
$durum = "AKTİF";
$durum_renk = "#2ecc71"; // Yeşil
if ($uye['uyelik_bitis'] && new DateTime($uye['uyelik_bitis']) < new DateTime()) {
    $durum = "SÜRESİ DOLDU";
    $durum_renk = "#e74c3c"; // Kırmızı
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dijital Kimlik Kartım</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #111;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            color: #fff;
        }

        /* KART TASARIMI */
        .id-card {
            width: 350px;
            height: 550px;
            background: linear-gradient(135deg, #1a1a1a 0%, #000 100%);
            border-radius: 20px;
            border: 2px solid #cfff04; /* Neon Çerçeve */
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 50px rgba(207, 255, 4, 0.2);
            text-align: center;
            padding: 20px;
            max-width: 90%; /* Telefondan geniş olamaz */
            margin: 0 auto; /* Ortala */
        }

        /* Arka plan süslemesi */
        .bg-pattern {
            position: absolute;
            top: -50px; left: -50px;
            width: 200px; height: 200px;
            background: #cfff04;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.2;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
            letter-spacing: 2px;
            margin-bottom: 30px;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .profil-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #cfff04;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .isim {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .durum-badge {
            display: inline-block;
            background: <?php echo $durum_renk; ?>;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .qr-area {
            background: #fff;
            padding: 10px;
            border-radius: 10px;
            display: inline-block;
            position: relative;
            z-index: 2;
        }

        .alt-bilgi {
            margin-top: 20px;
            font-size: 0.8rem;
            color: #666;
            position: relative;
            z-index: 2;
        }

        /* YAZDIR BUTONU */
        .print-btn {
            margin-top: 30px;
            background: #cfff04;
            color: #000;
            border: none;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }
        .print-btn:hover {
            box-shadow: 0 0 20px #cfff04;
        }
        
        /* Yazdırırken buton gizlensin */
        @media print {
            .print-btn, .back-link { display: none; }
            body { background: #fff; }
        }
    </style>
</head>
<body>

    <div class="id-card">
        <div class="bg-pattern"></div>
        
        <div class="logo">SPOR SALONU</div>

        <img src="<?php echo $resim; ?>" class="profil-img" alt="Profil">

        <h2 class="isim"><?php echo htmlspecialchars($uye['kullanici_adi']); ?></h2>
        
        <div class="durum-badge"><?php echo $durum; ?></div>

        <div class="qr-area">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=UyeID-<?php echo $user_id; ?>" alt="QR">
        </div>

        <div class="alt-bilgi">
            Üye No: #<?php echo str_pad($user_id, 4, "0", STR_PAD_LEFT); ?> <br>
            Geçerlilik: <?php echo $uye['uyelik_bitis'] ? date("d.m.Y", strtotime($uye['uyelik_bitis'])) : "Süresiz"; ?>
        </div>
    </div>

    <div style="margin-top: 20px; display: flex; gap: 15px;">
        <a href="javascript:window.print()" class="print-btn">🖨️ YAZDIR</a>
        <a href="profil.php" class="print-btn back-link" style="background: #333; color: #fff;">GERİ DÖN</a>
    </div>

</body>
</html>