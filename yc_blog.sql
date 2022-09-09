-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 09 Eyl 2022, 08:09:58
-- Sunucu sürümü: 10.4.19-MariaDB
-- PHP Sürümü: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `yc_blog`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_isim` text NOT NULL,
  `admin_k_adi` text NOT NULL,
  `admin_eposta` text NOT NULL,
  `admin_sifre` text NOT NULL,
  `admin_resim` text DEFAULT NULL,
  `admin_tarih` text NOT NULL,
  `admin_yazilar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `admin_isim`, `admin_k_adi`, `admin_eposta`, `admin_sifre`, `admin_resim`, `admin_tarih`, `admin_yazilar`) VALUES
(1, 'Yusuf Çelmez', 'yusuf', 'yusuf@celmez.com', '123456', NULL, '09.09.2022 05:27:00', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `begeniler`
--

CREATE TABLE `begeniler` (
  `id` int(11) NOT NULL,
  `yaziyi_begenen` text NOT NULL,
  `begenilen_yazi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `begeniler`
--

INSERT INTO `begeniler` (`id`, `yaziyi_begenen`, `begenilen_yazi`) VALUES
(1, '::1', 'ikinci-deneme-yazisi'),
(2, '::1', 'bu-bir-deneme-yazisi');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `goruntulenmeler`
--

CREATE TABLE `goruntulenmeler` (
  `id` int(11) NOT NULL,
  `goruntuleyen_k_adi` text NOT NULL,
  `goruntulenen_yazi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `goruntulenmeler`
--

INSERT INTO `goruntulenmeler` (`id`, `goruntuleyen_k_adi`, `goruntulenen_yazi`) VALUES
(1, '::1', 'ikinci-deneme-yazisi'),
(2, '::1', 'bu-bir-deneme-yazisi'),
(3, '::1', 'POST'),
(4, '::1', 'yusuf'),
(5, '::1', 'bu-ucuncu-yazi'),
(6, '::1', 'dncjsncjsncdsd'),
(7, '::1', 'hello');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uyeler`
--

CREATE TABLE `uyeler` (
  `id` int(11) NOT NULL,
  `isim` text NOT NULL,
  `k_adi` text NOT NULL,
  `eposta` text NOT NULL,
  `sifre` text NOT NULL,
  `resim` text DEFAULT NULL,
  `tarih` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `uyeler`
--

INSERT INTO `uyeler` (`id`, `isim`, `k_adi`, `eposta`, `sifre`, `resim`, `tarih`) VALUES
(1, 'Yusuf Çelmez', 'yusuf', 'yusuf@celmez.com', '8vWGTaqTKlscQgf16ADvBA==', NULL, '09.09.2022 07:43:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yazilar`
--

CREATE TABLE `yazilar` (
  `id` int(11) NOT NULL,
  `yazan_admin_k_adi` text NOT NULL,
  `yazi` text NOT NULL,
  `yazi_baslik` text NOT NULL,
  `yazi_resim` text DEFAULT NULL,
  `yazi_url` text NOT NULL,
  `yazi_tarihi` text NOT NULL,
  `yazi_begeni` text NOT NULL DEFAULT '0',
  `yazi_yorum` text NOT NULL DEFAULT '0',
  `yazi_goruntulenme` text NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `yazilar`
--

INSERT INTO `yazilar` (`id`, `yazan_admin_k_adi`, `yazi`, `yazi_baslik`, `yazi_resim`, `yazi_url`, `yazi_tarihi`, `yazi_begeni`, `yazi_yorum`, `yazi_goruntulenme`) VALUES
(1, 'yusuf', 'Merhaba bu bir deneme yazısıdır. Sonunda istediğim blog scriptini kodladım yehuuuuu!!!!! &lt;b&gt;Yusuf Çelmez&lt;/b&gt;', 'Bu bir deneme yazısı', NULL, 'bu-bir-deneme-yazisi', '09.09.2022 09:04:23', '0', '6', '0');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

CREATE TABLE `yorumlar` (
  `id` int(11) NOT NULL,
  `yorum_yapan_k_adi` text NOT NULL,
  `yorum_yapilan_yazi` text NOT NULL,
  `yorum` text NOT NULL,
  `yorum_tarihi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `yorumlar`
--

INSERT INTO `yorumlar` (`id`, `yorum_yapan_k_adi`, `yorum_yapilan_yazi`, `yorum`, `yorum_tarihi`) VALUES
(1, 'yusufcelmez', 'ikinci-deneme-yazisi', 'Merhaba bu bir deneme yorumudur', '09.09.2022 07:02:37'),
(2, 'yusufcelmez', 'ikinci-deneme-yazisi', 'Merhaba bu bir deneme yorumudur', '09.09.2022 07:02:47'),
(3, 'yusufcelmez', 'ikinci-deneme-yazisi', 'Merhaba bu bir deneme yorumudur', '09.09.2022 07:02:53'),
(4, 'YUSUF', 'ikinci-deneme-yazisi', 'DNCJSDNC', '09.09.2022 07:03:16'),
(5, 'yusufcelmez', 'ikinci-deneme-yazisi', '124', '09.09.2022 07:07:05'),
(6, 'yusuf', 'ikinci-deneme-yazisi', 'hello', '09.09.2022 08:24:02'),
(7, 'yusuf', 'ikinci-deneme-yazisi', 'Benim Adım Bu', '09.09.2022 08:24:08'),
(8, 'yusuf', 'ikinci-deneme-yazisi', 'Benim Adım Bu', '09.09.2022 08:28:01'),
(9, 'yusuf', 'ikinci-deneme-yazisi', 'Benim Adım Bu', '09.09.2022 08:28:15'),
(10, 'yusuf', 'dncjsncjsncdsd', 'HELLO', '09.09.2022 09:00:33'),
(11, 'yusuf', 'dncjsncjsncdsd', 'cdjcdsj', '09.09.2022 09:00:46'),
(12, 'yusuf', 'dncjsncjsncdsd', 'cdjcdsj', '09.09.2022 09:00:48'),
(13, 'yusuf', 'bu-bir-deneme-yazisi', 'Hellodncjsdnj', '09.09.2022 09:05:31'),
(14, 'yusuf', 'bu-bir-deneme-yazisi', 'dncjsdnj', '09.09.2022 09:05:53'),
(15, 'yusuf', 'bu-bir-deneme-yazisi', 'dncjsdnj', '09.09.2022 09:08:14'),
(16, 'yusuf', 'bu-bir-deneme-yazisi', 'dcnsdjc', '09.09.2022 09:08:18'),
(17, 'yusuf', 'bu-bir-deneme-yazisi', 'ncsdncnsd', '09.09.2022 09:08:23'),
(18, 'yusuf', 'bu-bir-deneme-yazisi', 'cdkcdsk', '09.09.2022 09:08:26');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `begeniler`
--
ALTER TABLE `begeniler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `goruntulenmeler`
--
ALTER TABLE `goruntulenmeler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `uyeler`
--
ALTER TABLE `uyeler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yazilar`
--
ALTER TABLE `yazilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `begeniler`
--
ALTER TABLE `begeniler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `goruntulenmeler`
--
ALTER TABLE `goruntulenmeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `uyeler`
--
ALTER TABLE `uyeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `yazilar`
--
ALTER TABLE `yazilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `yorumlar`
--
ALTER TABLE `yorumlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
