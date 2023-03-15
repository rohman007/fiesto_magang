<?php
header("Content-type: text/xml"); 
include ("kelola/urasi.php");
include ("kelola/fungsi.php");
$urlfunc = new PrettyURL();

$tanggal = date('Y-m-d');
$mapsql = "SELECT nama FROM module ORDER BY judulfrontend";

//query pertama untuk inisialisasi semua variabel $GLOBAL (terkait pretty)
//jika query digabung, saat di brand, yang dideklarasikan adalah $GLOBALS['action_brand'] padahal jika brand diklik maunya ngelink ke p=catalog
//sedangkan brand dipanggil sebelum catalog, jadi $GLOBALS['action_catalog'] belum ada isinya sehingga muncul warning
$mapresult = $mysql->query($mapsql);
while (list($namamodul)=$mysql->fetch_row($mapresult)) {
	if (file_exists("$cfg_app_path/modul/$namamodul/prettyurasi.php")) include("$cfg_app_path/modul/$namamodul/prettyurasi.php");
}
//query kedua untuk jalankan sitemap.php di masing2 modul
$mapresult = $mysql->query($mapsql);
while (list($namamodul)=$mysql->fetch_row($mapresult)) {
	if (file_exists("$cfg_app_path/modul/$namamodul/sitemap.php")) include("$cfg_app_path/modul/$namamodul/sitemap.php");
}
echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.9" 
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
	xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.9 
	http://www.google.com/schemas/sitemap/0.9/sitemap.xsd">
';
echo $xml_standalone;
echo $xml_others;
echo '</urlset>';
?>