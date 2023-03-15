<?php

$cfg_framed = FALSE;		//frame on=1 off=0
$cfg_framewidth = "30";		//lebar frame (pixel)
$cfg_framefiletype = "jpg";	//tipe file frame

$cfg_fullsizepics_path = "$cfg_img_path/catalog/large";
$cfg_fullsizepics_url = "$cfg_img_url/catalog/large";
$cfg_thumb_path = "$cfg_img_path/catalog/small";
$cfg_thumb_url = "$cfg_img_url/catalog/small";
$cfg_frame_url = "$cfg_img_url/catalog/frame";

$cfg_temp_unzip_path = "$cfg_img_path/catalog/zip";

$topcatnamecombo = "";		//Name of top level category
$separatorstyle = " / ";	//Categories separator in categories navigation at the top part of the page
$maxfilesize = 1000000;		//Max file size allowed to be uploaded
$ispickerused = FALSE;		//IS date time picker javascript used? --> hanya untuk field tambahan
$autoscrolltime = 3; // dalam detik

$sql = "SELECT judulfrontend FROM module WHERE nama='catalog' ";
$result = mysql_query($sql);
list($topcatnamenav) = mysql_fetch_row($result);

$sql = "SELECT name, value FROM config WHERE modul='catalog' ";
$result = mysql_query($sql);
while (list($name, $value) = mysql_fetch_row($result)) {
	$$name=$value;
}

switch($sorttype)
{	case 0 :	$sort = "title ASC";
				break;
	case 1 :	$sort = "harganormal ASC";
				break;
	case 2 :	$sort = "harganormal DESC";
				break;
	case 3 :	$sort = "hargadiskon ASC";
				break;
	case 4 :	$sort = "hargadiskon DESC";
				break;
}




/**********************************************
CUSTOM FIELDS

Mata Uang

	Dollar:
		$typecustom[n] = 'DOLLAR';
		$paracustom[n] = '';

	Rupiah:
		$typecustom[n] = 'RUPIAH';
		$paracustom[n] = '';

Bilangan desimal (Contoh: berat dalam kilogram --> bisa koma)

	$typecustom[n] = 'DECIMAL';
	$paracustom[n] = 'M,D';

		Dimana:
		M = Panjang digit maksimal (termasuk titik desimal dan angka di belakang koma, tidak termasuk tanda minus jika ada)
		D = Jumlah angka di belakang koma

Bilangan bulat (Contoh: jumlah penonton)

	Jika mungkin negatif:

		-128 sampai 127
			$typecustom[n] = 'TINYINT';
			$paracustom[n] = '';

		-32.768 sampai 32.767
			$typecustom[n] = 'SMALLINT';
			$paracustom[n] = '';

		-8.388.608 sampai 8.388.607
			$typecustom[n] = 'MEDIUMINT';
			$paracustom[n] = '';

		-2.147.483.648 sampai 2.147.483.647
			$typecustom[n] = 'INT';
			$paracustom[n] = '';

	Jika tidak mungkin negatif:

		0 sampai 255
			$typecustom[n] = 'TINYINT';
			$paracustom[n] = 'UNSIGNED';

		0 sampai 65.535
			$typecustom[n] = 'SMALLINT';
			$paracustom[n] = 'UNSIGNED';

		0 sampai 16.777.215
			$typecustom[n] = 'MEDIUMINT';
			$paracustom[n] = 'UNSIGNED';

		0 sampai 4.294.967.295 
			$typecustom[n] = 'INT';
			$paracustom[n] = 'UNSIGNED';

String untuk combo box (Disediakan pilihan. Contoh: "Pria/Wanita")

	$typecustom[n] = 'ENUM';
	$paracustom[n] = 'value1/value2/.../valueX';

String untuk input box (User isi sendiri. Contoh: nama, nomor telepon)

	$typecustom[n] = 'VARCHAR';
	$paracustom[n] = 'M';

		Dimana:
		M = Panjang digit (maksimum 255)

String untuk text area (Contoh: keterangan yang panjangnya bisa sampai 65.535 karakter)

	$typecustom[n] = 'TEXT';
	$paracustom[n] = '';
 
Tanggal

	$typecustom[n] = 'DATE';
	$paracustom[n] = '';

Tanggal dan jam

	$typecustom[n] = 'DATETIME';
	$paracustom[n] = '';
 
**********************************************/

$customfield[]='ketsingkat';
$ketcustomfield[]='Keterangan luar';
$typecustom[]='TEXT';
$paracustom[]='60,10,usetiny';

$customfield[]='keterangan';
$ketcustomfield[]='Keterangan dalam';
$typecustom[]='TEXT';
$paracustom[]='60,20,usetiny';

$customfield[]='matauang';
$ketcustomfield[]='Mata Uang';
$typecustom[]='ENUM';
$paracustom[]='Rp/USD';

$customfield[]='harganormal';
$ketcustomfield[]='Harga Normal';
$typecustom[]='DECIMAL';
$paracustom[]='13,2';

$customfield[]='diskon';
$ketcustomfield[]='Diskon';
$typecustom[]='VARCHAR';
$paracustom[]='17';

$customfield[]='tag';
$ketcustomfield[]='Tag';
$typecustom[]='VARCHAR';
$paracustom[]='500';

/*
$customfield[]='dimensi';
$ketcustomfield[]='Dimensi';
$typecustom[]='VARCHAR';
$paracustom[]='30';

$customfield[]='harga';
$ketcustomfield[]='Harga';
$typecustom[]='RUPIAH';
$paracustom[]='';

$customfield[]='tanggal';
$ketcustomfield[]='Tanggal';
$typecustom[]='DATE';
$paracustom[]='';
*/

/**********************************************
SEARCH FIELD

Array is independent --> has no relationship with custom fields array!!!

**********************************************/

$searchedcf[]='keterangan';
$searchedcf[]='tag';


?>
