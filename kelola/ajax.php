<?php
Header('Cache-Control: no-cache, must-revalidate');
Header('Pragma: no-cache');
session_start();

include ("urasi.php");
include ("fungsi.php");
include ("lang/$lang/definisi.php");

$aksi=$_GET['action'];

if($aksi=="urutan")
{	$htmlajax = "";
	$modul = $_GET['p'];
	$parent_id = $_GET['parent'];
	$id = $_GET['pid'];
	
	$htmlajax .= createurutan($modul, $parent_id, $id);
}

if($aksi=="video_awal")
{
$check=$_GET['check']==true?1:0;
$Month = 2592000 + time();
setcookie("disable_video_awal_".$domainid,$check,$Month);
}

if($aksi=="video_step")
{
$check=$_GET['check']==true?1:0;
$step=$_GET['step']==true?1:0;
$Month = 2592000 + time();
setcookie("disable_video_{$step}_".$domainid,$check,$Month);

}

if($aksi == "auto_complete_customer")
{
	$keyword = $_GET['keyword'];
	$sql = "SELECT username, fullname FROM webmember WHERE fullname LIKE '%".$keyword."%' ORDER BY fullname LIMIT 10";
	$result = $mysql->query($sql);
	while($data = $mysql->fetch_assoc($result))
	{
		$temp[] = array("id" => $data['username'], "value" => $data['fullname']);
	}
	$htmlajax = json_encode($temp);
}

if($aksi == "auto_complete_kode")
{
	$keyword = $_GET['keyword'];
	$sql = "SELECT b.kode_transaksi, 
			CONCAT(b.kode_transaksi, ' - ', c.kode_produk, ' - ', a.fullname) AS value
			FROM webmember a
			INNER JOIN topup b ON a.username=b.username
			INNER JOIN produk c ON b.id_produk=c.id
			WHERE b.kode_transaksi LIKE '%".$keyword."%' 
			OR a.username LIKE '%".$keyword."%'
			OR a.fullname LIKE '%".$keyword."%'
			OR c.kode_produk LIKE '%".$keyword."%'
			ORDER BY b.id DESC LIMIT 10";
	$result = $mysql->query($sql);
	while($data = $mysql->fetch_assoc($result))
	{
		$temp[] = array("id" => $data['kode_transaksi'], "value" => $data['value']);
	}
	$htmlajax = json_encode($temp);
}

if($aksi == "auto_complete_transid")
{
	$keyword = $_GET['keyword'];
	$sql = "SELECT t.transaction_id, 
			CONCAT(t.transaction_id, ' - ', a.fullname) AS value
			FROM webmember a
			INNER JOIN sc_customer b ON a.user_id=b.web_user_id
			INNER JOIN sc_transaction t ON b.customer_id=t.customer_id
			WHERE t.transaction_id LIKE '%".$keyword."%' 
			OR a.username LIKE '%".$keyword."%'
			OR a.fullname LIKE '%".$keyword."%'
			ORDER BY t.transaction_id DESC LIMIT 10";
	$result = $mysql->query($sql);
	while($data = $mysql->fetch_assoc($result))
	{
		$temp[] = array("id" => $data['transaction_id'], "value" => $data['value']);
	}
	$htmlajax = json_encode($temp);
}

echo $htmlajax;
?>
