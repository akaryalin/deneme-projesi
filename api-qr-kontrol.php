<?php
require 'includes/db.php';
header('Content-Type: application/json');

// Gelen veriyi al
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['qr_code'])) {
    $qr_kod = $data['qr_code'];
    $uye_id = str_replace('UyeID-', '', $qr_kod);

    try {
        // 1. Üyeyi Kontrol Et
        $sql = "SELECT * FROM uyeler WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$uye_id]);
        $uye = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($uye) {
            // ÜYE BULUNDU!
            
            // 2. YENİ EKLENEN KISIM: Giriş Kaydını Logla
            // giris_hareketleri tablosuna üyenin ID'sini ve şu anki saati kaydediyoruz.
            $sqlLog = "INSERT INTO giris_hareketleri (uye_id) VALUES (?)";
            $stmtLog = $db->prepare($sqlLog);
            $stmtLog->execute([$uye['id']]);

            // Cevabı Gönder
            echo json_encode([
                'durum' => 'basarili',
                'mesaj' => 'Giriş Onaylandı & Kaydedildi!',
                'isim' => $uye['kullanici_adi']
            ]);
        } else {
            echo json_encode([
                'durum' => 'hata',
                'mesaj' => 'Geçersiz QR Kod!'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['durum' => 'hata', 'mesaj' => 'Veritabanı hatası']);
    }
} else {
    echo json_encode(['durum' => 'hata', 'mesaj' => 'Veri gelmedi']);
}
?>