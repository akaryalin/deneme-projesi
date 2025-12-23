-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 18 Ara 2025, 19:18:44
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `spor_salonu`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `dersler`
--

CREATE TABLE `dersler` (
  `id` int(11) NOT NULL,
  `ders_adi` varchar(100) NOT NULL,
  `egitmen` varchar(100) NOT NULL,
  `tarih_saat` datetime NOT NULL,
  `kontenjan` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `dersler`
--

INSERT INTO `dersler` (`id`, `ders_adi`, `egitmen`, `tarih_saat`, `kontenjan`) VALUES
(1, 'Pilates', 'Ayşe Hoca', '2025-12-13 15:30:00', 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ders_kayitlari`
--

CREATE TABLE `ders_kayitlari` (
  `id` int(11) NOT NULL,
  `ders_id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `ders_kayitlari`
--

INSERT INTO `ders_kayitlari` (`id`, `ders_id`, `uye_id`, `kayit_tarihi`) VALUES
(1, 1, 4, '2025-12-10 15:44:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `duyurular`
--

CREATE TABLE `duyurular` (
  `id` int(11) NOT NULL,
  `mesaj` text NOT NULL,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `duyurular`
--

INSERT INTO `duyurular` (`id`, `mesaj`, `tarih`) VALUES
(1, 'Spor salonumuza hoş geldiniz! Tadilat nedeniyle Pazar günü kapalıyız.', '2025-12-10 15:11:03'),
(2, '1 Ocak Günü Kapalıyız', '2025-12-10 15:13:05');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `giris_hareketleri`
--

CREATE TABLE `giris_hareketleri` (
  `id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `tarih_saat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `giris_hareketleri`
--

INSERT INTO `giris_hareketleri` (`id`, `uye_id`, `tarih_saat`) VALUES
(1, 3, '2025-11-27 17:24:05'),
(2, 3, '2025-11-27 17:39:56'),
(3, 3, '2025-11-28 01:46:10'),
(4, 2, '2025-11-28 01:46:27'),
(5, 2, '2025-11-28 01:57:24'),
(6, 4, '2025-11-28 02:01:36'),
(7, 2, '2025-11-29 00:27:09'),
(8, 4, '2025-11-29 00:27:19'),
(9, 2, '2025-12-03 17:20:20'),
(10, 4, '2025-12-03 17:20:32'),
(11, 2, '2025-12-05 02:02:26'),
(12, 2, '2025-12-05 10:42:52'),
(13, 2, '2025-12-05 10:45:02'),
(14, 4, '2025-12-10 15:56:18');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mesajlar`
--

CREATE TABLE `mesajlar` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `konu` varchar(150) NOT NULL,
  `mesaj` text NOT NULL,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp(),
  `okundu` int(11) DEFAULT 0,
  `cevap` text DEFAULT NULL,
  `cevap_tarihi` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `mesajlar`
--

INSERT INTO `mesajlar` (`id`, `ad_soyad`, `email`, `konu`, `mesaj`, `tarih`, `okundu`, `cevap`, `cevap_tarihi`) VALUES
(1, 'test', 'test@gmail.com', 'kapalı', 'Pazar günleri kapalı mısınız', '2025-12-10 16:01:17', 1, 'evet kapalıyız', '2025-12-10 16:12:02');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `odemeler`
--

CREATE TABLE `odemeler` (
  `id` int(11) NOT NULL,
  `uye_id` int(11) NOT NULL,
  `tutar` decimal(10,2) NOT NULL,
  `aciklama` varchar(255) NOT NULL,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `odemeler`
--

INSERT INTO `odemeler` (`id`, `uye_id`, `tutar`, `aciklama`, `tarih`) VALUES
(1, 2, 1500.00, '3 aylık', '2025-12-10 15:07:47'),
(2, 4, 2500.00, '5 Aylık', '2025-12-10 15:55:55');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `paketler`
--

CREATE TABLE `paketler` (
  `id` int(11) NOT NULL,
  `paket_adi` varchar(100) NOT NULL,
  `ozellikler` text NOT NULL COMMENT 'Özellikleri | işareti ile ayırarak yaz',
  `fiyat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `paketler`
--

INSERT INTO `paketler` (`id`, `paket_adi`, `ozellikler`, `fiyat`) VALUES
(1, 'Altın Paket', 'Sınırsız Salon Kullanımı|Kişiye Özel Antrenman Programı|Tüm Grup Derslerine Katılım|Sauna & Buhar Odası|Ayda 4 Kez Vücut Analizi', '800 TL / Aylık'),
(2, 'Gümüş Paket', 'Sınırsız Salon Kullanımı|Standart Antrenman Programı|Haftada 2 Grup Dersine Katılım|-|Ayda 1 Kez Vücut Analizi', '500 TL / Aylık'),
(3, 'Bronz Paket', 'Sınırsız Salon Kullanımı|-|-|-|-', '350 TL / Aylık');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uyeler`
--

CREATE TABLE `uyeler` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` varchar(20) DEFAULT 'uye',
  `uyelik_bitis` date DEFAULT NULL,
  `antrenor` varchar(100) DEFAULT 'Henüz Atanmadı',
  `antrenman_programi` text DEFAULT NULL,
  `diyet_listesi` text DEFAULT NULL,
  `boy` int(11) DEFAULT NULL,
  `kilo` decimal(5,1) DEFAULT NULL,
  `profil_resmi` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `uyeler`
--

INSERT INTO `uyeler` (`id`, `kullanici_adi`, `email`, `sifre`, `kayit_tarihi`, `rol`, `uyelik_bitis`, `antrenor`, `antrenman_programi`, `diyet_listesi`, `boy`, `kilo`, `profil_resmi`) VALUES
(2, 'yalın', 'akaryalin17@gmail.com', '$2y$10$dKnNBDSGBtt.Wc509SF55uwTVCia1oFCDm65CO2t4r8zCUneay7Ta', '2025-10-30 19:39:03', 'admin', '2025-12-25', 'Mehmet Hoca', '1. Gün: Göğüs & Ön Kol\r\n- Bench Press: 4x10\r\n- Incline Dumbbell Press: 3x12\r\n- Cable Crossover: 3x15\r\n- Barbell Curl: 4x10\r\n\r\n2. Gün: Sırt & Arka Kol\r\n- Lat Pulldown: 4x10\r\n- Dumbbell Row: 3x12\r\n- Triceps Pushdown: 4x12', 'Kahvaltı: 3 Yumurta, 50gr Lor Peyniri, Yulaf Ezmesi\r\nÖğle: 200gr Tavuk Göğsü, 100gr Pirinç Lapa\r\nAra Öğün: 1 Yeşil Elma, 10 Badem\r\nAkşam: Ton Balıklı Salata', 182, 100.0, 'profil_2.jpg'),
(4, 'test', 'test@gmail.com', '$2y$10$qdi580RNpo6fTdfQvR3va.ooL3hFFBAT6nPBqWBNgOCWKoi6A0RIy', '2025-11-28 01:49:02', 'uye', '2026-01-03', 'Yalın', '', '', 175, 65.0, 'default.png');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `dersler`
--
ALTER TABLE `dersler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `ders_kayitlari`
--
ALTER TABLE `ders_kayitlari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `duyurular`
--
ALTER TABLE `duyurular`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `giris_hareketleri`
--
ALTER TABLE `giris_hareketleri`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `mesajlar`
--
ALTER TABLE `mesajlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `odemeler`
--
ALTER TABLE `odemeler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `paketler`
--
ALTER TABLE `paketler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `uyeler`
--
ALTER TABLE `uyeler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanici_adi` (`kullanici_adi`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `dersler`
--
ALTER TABLE `dersler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `ders_kayitlari`
--
ALTER TABLE `ders_kayitlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `duyurular`
--
ALTER TABLE `duyurular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `giris_hareketleri`
--
ALTER TABLE `giris_hareketleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `mesajlar`
--
ALTER TABLE `mesajlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `odemeler`
--
ALTER TABLE `odemeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `paketler`
--
ALTER TABLE `paketler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `uyeler`
--
ALTER TABLE `uyeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
