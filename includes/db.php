<?php
$sunucu = "localhost"; 
$kullanici_adi = "root";    
$sifre = "";            
$veritabani = "spor_salonu"; 

try {
    $db = new PDO("mysql:host=$sunucu;dbname=$veritabani;charset=utf8", $kullanici_adi, $sifre);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
?>