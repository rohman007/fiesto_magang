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
$action = fiestolaundry($_REQUEST['action'], 10);
$pid = fiestolaundry($_REQUEST['pid'], 11);

$kotaasal = 'Surabaya'; /* fiestolaundry($_POST['kotaasal'], 50); */
$kotatujuan = fiestolaundry($_POST['kotatujuan'], 50);
$kode = fiestolaundry($_POST['kode'], 8);
$code = fiestolaundry($_POST['code'], 16);
$tipe = fiestolaundry($_POST['tipe'], 50);
$waktu_kirim = fiestolaundry($_POST['waktu_kirim'], 50);
$biaya = fiestolaundry($_POST['biaya'], 13);

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    header("Location:?p=$modulename");
}

if ($action == "tambahdata") {
	$notif = '';
	$notif .= validation($kotaasal, _KOTAASAL, false);
	$notif .= validation($kotatujuan, _KOTATUJUAN, false);
	$notif .= validation($kode, _KODE, false);
	$notif .= validation($code, _CODE, false);
	$notif .= validation($tipe, _TIPE, false);
    // $kotaasal = checkrequired($kotaasal, _KOTAASAL);
    // $kotatujuan = checkrequired($kotatujuan, _KOTATUJUAN);
	if ($notif != '') {
		$action = createmessage($notif, _ERROR, "error", "add");
	} else {
		$sql = "INSERT INTO sc_courier_jne (courier_id, asal, kode, code, tujuan,tipe, waktu_kirim, biaya) VALUES (1,'$kotaasal', '$kode', '$code', '$kotatujuan','$tipe', '$waktu_kirim', '$biaya')";
		$result = $mysql->query($sql);
		if ($result) {
			$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
		} else {
			$action = createmessage(_DBERROR, _ERROR, "error", "add");
		}
	}
}

if ($action == "add") {
    $admintitle = _ADDPOSTAGE;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
	  <input type="hidden" name="action" value="tambahdata">
		  <div class="control-group">
			<label class="control-label">' . _KODE . '</label>
			<div class="controls"><input type="text" name="kode"></div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _KOTATUJUAN . '</label>
			<div class="controls"><input type="text" name="kotatujuan"></div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _CODE . '</label>
			<div class="controls"><input type="text" name="code"></div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _TIPE . ':</label>
			<div class="controls"><select name="tipe">'.get_dropdown().'</select></div>
		  </div>
	      <div class="control-group">
	        <label class="control-label">' . _WAKTUKIRIM . ':</label>
	        <div class="controls"><input type="text" name="waktu_kirim" size="10"></div>
          </div>
	      <div class="control-group">
	        <label class="control-label">' . _BIAYA . ':</label>
	        <div class="controls"><input type="text" name="biaya" value="0" size="10"></div>
	      </div>
		  <div class="control-group">
			<div class="controls">
			  <input type="submit" name="submit" class="buton"  value="' . _ADD . '">
			  <input type="submit" name="back" class="buton" value="' . _BACK . '">
			</div>
		  </div>
	</form>
    ';
}


if ($action == "ubahdata") {
	$notif = '';
	$notif .= validation($kotaasal, _KOTAASAL, false);
	$notif .= validation($kotatujuan, _KOTATUJUAN, false);
	$notif .= validation($kode, _KODE, false);
	$notif .= validation($code, _CODE, false);
	$notif .= validation($tipe, _TIPE, false);
	if ($notif != '') {
		$action = createmessage($notif, _ERROR, "error", "add");
	} else {
		// $kotaasal = checkrequired($kotaasal, _KOTAASAL);
		// $kotatujuan = checkrequired($kotatujuan, _KOTATUJUAN);
		$sql = "UPDATE sc_courier_jne SET asal='$kotaasal',kode='$kode',tujuan='$kotatujuan',code='$code',tipe='$tipe',waktu_kirim='$waktu_kirim', biaya=$biaya WHERE id='$pid'";
		$result = $mysql->query($sql);
		if ($result) {
			$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
		} else {
			$action = createmessage(_DBERROR, _ERROR, "error", "add");
		}
	}
}

if ($action == "modify") {
    $admintitle = _EDITPOSTAGE;
    $sql = "SELECT id, asal, kode, code, tujuan,tipe, waktu_kirim, biaya FROM sc_courier_jne WHERE id='$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        pesan(_ERROR, _NOPOSTAGE);
    } else {
        list($id, $asal, $kode, $code, $tujuan, $tipe, $waktu_kirim, $biaya) = $mysql->fetch_row($result);

        if ($filename != '')
            $admincontent .= '<img src="' . $cfg_thumb_url . '/' . $filename . '" alt="' . $title . '">';

        $admincontent .= '
		<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
		  <input type="hidden" name="action" value="ubahdata">
		  <input type="hidden" name="pid" value="' . $pid . '">			
			  <div class="control-group">
				<label class="control-label">' . _KODE . '</label>
				<div class="controls"><input type="text" name="kode" value="' . $kode . '" ></div>
			  </div>
			  <div class="control-group">
				<label class="control-label">' . _KOTATUJUAN . '</label>
				<div class="controls"><input type="text" name="kotatujuan" value="' . $tujuan . '" ></div>
			  </div>
			  <div class="control-group">
				<label class="control-label">' . _CODE . '</label>
				<div class="controls"><input type="text" name="code" value="' . $code . '" ></div>
			  </div>
			  <div class="control-group">
				<label class="control-label">' . _TIPE . ':</label>
				<div class="controls"><select name="tipe">'.get_dropdown($tipe).'</select></div>
			  </div>
			  <div class="control-group">
				<label class="control-label">' . _WAKTUKIRIM . ':</label>
				<div class="controls"><input type="text" name="waktu_kirim" value="' . $waktu_kirim . '" size="10"></div>
			  </div>
			   <div class="control-group">
				<label class="control-label">' . _BIAYA . ':</label>
				<div class="controls"><input type="text" name="biaya" value="' . $biaya . '" size="10"></div>
			  </div>
			  <div class="control-group">
			    <div class="controls">
			      <input type="submit" name="submit" class="buton"  value="' . _SAVE . '">
			      <input type="submit" name="back" class="buton" value="' . _BACK . '">
			    </div>
			  </div>
		</form>
		';
    }
}

if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $admintitle = _DELPOSTAGE;
    $sql = "SELECT id FROM sc_courier_jne WHERE id = '$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        pesan(_ERROR, _NOPOSTAGE);
    } else {
        list($id, $filename) = $mysql->fetch_row($result);
        if ($action == "remove") {
            $admincontent .= _PROMPTDEL;
            $admincontent .= "<a class=\"buton\" href=\"?p=$module_name&action=delete&pid=$pid\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
        } else {
            $sql = "DELETE FROM sc_courier_jne WHERE id='$pid'";
            $result = $mysql->query($sql);
			if ($result) {
				$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
			} else {
				$action = createmessage(_DBERROR, _ERROR, "error", "add");
			}
        }
    }
}

if ($action == 'search') {
    $admintitle = _SEARCHRESULTS;

    $sql = "SELECT id FROM sc_courier_jne WHERE asal LIKE '%$keyword%' OR tujuan LIKE '%$keyword%' OR kode LIKE '%$keyword%' OR code LIKE '%$keyword%'";
	
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) > 0) {
        $admintitle = _SEARCHRESULTS;
        $start = $screen * $max_page_list;
        $sql = "SELECT id, asal, kode, code, tujuan,tipe, waktu_kirim, biaya FROM sc_courier_jne 
			WHERE asal LIKE '%$keyword%' OR tujuan LIKE '%$keyword%' OR kode LIKE '%$keyword%' OR code LIKE '%$keyword%'
			ORDER BY id LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen, "action=search&keyword=$keyword");
		
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _NOMOR . "</th>";
        $admincontent .= "<th>" . _KOTAASAL . "</th>";
        $admincontent .= "<th>" . _KODE . "</th>";
        $admincontent .= "<th>" . _KOTATUJUAN . "</th>";
        $admincontent .= "<th>" . _CODE . "</th>";
        $admincontent .= "<th>" . _TIPE . "</th>";
        $admincontent .= "<th>" . _WAKTUKIRIM . "</th>";
        $admincontent .= "<th>" . _BIAYA . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        $no_urut = $start;
        while (list($id, $asal, $kode, $code, $tujuan, $tipe, $waktu_kirim, $biaya) = $mysql->fetch_row($result)) {
            $no_urut++;
            $admincontent .= "<td align=\"center\">$no_urut</td>";
            $admincontent .= "<td>$asal</td>";
            $admincontent .= "<td>$kode</td>";
            $admincontent .= "<td>$tujuan</td>";
            $admincontent .= "<td>$code</td>";
            $admincontent .= "<td>$tipe</td>";
            $admincontent .= "<td>$waktu_kirim</td>";
            $admincontent .= "<td align=\"right\">" . number_format($biaya, 0, ',', '.') . "</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\"><a href=\"?p=$module_name&action=modify&pid=$id\">";
            $admincontent .= "<img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a>\n";
            $admincontent .= "<a href=\"?p=$module_name&action=remove&pid=$id\">";
            $admincontent .= "<img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        $admincontent .= "<p>" . _NOSEARCHRESULTS . "</p>";
    }
}

if ($action == '') {
    $sql = "SELECT id FROM sc_courier_jne";

    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);
    if ($mysql->num_rows($result) == "0") {
        $admincontent .= _NOPOSTAGE;
    } else {
        $start = $screen * $max_page_list;

        $sql = "SELECT id, asal, kode, code, tujuan,tipe, waktu_kirim, biaya FROM sc_courier_jne ORDER BY id LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen, "");
			
		$admincontent .= "<p><a class=\"buton\" href=\"?p=$module_name&action=add\">" . _ADDPOSTAGE . "</a></p>";
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _NOMOR . "</th>";
        $admincontent .= "<th>" . _KOTAASAL . "</th>";
        $admincontent .= "<th>" . _KODE . "</th>";
        $admincontent .= "<th>" . _KOTATUJUAN . "</th>";
        $admincontent .= "<th>" . _CODE . "</th>";
        $admincontent .= "<th>" . _TIPE . "</th>";
        $admincontent .= "<th>" . _WAKTUKIRIM . "</th>";
        $admincontent .= "<th>" . _BIAYA . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
		$no_urut = $start;
        while (list($id, $asal, $kode, $code, $tujuan, $tipe, $waktu_kirim, $biaya) = $mysql->fetch_row($result)) {
            $no_urut++;
            $admincontent .= "<td align=\"center\">$no_urut</td>";
            $admincontent .= "<td>$asal</td>";
            $admincontent .= "<td>$kode</td>";
            $admincontent .= "<td>$tujuan</td>";
            $admincontent .= "<td>$code</td>";
            $admincontent .= "<td>$tipe</td>";
            $admincontent .= "<td>$waktu_kirim</td>";
            $admincontent .= "<td align=\"right\">" . number_format($biaya, 0, ',', '.') . "</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\"><a href=\"?p=$module_name&action=modify&pid=$id\">";
            $admincontent .= "<img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a>\n";
            $admincontent .= "<a href=\"?p=$module_name&action=remove&pid=$id\">";
            $admincontent .= "<img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    }
    $admincontent .= "<p>"._RUMUSONGKIR."</p>";
    $specialadmin = "<a class=\"buton\" href=\"?p=$module_name&action=add\">" . _ADDPOSTAGE . "</a>";
}

// if ($specialadmin == '')
    // $specialadmin = "<a class=\"buton\" href=\"?p=module_name\">" . _BACK . "</a>";
?>