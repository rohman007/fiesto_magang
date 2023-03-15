<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$keyword = fiestolaundry($_GET['keyword'], 100);
$screen = fiestolaundry($_GET['screen'], 11);
if ($screen == '') {
    $screen = 0;
}

$keterangan = fiestolaundry($_POST['keterangan'], 65536, TRUE);
$name = fiestolaundry($_POST['name'], 100, TRUE);
$action = fiestolaundry($_REQUEST['action'], 20);
$pid = fiestolaundry($_REQUEST['pid'], 20);

if ($action == "ubahdata") {
    $name = checkrequired($name, _NAME);
    $sql = "INSERT INTO sc_payment(id,nama,deskripsi) values ('$pid','$name','$keterangan') ON DUPLICATE KEY UPDATE  nama='$name', 
			deskripsi='$keterangan' ";

    $result = mysql_query($sql);
    if ($result) {
        pesan(_SUCCESS, _DBSUCCESS, "?p=sc_payment&r=$random");
    } else {
        pesan(_ERROR, _DBERROR);
    }
    $action = "modify";
    $pid = 1;
}

if ($action == "modify") {
    $admintitle = _EDITPAYMENT;
    $sql = "SELECT  id, nama, deskripsi, gambar FROM sc_payment WHERE id='$pid'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        list($id, $nama, $keterangan, $filename) = mysql_fetch_row($result);
    }


    if ($filename != '')
        $admincontent .= '<img src="' . $cfg_thumb_url . '/' . $filename . '" alt="' . $title . '">';

    $admincontent .= '
		<form method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
		  <input type="hidden" name="action" value="ubahdata">
		  <input type="hidden" name="pid" value="' . $pid . '">
		  <textarea class="usetiny" cols="60" rows="20" name="keterangan">' . $keterangan . '</textarea>
		  <div><input type="submit" name="submit" class="buton" value="' . _SAVE . '"></div>
			
		</form>
		';
}
?>
