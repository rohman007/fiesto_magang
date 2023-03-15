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

$kota = fiestolaundry($_POST['kota'], 50);
$ongkos = fiestolaundry($_POST['ongkos'], 15);
$ongkostetap = fiestolaundry($_POST['ongkostetap'], 15);
$waktukirim = fiestolaundry($_POST['waktukirim'], 10);

$courieroption = $_POST['courieroption'];
$action = fiestolaundry($_REQUEST['action'], 20);
$blank = true;
$action = fiestolaundry($_REQUEST['action'], 20);

$modulename = $_GET['p'];


if ($action == "setsite" AND $_POST["savesetting"]) {
    $citynotfoundmsg = $_POST['citynotfoundmsg'];
    $paymentnote = $_POST['paymentnote'];
    $r1 = $mysql->query("UPDATE config set value='$citynotfoundmsg' WHERE name='citynotfoundmsg'");
    $r2 = $mysql->query("UPDATE config set value='$paymentnote' WHERE name='paymentnote'");
    if ($r1 and $r2) {
        $action = createmessage(_SUCCESSEDITSETTING, _SUCCESS, "success", "");
    } else {
        $action = createmessage(_ERROREDITSETTING, _ERROR, "error", "");
    }
}
if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($kota, _KOTA, false);
        $notificationbuilder .= validation($ongkostetap, _ONGKOSTETAP, true);
        $notificationbuilder .= validation($ongkos, _ONGKOS, true);
        // $notificationbuilder .= validation($waktukirim, _WAKTUKIRIM, true);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            $sql = "INSERT INTO sc_postage (kota, ongkos, ongkostetap, waktukirim) 
                        VALUES ('$kota', '$ongkos', '$ongkostetap', '$waktukirim')";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "manual");
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
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
            <div class="control-group">
                    <label class="control-label">' . _KOTA . '</label>
                    <div class="controls">
                            <input type="text" name="kota" placeholder="' . _KOTA . '" class="span12" value=' . $kota . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _ONGKOSTETAP . '</label>
                    <div class="controls">
                            <input type="text" name="ongkostetap" placeholder="' . _ONGKOSTETAP . '" class="span12" value="' . $ongkostetap . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _ONGKOS . '</label>
                    <div class="controls">
                            <input type="text" name="ongkos" placeholder="' . _ONGKOS . '" class="span12" value="' . $ongkos . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _WAKTUKIRIM . '</label>
                    <div class="controls">
                            <input type="text" name="waktukirim" placeholder="' . _WAKTUKIRIM . '" class="span12" value="' . $waktukirim . '">
                    </div>
            </div>
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
        $notificationbuilder .= validation($kota, _KOTA, false);
        $notificationbuilder .= validation($ongkostetap, _ONGKOSTETAP, true);
        $notificationbuilder .= validation($ongkos, _ONGKOS, true);
        // $notificationbuilder .= validation($waktukirim, _WAKTUKIRIM, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
            $blank = false;
        } else {
            $sql = "UPDATE sc_postage SET kota='$kota', ongkos='$ongkos', 
                waktukirim='$waktukirim', ongkostetap='$ongkostetap' WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "manual");
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
    $sql = "SELECT  id, kota, ongkos, ongkostetap, waktukirim FROM sc_postage WHERE id='$pid'";
    $result = $mysql->query($sql);

    $admintitle = _EDITPOSTAGE;
    if ($mysql->num_rows($result) > 0) {
        if ($blank) {
            list($id, $kota, $ongkos, $ongkostetap, $waktukirim) = $mysql->fetch_row($result);
        }
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
                                <label class="control-label">' . _ONGKOSTETAP . '</label>
                                <div class="controls">
                                    <input type="text" name="ongkostetap" value="' . $ongkostetap . '"  placeholder="' . _ONGKOSTETAP . '" class="span12" value="0">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">' . _ONGKOS . '</label>
                                <div class="controls">
                                    <input type="text" name="ongkos" value="' . $ongkos . '" placeholder="' . _ONGKOS . '" class="span12" value="0">
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">' . _WAKTUKIRIM . '</label>
                                <div class="controls">
                                    <input type="text" name="waktukirim" value="' . $waktukirim . '" placeholder="' . _WAKTUKIRIM . '" class="span12" value="0">
                                </div>
                        </div>
                        <div class="control-group">
                           <div class="controls">
                               <input type="submit" name="submit" class="buton" value="' . _SAVE . '">
                               <a href="' . param_url('?p=sc_postage') . '" class="buton">' . _BACK . '</a>
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
                                        <a class="buton" href="' . param_url("?p=$modulename&action=delete&pid=$id") . '">' . _YES . '</a>
                                        <a class="buton" href="javascript:history.go(-1)">' . _NO . '</a></p>
                                </div>
                        </div>';
        } else {
            $sql = "DELETE FROM sc_postage WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "");
            }
        }
    }
}

if ($action == "manual" or $action == "search") {
    $admintitle = "";
    if ($action == "search") {
        $sql = "SELECT id, kota, ongkos, ongkostetap, waktukirim FROM sc_postage WHERE kota LIKE '%$keyword%' OR ongkos LIKE '%$keyword%' OR ongkostetap LIKE '%$keyword%'";
        $admintitle = _SEARCHRESULTS;
    } else {
        $sql = "SELECT id, kota, ongkos, ongkostetap, waktukirim FROM sc_postage";
    }

    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) > 0) {
        $start = $screen * $max_page_list;
        $sql .= " ORDER BY $sort LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);
        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "");
        }
        $admincontent .= "<div class=\"row_sc_postage\"><table class=\"stat-table\" >\n";
        $admincontent .= "<tbody>";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _KOTA . "</th>";
        $admincontent .= "<th>" . _ONGKOSTETAP . "</th>";
        $admincontent .= "<th>" . _ONGKOS . "</th>";
        $admincontent .= "<th>" . _WAKTUKIRIM . "</th>";
        // $admincontent .= "<th align=\"center\">" . _EDIT . "</th><th align=\"center\">" . _DEL . "</th></tr>\n";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($id, $kota, $ongkos, $ongkostetap, $waktukirim) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr>";
            $admincontent .= "<td>$kota</td>";
            $admincontent .= "<td align=\"right\">" . number_format($ongkostetap, 0, ',', '.') . "</td>";
            $admincontent .= "<td align=\"right\">" . number_format($ongkos, 0, ',', '.') . "</td>";
            $admincontent .= "<td align=\"right\">$waktukirim</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"" . param_url("?p=$modulename&action=modify&pid=$id") . "\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"" . param_url("?p=$modulename&action=remove&pid=$id") . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</tbody>";
        $admincontent .= "</table>";
    } else {
        createnotification(_NOPOSTAGE, _INFO, "info");
    }
    $admincontent .= "<p>" . _RUMUSONGKIR . "</p>";

    $admincontent .= "<a href=\"" . param_url("?p=$modulename&action=add") . "\" class=\"buton\">" . _ADDPOSTAGE . "</a>";
    $admincontent .= "<a href=\"" . param_url("?p=$modulename") . "\" class=\"buton\">" . _BACK . "</a>";
    $admincontent .= "</div>";
}

if ($action == "save_options") {
    if (isset($_POST['submit'])) {
        $isi = "";
        for ($i = 0; $i < count($courieroption); $i++) {
            $isi .= ($i == 0) ? $courieroption[$i] : ',' . $courieroption[$i];
        }
        $sql = "UPDATE config SET value = '$isi' WHERE name = 'couriers'";
        $result = $mysql->query($sql);
        if ($result) {
            $action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
        } else {
            $action = createmessage(_DBERROR, _ERROR, "error", "modify_options");
        }
    } else {
        /* default value untuk form */
        $action = "modify_options";
    }
}

if ($action == "") {
	$admincontent .= "<div class=\"content-widgets gray boxappear2\">";
	$admincontent .= "<div class=\"widget-head blue grayhead\"><h3>" . _POSTAGEOPTION . "</h3></div>\r\n";
    /* get selected couriers */
    $sql = "SELECT value FROM config WHERE name = 'couriers'";
    $result = $mysql->query($sql);
    list($value) = $mysql->fetch_row($result);
    $values = explode(",", $value);
    // /* get master couriers */
    // // $sql = "SELECT id, nama, kota FROM $masterdb.sc_couriers";
    $sql = "SELECT id,nama, kota FROM sc_couriers";
	
    $result = $mysql->query($sql);
    while (list($id, $nama, $kota) = $mysql->fetch_row($result)) {
        $optioncontent .= '<div class="control-group">
                        <div class="controls ">
                                <label class="checkbox">';
        $optioncontent .= "<input type=\"checkbox\" name=\"courieroption[]\" ";
        $optioncontent .= (in_array($id, $values)) ? "checked " : "";
        $optioncontent .= "value=\"$id\" />" . $nama . " " . $kota . "\r\n";
        if ($id == 0) {
            $optioncontent .= "<a href=\"" . param_url("?p=$modulename&action=manual") . "\" class=\"buton\">" . _SETTING . "</a>";
        }
		if ($id == 1) {
            $optioncontent .= "<a href=\"" . param_url("?p=sc_jne") . "\" class=\"buton\">" . _SETTING . "</a>";
        }
        $optioncontent .= '</label>';
        $optioncontent .= '</div>
            </div>';
    }
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_options">';
    $admincontent .= $optioncontent;
    $admincontent .= '<div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="buton" value="' . _SAVE . '">
                    </div>
            </div>
	</form>
    ';
	$admincontent .= "</div>";
}

if ($action == "") {
    $inclusion = "'citynotfoundmsg','paymentnote'";
    $config = fiestolaundry($_POST['config'], 9);

    $currentmodule = '';

    $sql = "SELECT name, value, namatampilan, modul, tipeform, maxlength FROM config "
            . "where name in ($inclusion) ";

    $sql.="  ORDER BY FIELD(name,$inclusion) ";
    $result = $mysql->query($sql);

    $index = 0;
	$admincontent .= "<div class=\"widget-head blue grayhead\"><h3>" . _PENGATURANTAMBAHAN . "</h3></div>\r\n";
    $admincontent .= "
	<div class=\"row_sc_postage\">
	<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\" enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"action\" value=\"setsite\">
			<input type=\"hidden\" name=\"config\" value=\"dashboard\">";
    while ($urasi = $mysql->fetch_array($result)) {

        $tipeform = explode(';', $urasi['tipeform']);


        switch ($tipeform[0]) {
            case 'text':
                $admincontent .= "<div class=\"catalog_label\">" . $urasi['namatampilan'] . "</div>\r\n
                                    <div>\r\n
                                            <input type=\"text\" name=\"" . $urasi['name'] . "\" value=\"" . $urasi['value'] . "\" 
                                                size=\"" . $tipeform[1] . "\" max_length=\"" . $urasi['maxleength'] . "\"/>\r\n
                                    </div>\r\n";
                break;
            case 'textarea':
                $admincontent .= "<div class=\"catalog_label\">" . $urasi['namatampilan'] . "</div>\r\n
                                    <div>\r\n
                                            <textarea name=\"" . $urasi["name"] . "\" cols=\"" . $tipeform[1] . "\" rows=\"" . $tipeform[2] . "\" class=\"usetiny span12 " . $tipeform[3] . "\">" . $urasi["value"] . "</textarea>
                                    </div>\r\n";
                break;
            case 'combo':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                    <select name=\"" . $urasi['name'] . "\">\r\n";
                $pilihanvalue = explode(',', $tipeform[1]);
                $pilihandisplay = explode(',', $tipeform[2]);
                for ($i = 0; $i < count($pilihanvalue); $i++) {
                    $selected = ($urasi['value'] == $pilihanvalue[$i]) ? 'selected' : '';
                    $admincontent .= '<option value="' . $pilihanvalue[$i] . '" ' . $selected . '>' . $pilihandisplay[$i] . '</option>' . "\r\n";
                }
                $admincontent .= "
                                    </select>\r\n
                                </div>\r\n
                            </div>\r\n";

                $admincontent .= '</select></td></tr>' . "\r\n";
                break;
            case 'favicon':
                $admincontent .= "<div class=\"control-group\">\r\n
									<label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
									<div class=\"controls\">\r\n
										<div data-provides=\"fileupload\" class=\"fileupload fileupload-new\"><input type=\"hidden\"  name=\"" . $urasi["name"] . "\" />
											<div class=\"input-append\">\r\n
												<div class=\"uneditable-input span2\">\r\n
													<i class=\"icon-file fileupload-exists\"></i><span class=\"fileupload-preview\"></span>\r\n
												</div>\r\n
												<span class=\"btn btn-file\"><span class=\"fileupload-new\">Cari File</span><span class=\"fileupload-exists\">Change</span>\r\n
												<img src=\"" . $cfg_img_url . "/favicon." . $urasi["value"] . "\" /><input type=\"file\" name=\"" . $urasi["name"] . "\" />
												</span><a data-dismiss=\"fileupload\" class=\"btn fileupload-exists\" href=\"#\">Remove</a>\r\n
											</div>\r\n
										</div>\r\n
									</div>\r\n
								</div>\r\n";
                break;
            case 'color':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <input type=\"text\" name=\"" . $urasi['name'] . "\" value=\"" . $urasi['value'] . "\" 
                                                size=\"7\" max_length=\"7\" class=\"colors\" />\r\n
                                    </div>\r\n
                            </div>\r\n";
                break;
            case 'none':
                break;
        }
        $currentmodule = $urasi['modul'];
    }
    $admincontent .= "<div class=\"control-group\">\r\n
                            <div class=\"controls\">\r\n
                                    <input class=\"btn btn-danger postage-save\" type=\"submit\" name=\"savesetting\" value=\"" . _SAVE . "\" />\r\n
                            </div>\r\n
                    </div>\r\n";
    $admincontent .= "</form></div>";
}
?>