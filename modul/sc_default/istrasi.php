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

$pid = fiestolaundry($_REQUEST['pid'], 11);

// $kotaasal = 'Surabaya'; /* fiestolaundry($_POST['kotaasal'], 50); */
// $kotatujuan = fiestolaundry($_POST['kotatujuan'], 50);
// $tipe = fiestolaundry($_POST['tipe'], 11);
// $waktu_kirim = fiestolaundry($_POST['waktu_kirim'], 50);
// $biaya = fiestolaundry($_POST['biaya'], 13);

$kota = fiestolaundry($_POST['kota'], 50);
$ongkos = fiestolaundry($_POST['ongkos'], 15);
$ongkostetap = fiestolaundry($_POST['ongkostetap'], 15);
$action = fiestolaundry($_REQUEST['action'], 20);

$modulename = $_GET['p'];

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        // $notificationbuilder .= validation($kota, _KOTA, false);
        // $notificationbuilder .= validation($ongkostetap, _ONGKOSTETAP, true);
        // $notificationbuilder .= validation($ongkos, _ONGKOS, true);
        // $notificationbuilder .= validation($waktukirim, _WAKTUKIRIM, true);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            $sql = "INSERT INTO sc_postage (kota, ongkos, ongkostetap) VALUES ('$kota', $ongkos, $ongkostetap)";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "add");
            }
        }
    } else {
        /* default value untuk form */
        $kota = "";
        $ongkos = 0;
        $ongkostetap = 0;
        $action = "add";
    }
}

if ($action == "add") {
    $admintitle = _ADDPOSTAGE;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '">
            <input type="hidden" name="action" value="save">
			<div class="control-group">
					<label class="control-label">' . _KOTA . '</label>
					<div class="controls">
						<input type="text" name="kota" value="' . $kota . '" placeholder="' . _KOTA . '" class="span12">
					</div>
			</div>
			<div class="control-group">
					<label class="control-label">' . _ONGKOS . '</label>
					<div class="controls">
						<input type="text" name="ongkos" value="' . $ongkos . '"  placeholder="' . _ONGKOS . '" class="span12" value="0">
					</div>
			</div>
			<div class="control-group">
					<label class="control-label">' . _ONGKOSTETAP . '</label>
					<div class="controls">
						<input type="text" name="ongkostetap" value="' . $ongkostetap . '"  placeholder="' . _ONGKOSTETAP . '" class="span12" value="0">
					</div>
			</div>
            <!--<div class="control-group">
                    <label class="control-label">' . _BIAYA . '</label>
                    <div class="controls">
                            <input type="text" name="biaya" placeholder="' . _BIAYA . '" class="span12" value="' . $biaya . '">
                    </div>
            </div>-->
            <div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="buton" value="' . _SAVE . '">
                             <a href="' . param_url("?p=$modulename") . '" class="buton">' . _BACK . '</a>
                    </div>
            </div>
	</form>
    ';
}

if ($action == "update") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        // $notificationbuilder .= validation($kota, _KOTA, false);
        // $notificationbuilder .= validation($ongkostetap, _ONGKOSTETAP, true);
        // $notificationbuilder .= validation($ongkos, _ONGKOS, true);
        // $notificationbuilder .= validation($waktukirim, _WAKTUKIRIM, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
            $blank = false;
        } else {
            $sql = "UPDATE sc_postage SET kota='$kota', ongkos=$ongkos, ongkostetap=$ongkostetap WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
                $blank = false;
            }
        }
    } else {
        /* default value untuk form */
        $action = "modify";
        $blank = false;
    }
}

if ($action == "modify") {
    $sql = "SELECT  id, kota, ongkos, ongkostetap FROM sc_postage WHERE id='$pid'";
    $result = $mysql->query($sql);

    $admintitle = _EDITPOSTAGE;
    if ($mysql->num_rows($result) > 0) {
        list($id, $kota, $ongkos, $ongkostetap) = $mysql->fetch_row($result);
        $admincontent .= '
		  <form class="form-horizontal" method="POST" action="' . $thisfile . '">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="pid" value="' . $pid . '">
                        <div class="control-group">
                                <label class="control-label">' . _KOTA . '</label>
                                <div class="controls">
                                    <input type="text" name="kota" value="' . $kota . '" placeholder="' . _KOTA . '" class="span12">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">' . _ONGKOS . '</label>
                                <div class="controls">
                                    <input type="text" name="ongkos" value="' . $ongkos . '"  placeholder="' . _ONGKOS . '" class="span12" value="0">
                                </div>
                        </div>
						<div class="control-group">
                                <label class="control-label">' . _ONGKOSTETAP . '</label>
                                <div class="controls">
                                    <input type="text" name="ongkostetap" value="' . $ongkostetap . '"  placeholder="' . _ONGKOSTETAP . '" class="span12" value="0">
                                </div>
                        </div>
                        <!--<div class="control-group">
                                <label class="control-label">' . _WAKTUKIRIM . '</label>
                                <div class="controls">
                                    <input type="text" name="waktu_kirim" value="' . $waktu_kirim . '" placeholder="' . _WAKTUKIRIM . '" class="span12" value="0">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">' . _BIAYA . '</label>
                                <div class="controls">
                                    <input type="text" name="biaya" value="' . $biaya . '" placeholder="' . _BIAYA . '" class="span12" value="0">
                                </div>
                        </div>-->
                        <div class="control-group">
                           <div class="controls">
                               <input type="submit" name="submit" class="buton" value="' . _SAVE . '">
                               <a href="' . param_url('?p='.$modulename.'') . '" class="buton">' . _BACK . '</a>
                            </div>
                        </div>
		</form>';
    } else {
        $action = createmessage(_NOPOSTAGE, _INFO, "info", "");
    }
}

if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $admintitle = _DELPOSTAGE;
   $sql = "SELECT id FROM sc_postage WHERE id = '$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOPOSTAGE, _INFO, "info", "");
    } else {
        list($id) = $mysql->fetch_row($result);
        if ($action == "remove") {
            $admincontent .= "<h5>" . _PROMPTDEL . "</h5>";
            $admincontent .= '<div class="control-group">
                                <div class="controls">
                                        <a class="btn btn-danger" href="'.param_url("?p=$modulename&action=delete&pid=$id").'">' . _YES . '</a>
                                        <a class="btn" href="javascript:history.go(-1)">' . _NO . '</a></p>
                                </div>
                        </div>';
        } else {
            $sql = "DELETE FROM sc_postage WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    }
}

if ($action == "" or $action == 'search') {
    $admintitle="";
	if( $action == 'search') 
	{
		$admintitle = _SEARCHRESULTS;
		$sql = "SELECT id, kota, ongkos, ongkostetap FROM sc_postage WHERE kota LIKE '%$keyword%' ";	
	}
	else
	{
		$sql = "SELECT id, kota, ongkos, ongkostetap FROM sc_postage ";
	}

    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);
		
    if ($mysql->num_rows($result) > 0) {
		$start = $screen * $max_page_list;
		$sql .= " LIMIT $start, $max_page_list";	
		$result = $mysql->query($sql);
		
        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "");
        }
        $admincontent .= "<table class=\"stat-table\">\n";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _KOTA . "</th>";
        $admincontent .= "<th>" . _ONGKOSTETAP . "</th>";
        $admincontent .= "<th>" . _ONGKOS . "</th>";
        $admincontent .= "<th align=\"center\">" . _EDIT . "</th></tr>\n";
        while (list($id, $kota, $ongkos, $ongkostetap) = $mysql->fetch_row($result)) {
            $admincontent .= "<td>$kota</td>";
            $admincontent .= "<td>" . number_format($ongkostetap, 0, ',', '.') . "</td>";
            $admincontent .= "<td>" . number_format($ongkos, 0, ',', '.') . "</td>";
            $admincontent .= "<td class=\"action-ud center\"><a href=\"?p=$modulename&action=modify&pid=$id\">";
            $admincontent .= "<img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a>\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$id\">";
            $admincontent .= "<img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        createnotification(_NOPOSTAGE, _INFO, "info");
    }
    $admincontent .= "<a href=\"".param_url("?p=$modulename&action=add")."\" class=\"btn btn-danger\">" . _ADDPOSTAGE . "</a>";
}

?>