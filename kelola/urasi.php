<?php

error_reporting(1);
/*********** BAGIAN A ***********/
//LOKASI SERVER
$serverlocation = "id";	//id,net,off
$sandbox = true;
$idpaketwebsite = 2;	//1 toko online 2 non toko


//URL DAN PATH
$htdocspath = $_SERVER['DOCUMENT_ROOT'];	//Tanpa tanda slash (/) di belakang
$httpdomain = "http://".$_SERVER['HTTP_HOST'];//http:www.example.com	//Tanpa tanda slash (/) di belakang (kalo pake ~ taro disini sekalian)

$subdir = "/iwd/fiesto_magang";	

if (!$sandbox) {
	$cfg_host = 'localhost';	// MySQL hostname
	$cfg_user = 'root';			// MySQL username
	$cfg_pass = '';			// MySQL password
	$cfg_db = 'fiesto_magang';		// MySQL database
} else {
	$cfg_host = 'localhost';		// MySQL hostname
	$cfg_user = 'root';				// MySQL username
	$cfg_pass = '';					// MySQL password
	$cfg_db = 'fiesto_magang';		// MySQL database
}

$cfg_app_path = $htdocspath.$subdir;
$cfg_app_url = $httpdomain.$subdir;

$cfg_file_path = $htdocspath.$subdir;
$cfg_file_url = $httpdomain.$subdir;
////////////////////////

//PHPMAILER
$smtpsecure = 'ssl';	//kosongkan jika pakai protokol normal (Non SSL)
$smtphost = 'mail.wolawola.com';
$smtpport = '465';
$smtpuser = 'abrakadabra@wolawola.com';
$smtppass = 'Mr.Wolaisindahouse-2014';
$smtpsendername = 'J.One Jewelry';

//DESAIN CUSTOM ATAU STANDARD TEMPLATE
$iscustomdesign = false;	//jika custom maka link ke template di-del

//OPTIONAL PLUGIN
$belimobileversion = false;
$pakaimarketplaceurl = true;
$pakaicatalogattribut = true;
$pakaislug = false;
$pakaifbpixel = false;

// AMP 
// url to amp version
$amp_domain = 'amp.iphschools.sch.id';
if ($_SERVER['HTTP_HOST'] == $amp_domain) {
	$pakaiamp = false;
}

if ($pakaicatalogattribut) {
	/**
	* Config attribute option
	*
	* 0 => Pakai Ukuran dan Warna
	* 1 => Pakai Ukuran
	* 2 => Pakai Warna
	*/
	$catalog_attribute_option = 1;
}

if ($idpaketwebsite == 1) {
	//SHOPPING CART
	$pakaicart = true;	//true = ada shopping cart, false = katalog saja
	$pakaiongkir = true;	//true = pakai ongkir, false = tanpa ongkir
	$pakaiwishlist = false;
	$pakaivoucher = false;
	$pakaiadditional = false;
	$pakaistock = false;
	$pakaikurs = false;
	$harusmember = 2;	//0 = tanpa membership,1 = pembeli harus jadi member,2=tanpa member atau bisa jadi member
} else{
	//NON TOKO ONLINE CART
	$pakaicart = false;	//true = ada shopping cart, false = katalog saja
	$pakaiongkir = false;	//true = pakai ongkir, false = tanpa ongkir
	$harusmember = 0;	//0 = tanpa membership,1 = pembeli harus jadi member,2=tanpa member atau bisa jadi member
}
//CONTACT FORM
$okaydomains=array('localhost'); //tanpa www

//$httpdomain = "http://$httpdomain";

///////////////////////////////////////////////////////////////////////////
define('BASE_URL', "$cfg_app_path/file/media");
define('DIR_IMAGES', "$cfg_app_path/file/media");
define('DIR_FILES', "$cfg_app_path/file/download");
include("$cfg_app_path/aplikasi/phpmailer/class.phpmailer.php");
include("$cfg_app_path/aplikasi/phpmailer/class.smtp.php"); // note, this is optional - gets called from main class if not already loaded

//Selisih jam server dengan WIB. 12 jika fiesto.net, 0 jika fiesto.co.id
switch ($serverlocation) {
	case 'id':	//SERVER ID
		$serverhouroffset = "0";
		$cfg_ftp_host = "ftp.indonesiawebdesign.com";
		$cfg_ftp_user = "template@indonesiawebdesign.com";
		$cfg_ftp_pass = "6l@JdeJwy)wm";
		$iwdurl = "http://www.indonesiawebdesign.com";
		$iwdpath = "/home/iwdsip/public_html";
		break;

	case 'net':	//SERVER US
		$serverhouroffset = "12";
		$cfg_ftp_host = "ftp.fiesto.net";
		$cfg_ftp_user = "template@fiesto.net";
		$cfg_ftp_pass = "6l@JdeJwy)wm";
		$iwdurl = "http://www.indonesiawebdesign.com";
		$iwdpath = "/home/iwdsip/public_html";
		break;

	case 'off':	//FIESTO.OFF
		$serverhouroffset = "0";
		$cfg_ftp_host = "192.168.24.11";
		$cfg_ftp_user = "template";
		$cfg_ftp_pass = "asdfg";
		$iwdurl = "http://fiesto.off/iwd/iwd";
		$iwdpath = "/home/fiesto/web/htdocs/iwd/iwd";

		break;
}


$template_selector_url = "$iwdurl/servicefiles/showpremiumtemplates.php";
$iwd_news_url = "$iwdurl/servicefiles/showpremiumnews.php";
$template_collection_path = "$iwdpath/~templates";

include "mysql.php";

$mysql = new Mysqli_db($cfg_host,$cfg_user,$cfg_pass,$cfg_db);
$mysql->query("SET sql_mode = '' ");
$mysql->set_charset("utf8"); 

// SET FOREIGN_KEY_CHECKS = 0; 
// truncate kegiatan;
// SET FOREIGN_KEY_CHECKS = 1;


/* $mysqli = new mysqli($cfg_host, $cfg_user, $cfg_pass, $cfg_db);
if ($mysqli->connect_errno) {
    printf("FATAL ERROR: %s\n", $mysqli->connect_error);
    exit();
}

if (!mysql_connect($cfg_host, $cfg_user, $cfg_pass)) die("FATAL ERROR: Setting user/pass atau hostname MySQL salah");
if (!mysql_select_db($cfg_db)) die("FATAL ERROR: Berhasil konek ke MySQL namun database $cfg_db tidak ada"); */


$cfg_img_url = "$cfg_file_url/file";
$cfg_img_path = "$cfg_file_path/file";
$absolutadminurl = "$cfg_app_url/kelola";

$lang = 'id';
$namabulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

$gaurl = "https://www.google.com/analytics/reporting/login";
$max_page_list = 20;	//max list untuk istrasi.php semua modul

$sql = "SELECT name, value, modul FROM config WHERE modul='site'";
$result = $mysql->query($sql);
while (list($name, $value) = $mysql->fetch_row($result)) {
	$value = addslashes($value);
	eval('$config_site_'.$name.'=stripslashes($value);');
}

//overwrite config ambil dari konfigurasi tbl config
if($config_site_harusmember!=''){$harusmember=$config_site_harusmember;}
//end overwrite config ambil dari konfigurasi tbl config
if(isset($_SESSION['config_site_templatefolder']))
{
$config_site_templatefolder=$_SESSION['config_site_templatefolder'];
}
$cfg_template_url="$cfg_app_url/template/$config_site_templatefolder";
$favicon = "$cfg_file_url/file/favicon.$config_site_favicon";
$cfg_favicon_path = "$cfg_app_path/userfiles/$domainid/file";
$cfg_favicon_url = "$cfg_app_url/userfiles/$domainid/file";
include ("$cfg_app_path/template/$config_site_templatefolder/urasi.php");


$id_separator = "_";


//TINIMCE image plugin
define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']);	//Site root dir
define('CLASS_LINK', 'lightview');	//Additional attributes class
define('REL_LINK', 'lightbox[iwd]');	//Additional attributes rel
define('WIDTH_TO_LINK', $cfg_max_width);	//$cfg_max_width ada di urasi masing2 template
define('HEIGHT_TO_LINK', $cfg_max_width);	//$cfg_max_width ada di urasi masing2 template

#region jquery
// $admincontent .= '
// <script>
	// $(document).ready(function()
	// {
		// $("#auto-complete-customer").autocomplete(
		// {
			// source: function(request, response)
			// {
				// var keyword = $("#auto-complete-customer").val();
				// $.ajax(
				// {
					// url: "ajax.php",
					// type: "GET",
					// data: {action : "auto_complete_customer", keyword : keyword},
					// success: function(data)
					// {
						// var customer_list = JSON.parse(data);
						// response(customer_list);
					// }
				// });
			// },
			// select: function(event, ui) 
			// {
				// $("#username-customer").val(ui.item.id);
			// }
		// });
		
		// $("#auto-complete-kode").autocomplete(
		// {
			// source: function(request, response)
			// {
				// var keyword = $("#auto-complete-kode").val();
				// $.ajax(
				// {
					// url: "ajax.php",
					// type: "GET",
					// data: {action : "auto_complete_kode", keyword : keyword},
					// success: function(data)
					// {
						// var code_list = JSON.parse(data);
						// response(code_list);
					// }
				// });
			// },
			// select: function(event, ui) 
			// {
				// $("#kode-transaksi").val(ui.item.id);
			// }
		// });

		// $("#auto-complete-transid").autocomplete(
		// {
			// source: function(request, response)
			// {
				// var keyword = $("#auto-complete-transid").val();
				// $.ajax(
				// {
					// url: "ajax.php",
					// type: "GET",
					// data: {action : "auto_complete_transid", keyword : keyword},
					// success: function(data)
					// {
						// var code_list = JSON.parse(data);
						// response(code_list);
					// }
				// });
			// },
			// select: function(event, ui) 
			// {
				// $("#kode-transaksi").val(ui.item.id);
			// }
		// });
	// });
// </script>';
#endregion jquery
?>
