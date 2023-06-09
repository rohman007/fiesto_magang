-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Apr 2023 pada 05.05
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fiesto_magang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `template_data`
--

CREATE TABLE `template_data` (
  `id` int(11) NOT NULL,
  `param` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `template_data`
--

INSERT INTO `template_data` (`id`, `param`, `value`) VALUES
(1, 'header_left', '<p><a href=\"https://wa.me/6281252856885\"><img style=\"width: 30px;\" src=\"https://fiesto.com/temp/jone/file/media/source/WA.png\" alt=\"WA\" />&nbsp; 0812-5285-6885</a></p>'),
(2, 'header_right', ''),
(3, 'slide1_title', 'INDONESIAN ORIGINAL PRODUCT'),
(4, 'slide1_content', ''),
(5, 'slide2_title', 'INTERNATIONAL QUALITY'),
(6, 'slide2_content', ''),
(7, 'slide3_title', 'PRACTICAL & AFFORDABLE'),
(8, 'slide3_content', ''),
(9, 'slidemobile1_title', ''),
(10, 'slidemobile1_content', ''),
(11, 'slidemobile2_title', ''),
(12, 'slidemobile2_content', ''),
(13, 'slidemobile3_title', ''),
(14, 'slidemobile3_content', ''),
(15, 'title_call_block', 'Pertanyaan Seputar kebutuhan Anda?'),
(16, 'link_call_block', 'tel:+62222518068'),
(17, 'teks_call_block', 'Hubungi Kami'),
(18, 'strength_title_1', 'Berpengalaman'),
(19, 'strength_content_1', '<p>Berdiri lebih dari dua dekade menjadi jasa pengangkutan barang melalui laut - domestik dan Internasional</p>'),
(20, 'strength_title_2', 'Terpercaya'),
(21, 'strength_content_2', '<p>Terdaftar di Kementerian Perhubungan No. AL.003/B.737/PA-V/94 tanggal 18 Mei 1994</p>'),
(22, 'strength_title_3', 'Pelayanan Terbaik'),
(23, 'strength_content_3', '<p>Pelayanan dan standar kerja yang profesional untuk memberikan kepuasan kepada pelanggan.</p>'),
(24, 'title_who_block', ''),
(25, 'content_who_block', '<h3>Welcome to</h3>\r\n<h1>Stepping Stones Schools</h1>\r\n<p>We are manufacture charcoal company from Indonesia, Established and registered since 2012 with Indonesian government, produce legaly also eco friendly for various kind of natural charcoal and coconut briquette charcoal.</p>\r\n<p>We are strong company who have 8 warehouse in 7 big city of Indonesia, export all charcoal variety more than 130 container / month. we have big cooperation with some steel and ferroalloy factory in China, Korea and Japan, we export industry charcoal for steel production and also high quality bbq charcoal.</p>'),
(26, 'button_who_block', 'Selengkapnya'),
(27, 'link_who_block', '#'),
(28, 'title_service', 'Popular Categories'),
(29, 'service_content', ''),
(30, 'service_title_1', '<p>High Quality Product</p>'),
(31, 'service_title_2', '<p>asdfasdf</p>'),
(32, 'service_title_3', ''),
(33, 'footerlink_1', '<p>Tes</p>'),
(34, 'footerlink_2', '<p style=\"text-align: center;\"><strong>Contact Us</strong></p>\r\n<p><a href=\"https://wa.me/6281252856885\"><img style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://fiesto.com/temp/jone/file/media/source/WA footer.png\" alt=\"WA footer\" /></a></p>'),
(35, 'footerlink_3', '<p style=\"text-align: center;\"><strong>Marketplace</strong></p>\r\n<p><a href=\"https://www.tokopedia.com/jonejewelry\"><img style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://fiesto.com/temp/jone/file/media/source/toped footer.png\" alt=\"toped footer\" /></a></p>'),
(36, 'footerlink_4', ''),
(37, 'copyright', '<p>Powered by <a href=\"https://fiesto.com/\">Fiesto</a></p>'),
(38, 'phone_number', '+6281252856885'),
(39, 'wa_number', '6281252856885'),
(40, 'email_link', '*'),
(41, 'map_link', ''),
(42, 'line_link', ''),
(43, 'bbm_link', ''),
(44, 'logo', 'logo.png'),
(45, 'slide1_img', 'slide1_img.jpg'),
(46, 'slide2_img', 'slide2_img.jpg'),
(47, 'slide3_img', 'slide3_img.jpg'),
(48, 'slidemobile1_img', 'slidemobile1_img.jpeg'),
(49, 'slidemobile2_img', 'slidemobile2_img.jpeg'),
(50, 'slidemobile3_img', 'slidemobile3_img.jpeg'),
(51, 'strength_img_1', 'strength_img_1.png'),
(52, 'strength_img_2', 'strength_img_2.png'),
(53, 'strength_img_3', 'strength_img_3.png'),
(54, 'image_who_block', 'image_who_block.png'),
(55, 'bg_footerlink', 'bg_footerlink.jpg'),
(854, 'gallery_judul', 'Perlindungan Sesuai Budget Anda'),
(855, 'product_title_1', 'Ekspedisi Motor Kapal Laut'),
(856, 'product_link_1', 'http://localhost/iwd/hibob/page/view/7_sea_freight_forwarding'),
(857, 'product_title_2', 'Angkutan Darat'),
(858, 'product_link_2', 'http://localhost/iwd/hibob/page/view/8_inland_transport'),
(859, 'product_title_3', 'Gudang'),
(860, 'product_link_3', 'http://localhost/iwd/hibob/page/view/9_warehousing'),
(861, 'product_title_4', 'Bea Masuk'),
(862, 'product_link_4', 'http://localhost/iwd/hibob/page/view/10_custom_clearance'),
(885, 'product_img_1', 'product_img_1.jpg'),
(886, 'product_img_2', 'product_img_2.jpg'),
(887, 'product_img_3', 'product_img_3.jpg'),
(888, 'product_img_4', 'product_img_4.jpg'),
(897, 'slide_content', '<h1>Wujudkan impian &amp; -----<br />bangun hari esokmu ----<br />yang lebih baik --------<br />bersama kami!</h1>'),
(989, 'secondlogo', 'secondlogo.jpg'),
(1035, 'strength_bg', 'strength_bg.jpg'),
(1991, 'bg-header-title', 'bg-header-title.jpg'),
(1992, 'no_telp_header', ''),
(1993, 'email_header', ''),
(2016, 'bn_judul', 'Artikel'),
(2017, 'bn_category', '3'),
(2018, 'bn_limit', '3'),
(2031, 'otherlogo', ''),
(2043, 'bgproduk', ''),
(2083, 'footerlink_image', 'footerlink_image.jpg'),
(2084, 'footerlink_image_mobile', 'footerlink_image_mobile.jpg'),
(2155, 'slide1_link', ''),
(2156, 'slide2_link', ''),
(2157, 'slide3_link', ''),
(2201, 'title_asuransi', '<span>Lindungi</span> Mereka!'),
(2202, 'content_asuransi', '<p>Hidup jangan mengalir saja. Miliki Plan B untuk antisipasi!</p>\r\n<p>Jika ada penyelamat yang siap menanggung biaya RS saat Anda sakit atau kecelakaan, serta membantu menafkahi keluarga... tentu Anda mau kan? Pilih sesuai kebutuhan perlindungan Anda...</p>'),
(2207, 'fase_judul', 'Perlindungan Sesuai Fase Hidup Anda'),
(2208, 'fase_link_1', ''),
(2209, 'fase_link_2', ''),
(2210, 'fase_link_3', ''),
(2211, 'fase_link_4', ''),
(2212, 'fase_link_5', ''),
(2230, 'image_asuransi', 'image_asuransi.jpg'),
(2234, 'fase_img_1', 'fase_img_1.jpg'),
(2235, 'fase_img_2', 'fase_img_2.jpg'),
(2236, 'fase_img_3', 'fase_img_3.jpg'),
(2237, 'fase_img_4', 'fase_img_4.jpg'),
(2238, 'fase_img_5', 'fase_img_5.jpg'),
(2307, 'fase_title_1', 'Lajang'),
(2309, 'fase_title_2', 'Menikah'),
(2311, 'fase_title_3', 'Menikah + Anak'),
(2313, 'fase_title_4', 'Anak Remaja'),
(2315, 'fase_title_5', 'Pensiun'),
(2640, 'kebutuhan_judul', 'Perlindungan Sesuai Kebutuhan'),
(2641, 'kebutuhan_title_1', 'Income'),
(2642, 'kebutuhan_link_1', ''),
(2643, 'kebutuhan_title_2', 'Income'),
(2644, 'kebutuhan_link_2', ''),
(2645, 'kebutuhan_title_3', 'Kesehatan'),
(2646, 'kebutuhan_link_3', ''),
(2647, 'kebutuhan_title_4', 'Pendidikan'),
(2648, 'kebutuhan_link_4', ''),
(2649, 'kebutuhan_title_5', 'Pensiun'),
(2650, 'kebutuhan_link_5', ''),
(2677, 'kebutuhan_img_1', 'kebutuhan_img_1.jpg'),
(2678, 'kebutuhan_img_2', 'kebutuhan_img_2.jpg'),
(2679, 'kebutuhan_img_3', 'kebutuhan_img_3.jpg'),
(2680, 'kebutuhan_img_4', 'kebutuhan_img_4.jpg'),
(2681, 'kebutuhan_img_5', 'kebutuhan_img_5.jpg'),
(2687, 'banner_link_1', '#'),
(2688, 'banner_title_1', 'Active & Passive Income'),
(2689, 'banner_link_2', '#'),
(2690, 'banner_title_2', 'Jenjang Karir'),
(2691, 'banner_link_3', '#'),
(2692, 'banner_title_3', 'Penghargaan'),
(2693, 'banner_link_4', '#'),
(2694, 'banner_title_4', 'Overseas Trip'),
(2695, 'banner_link_5', '#'),
(2696, 'banner_title_5', 'Car Ownership Program'),
(2697, 'banner_link_6', '#'),
(2698, 'banner_title_6', 'Dapat Diwariskan'),
(2699, 'event_judul', 'Agenda & Event'),
(2700, 'event_limit', '5'),
(2701, 'success_judul', 'Success Story'),
(2702, 'success_limit', '3'),
(2703, 'top_judul', 'Top Of The Month'),
(2704, 'top_limit', '3'),
(2708, 'agen_judul', 'Agen Berprestasi'),
(2709, 'agen_category', '7'),
(2710, 'agen_limit', '10'),
(2719, 'thumbnaillogo', 'thumbnaillogo.jpg'),
(2721, 'slide1mobile_img', 'slide1mobile_img.jpg'),
(2723, 'image_banner1', 'image_banner1.jpg'),
(2724, 'image_banner2', 'image_banner2.jpg'),
(2725, 'image_banner3', 'image_banner3.png'),
(2726, 'image_banner4', 'image_banner4.png'),
(2727, 'image_banner5', 'image_banner5.png'),
(2728, 'image_banner6', 'image_banner6.png'),
(2732, 'slide_button1', 'Pelajari Sekarang'),
(2733, 'slide_link1', '#'),
(2787, 'service_judul', 'Bergabunglah Bersama Kami'),
(2788, 'service_button', 'Pelajari Sekarang'),
(2789, 'service_link', '#'),
(2822, 'update_judul', 'Maestro Update!!'),
(2823, 'update_category', '3'),
(2824, 'update_limit', '4'),
(2834, 'service_text', '<p>Lorem ipsum</p>'),
(2839, 'product_limit', '5'),
(2840, 'content_map', '<h1>Ready for Admission?</h1>\r\n<h3>Fill out this forms</h3>'),
(2841, 'button_map', 'Register'),
(2842, 'link_map', '#'),
(2846, 'title_banner', ''),
(2847, 'content_banner', ''),
(2865, 'backgroun_map', 'backgroun_map.jpg'),
(2870, 'title_product', 'Produk Promo'),
(2904, 'strength_link_1', '#'),
(2907, 'strength_link_2', '#'),
(2910, 'strength_link_3', '#'),
(2913, 'slide_teks', '<h1 style=\"text-align: center;\">BEST SEAFOOD</h1>\r\n<h1 style=\"text-align: center;\">IN SURABAYA</h1>\r\n<div style=\"text-align: center;\">Always Fresh . Always Tasty</div>'),
(2917, 'title_contact', '<h1>World Wide Food Services</h1>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>'),
(2918, 'button_contact', 'Read More'),
(2919, 'url_contact', '#'),
(2920, 'title_tiga_pilar', 'Categories'),
(2921, 'text_banner_home_1', 'How It Works'),
(2922, 'url_banner_home_1', 'https://fiesto.com/temp/jone/catalog/images/1_cincin_wanita'),
(2923, 'text_banner_home_2', '3 Easy Steps'),
(2924, 'url_banner_home_2', 'https://fiesto.com/temp/jone/catalog/images/2_anting__giwang'),
(2925, 'text_banner_home_3', 'Why Bibob'),
(2926, 'url_banner_home_3', 'https://fiesto.com/temp/jone/catalog/images/5_kalung_wanita'),
(2927, 'text_banner_home_4', 'Harga Bersaing'),
(2928, 'url_banner_home_4', ''),
(2929, 'url_banner_home_5', ''),
(2930, 'title_empat_pilar', 'Facilities'),
(2931, 'text_empat_pilar', '<p>Lorem</p>'),
(2932, 'video_title', ''),
(2933, 'video_limit', ''),
(2953, 'image_contact', 'image_contact.jpg'),
(2954, 'banner_home_1', 'banner_home_1.jpg'),
(2955, 'banner_home_2', 'banner_home_2.jpg'),
(2956, 'banner_home_3', 'banner_home_3.jpg'),
(2957, 'banner_home_4', 'banner_home_4.png'),
(2958, 'banner_home_5', ''),
(2959, 'banner_home_5_2', ''),
(2998, 'bg_news', 'bg_news.jpg'),
(2999, 'bg_footer', 'bg_footer.jpg'),
(3003, 'slide_title1', 'Exports Around The World'),
(3004, 'slide_content1', ''),
(3007, 'slide_title2', 'Best Quality Charcoal'),
(3008, 'slide_content2', ''),
(3009, 'slide_button2', ''),
(3010, 'slide_link2', ''),
(3011, 'slide_title3', 'Fresh Materials'),
(3012, 'slide_content3', ''),
(3013, 'slide_button3', ''),
(3014, 'slide_link3', ''),
(3045, 'bg_header', 'bg_header.jpg'),
(3046, 'bakground_home', ''),
(3047, 'bg_who_block', 'bg_who_block.jpg'),
(3330, 'content_services', '<h1 style=\"text-align: center;\">Who Needs HiBob?</h1>\r\n<p style=\"text-align: center;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.&nbsp;</p>\r\n<ul>\r\n<li>Mall</li>\r\n<li>Perkantoran</li>\r\n<li>Hotel</li>\r\n</ul>\r\n<ul>\r\n<li>Sekolah</li>\r\n<li>Kampus</li>\r\n<li>Warehouse</li>\r\n</ul>\r\n<ul>\r\n<li>Pabrik</li>\r\n<li>Security</li>\r\n<li>Cleaning</li>\r\n</ul>'),
(3332, 'content_slide', '<p style=\"text-align: center;\">Let You Realize<br /> The Freedom of Jewelry</p>'),
(3336, 'title_catalog_block', 'Products'),
(3337, 'catalog_category', '1'),
(3549, 'button_slide', 'See Collection'),
(3550, 'link_slide', 'https://fiesto.com/temp/jone/catalog/images/1_catalog'),
(3599, 'images_who_block', ''),
(3672, 'content_info', '<table>\r\n<tbody>\r\n<tr>\r\n<td>\r\n<h1>Need more info and assistance?</h1>\r\n<h3>We will gladly help</h3>\r\n</td>\r\n<td><img src=\"http://localhost:8080/iwd/sss/file/media/source/WA footer.png\" alt=\"WA footer\" /></td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(3726, 'title-strength', 'What Our Customers Say'),
(3780, 'content_video1', '<p><iframe title=\"YouTube video player\" src=\"https://www.youtube.com/embed/FPDIpwnRx-s\" width=\"560\" height=\"315\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\"></iframe></p>'),
(3781, 'content_video2', '<p><iframe title=\"YouTube video player\" src=\"https://www.youtube.com/embed/_wzAU1o3eNg\" width=\"560\" height=\"315\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\"></iframe></p>'),
(3801, 'testi_limit', '3'),
(3856, 'content_strength', '<p>test</p>'),
(3863, 'call_content', '<table>\r\n<tbody>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>\r\n<h1>Contact Us for Customer Service</h1>\r\n<h3>We will gladly help</h3>\r\n</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>'),
(3976, 'text_banner_home_5', ''),
(3978, 'text_banner_home_6', ''),
(3979, 'url_banner_home_6', ''),
(3980, 'text_banner_home_7', ''),
(3981, 'url_banner_home_7', ''),
(3982, 'text_banner_home_8', ''),
(3983, 'url_banner_home_8', ''),
(4013, 'banner_home_6', ''),
(4014, 'banner_home_7', ''),
(4015, 'banner_home_8', ''),
(4103, 'text_facilities_1', 'Production Facilities'),
(4104, 'url_facilities_1', '#'),
(4105, 'text_facilities_2', 'Warehouse Facilities'),
(4106, 'url_facilities_2', '#'),
(4107, 'text_facilities_3', 'Research Facilities'),
(4108, 'url_facilities_3', '#'),
(4137, 'facilities_img_1', 'facilities_img_1.png'),
(4138, 'facilities_img_2', 'facilities_img_2.png'),
(4139, 'facilities_img_3', 'facilities_img_3.png'),
(4144, 'text_banner1', 'Home Decor'),
(4145, 'link_banner1', '#'),
(4146, 'text_banner2', 'Office Ideas'),
(4147, 'link_banner2', '#'),
(4503, 'news_testi', 'Article'),
(4504, 'news_category', '3'),
(4505, 'news_limit', '10'),
(4509, 'slide_teks2', '<h1 style=\"text-align: center;\">BEST SEAFOOD</h1>\r\n<h1 style=\"text-align: center;\">IN SURABAYA</h1>\r\n<div style=\"text-align: center;\">Always Fresh . Always Tasty</div>\r\n<p>&nbsp;</p>'),
(4511, 'slide_teks3', '<h1 style=\"text-align: center;\">BEST SEAFOOD</h1>\r\n<h1 style=\"text-align: center;\">IN SURABAYA</h1>\r\n<div style=\"text-align: center;\">Always Fresh . Always Tasty</div>'),
(5152, 'slogans_teks', '<h1>\"One cannot think well, love well and sleep well if one has not dined well. <br />Food is your common ground, a universal experience\"</h1>'),
(5198, 'produk_teks1', ''),
(5216, 'produk_teks', '<h1>BEST SEAFOOD<br />RESTAURANT<br />IN SURABAYA</h1>\r\n<p>&nbsp;</p>\r\n<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia eveniet, vitae dolorem commodi doloremque perspiciatis!Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia eveniet, vitae dolorem commodi doloremque perspiciatis!</div>'),
(5343, 'produk_Button', 'About Us'),
(5351, 'produk_Image1', 'produk_Image1.jpg'),
(5352, 'produk_Image2', 'produk_Image2.jpg'),
(5353, 'produk_Image3', 'produk_Image3.jpg'),
(5354, 'produk_Image4', 'produk_Image4.jpeg'),
(5377, 'Button_teks', '<h1>Trusted<br />since 1986</h1>'),
(5397, 'Menus_teks', '<h1>BEST FOOD SERVED AT THE BEST MOMENT</h1>\r\n<div>take a peek at our menu</div>'),
(5416, 'Menus_teks1', '<h1>BEST FOOD SERVED AT THE BEST MOMENT</h1>\r\n<div>&nbsp;</div>'),
(5417, 'Menus_teks2', '<p>take a peek at our menu</p>'),
(5438, 'Maps_Image', '<p><iframe style=\"border: 0;\" src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.5620774266886!2d112.75286831379724!3d-7.290561673680422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbb6c69cc20f%3A0x974e76b47b71cbb6!2sFiesto%20Informatika%20Indonesia!5e0!3m2!1sid!2sid!4v1679387660729!5m2!1sid!2sid\" width=\"100%\" height=\"450\" allowfullscreen=\"allowfullscreen\"></iframe></p>'),
(5439, 'Maps_teks1', ''),
(5483, 'Maps_teks', '<p>Jl Ngagel Jaya No. 99, Surabaya<br />(031)2828429810<br />masterjkw20@gmail.com</p>'),
(5528, 'Hidden_teks', '<p>Jl Ngagel Jaya No. 99, Surabaya<br />(031)2828429810<br />masterjkw20@gmail.com</p>'),
(5563, 'galery_Image1', 'galery_Image1.jpeg'),
(5564, 'galery_Image2', 'galery_Image2.jpeg'),
(5565, 'galery_Image3', 'galery_Image3.jpeg'),
(5566, 'galery_Image4', 'galery_Image4.jpeg'),
(5567, 'galery_Image5', 'galery_Image5.jpeg'),
(5568, 'galery_Image6', 'galery_Image6.jpeg'),
(5581, 'teks_bawah', '<p>2023-SITE BY FIESTO</p>');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `template_data`
--
ALTER TABLE `template_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `param_unik` (`param`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `template_data`
--
ALTER TABLE `template_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5689;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
