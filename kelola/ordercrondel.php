<?php
session_start();
header("Cache-control: private");

include ("urasi.php");
include ("fungsi.php");
include ("../modul/order/urasi.php");

if (!$_SESSION['s_userid']) die();

$now = date('Y-m-d H:i:s');

$sql = "SELECT transaction_id, customer_id, receiver_id FROM sc_transaction WHERE DATE_ADD(tanggal_buka_transaksi, INTERVAL '" . $hapusorder . "' DAY) < '$now' AND status<=1";
$result = $mysql->query($sql);
while (list($txid,$cid,$rid)=$mysql->fetch_row($result)) {

	$sql1 = "SELECT * FROM `sc_transaction_detail` WHERE transaction_id = '$txid'";
	$result1 = $mysql->query($sql1);
	$logdata = '';
	while ($row=$mysql->fetch_row($result1)) {
		$baris = date('Y-m-d H:i:s');
		foreach ($row as $kolom) {
			$baris .= "\t$kolom";
		}
		$baris .= "\r\n";
		$logdata .= $baris;
	}
	fiestolog($logdata,'sc_transaction_detail_del.txt');
	$sql = "DELETE FROM `sc_transaction_detail` WHERE transaction_id = '$txid'";
	//$del .= "<li>$sql</li>";
	$mysql->query($sql);
	
	
	$sql1 = "SELECT * FROM `sc_transaction` WHERE transaction_id = '$txid'";
	$result1 = $mysql->query($sql1);
	$logdata = '';
	while ($row=$mysql->fetch_row($result1)) {
		$baris = date('Y-m-d H:i:s');
		foreach ($row as $kolom) {
			$baris .= "\t$kolom";
		}
		$baris .= "\r\n";
		$logdata .= $baris;
	}
	fiestolog($logdata,'sc_transaction_del.txt');
	$sql = "DELETE FROM `sc_transaction` WHERE transaction_id = '$txid'";
	//$del .= "<li>$sql</li>";
	$mysql->query($sql);
	
	$sql1 = "SELECT * FROM `sc_receiver` WHERE receiver_id = '$rid'";
	$result1 = $mysql->query($sql1);
	$logdata = '';
	while ($row=$mysql->fetch_row($result1)) {
		$baris = date('Y-m-d H:i:s');
		foreach ($row as $kolom) {
			$baris .= "\t$kolom";
		}
		$baris .= "\r\n";
		$logdata .= $baris;
	}
	fiestolog($logdata,'sc_receiver_del.txt');
	$sql = "DELETE FROM `sc_receiver` WHERE receiver_id = '$rid'";
	//$del .= "<li>$sql</li>";
	$mysql->query($sql);
	
	$sql1 = "SELECT * FROM `sc_customer` WHERE customer_id = '$cid'";
	$result1 = $mysql->query($sql1);
	$logdata = '';
	while ($row=$mysql->fetch_row($result1)) {
		$baris = date('Y-m-d H:i:s');
		foreach ($row as $kolom) {
			$baris .= "\t$kolom";
		}
		$baris .= "\r\n";
		$logdata .= $baris;
	}
	fiestolog($logdata,'sc_customer_del.txt');
	$sql = "DELETE FROM `sc_customer` WHERE customer_id = '$cid'";
	//$del .= "<li>$sql</li>";
	$mysql->query($sql);
}

//echo "<ul>$delx</ul>";

?>