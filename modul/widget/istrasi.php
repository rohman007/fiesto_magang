<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$pid = fiestolaundry($_REQUEST['pid'], 11);

$action = fiestolaundry($_REQUEST['action'], 20);
$posisi = fiestolaundry($_POST['posisi'], 1);
$isborder = fiestolaundry($_POST['isborder'], 1);
$judul = fiestolaundry($_POST['judul'], 100);
$isi = fiestolaundry($_POST['isi'], 0, TRUE);
$urutan = fiestolaundry($_POST['urutan'], 11);
$type = fiestolaundry($_REQUEST['type'], 11);
$widgetconfig = $_POST['widgetconfig'];

if (count($availangs) > 0) {
    foreach ($availangs as $key => $langl) {
        if ($langl != $defaultlang) {
            $juduledit[$langl] = fiestolaundry($_POST['juduledit_' . $langl], 100);
            $isiedit[$langl] = fiestolaundry($_POST['isiedit_' . $langl], 0, TRUE);
        }
    }
}

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    header("Location:?p=$modulename");
}

if ($posisiwidgeta == '_LEFT' || $posisiwidgeta == '_RIGHT' || $posisiwidgeta == '_TOP' || $posisiwidgeta == '_BOTTOM') {
    // eval('$namaposisia = ' . $posisiwidgeta . ';');
} else {
    // $namaposisia = _LEFT;
}

if ($posisiwidgetb == '_LEFT' || $posisiwidgetb == '_RIGHT' || $posisiwidgetb == '_TOP' || $posisiwidgetb == '_BOTTOM') {
    // eval('$namaposisib = ' . $posisiwidgetb . ';');
} else {
    // $namaposisib = _RIGHT;
}

if ($action == "") {
    if ($notification == "addsuccess") {
        $admincontent .= createnotification(_ADDSUCCESS, _SUCCESS, "success");
    } else if ($notification == "editsuccess") {
        $admincontent .= createnotification(_EDITSUCCESS, _SUCCESS, "success");
    } else if ($notification == "delsuccess") {
        $admincontent .= createnotification(_DELETESUCCESS, _SUCCESS, "success");
    } else if ($notification == "dberror") {
        $admincontent .= createnotification(_DBERROR, _ERROR, "error");
    }

    $admincontent .= "
        <div id='trash'>
            <div id='sortable-delete' class='connectedSortable'></div>
        </div>
        <div id='content' class='span3'>
                <ul id='sortable1' class='connectedSortable'>" . _WIDGETAVAILABLE;
    $sql_widget = "SELECT id, namatampilan 
                FROM widgettype 
                ORDER BY namatampilan ";
    $result_widget = $mysql->query($sql_widget);
    while (list($id, $namatampilan) = $mysql->fetch_row($result_widget)) {
        $admincontent .= "
            <li class='ui-state-default' id='$id'>
                <div class='list-setting'>
                    <div class='title'>$namatampilan</div>
                    <a href=''><i class='icon-cog'></i></a>
                </div>
            </li>";
    }
    $admincontent .= "</ul>
                </div>\r\n\r\n";
    
    $admincontent .= "<div id='content2' class='span3'>
                <ul id='sortable2' class='connectedSortable widgetleft'>" . " $namaposisia";
    $sql_widget = "SELECT id, type, judul 
                FROM widget 
                WHERE posisi = '$posisi1' 
                ORDER BY urutan";
    $result_widget = $mysql->query($sql_widget);
    while (list($id, $type, $namatampilan) = $mysql->fetch_row($result_widget)) {
        $admincontent .= "
            <li class='ui-state-default' id='id_$id'>
                <div class='list-setting'>
                    <div class='title'>$namatampilan</div>
                    <a href='?p=widget&action=edit&pid=$id'><i class='icon-cog'></i></a>
                </div>
            </li>";
    }

    $admincontent .= "</ul>
                </div>\r\n\r\n";
    
    // $admincontent .= "<div id='content3' class='span3'>
                // <ul id='sortable3' class='connectedSortable widgetright'>" . " $namaposisib";
    // $sql_widget = "SELECT id, type, judul 
                // FROM widget 
                // WHERE posisi = '$posisi2' 
                // ORDER BY urutan";
    // $result_widget = $mysql->query($sql_widget);
    // while (list($id, $type, $namatampilan) = $mysql->fetch_row($result_widget)) {
        // $admincontent .= "
            // <li class='ui-state-default' id='id_$id'>
                // <div class='list-setting'>
                    // <div class='title'>$namatampilan</div>
                    // <a href='?p=widget&action=edit&pid=$id'><i class='icon-cog'></i></a>
                // </div>
            // </li>";
    // }

    // $admincontent .= "</ul>
                // </div>";
    $admincontent .= "
                
                <div id='content4' class='span3'></div>
        </div>";
    $specialadmin = "";
}

if ($action == "edit") {
    if (empty($pid)) {
        $admincontent .= createstatus(_NOWIDGET, "error");
    }
    $sql = "SELECT w.id, w.type, w.judul, w.isi, w.posisi, w.isborder, w.urutan, wt.namatampilan FROM widget w, widgettype wt WHERE w.id='$pid' AND w.type=wt.id";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == 0) {
        $admincontent .= createstatus(_NOWIDGET, "error");
    } else {
        if (isset($_POST['submit'])) {
            $notificationbuilder = "";
            $notificationbuilder .= validation($judul, _TITLE, false);
            if ($notificationbuilder != "") {
                $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
            } else {
                for ($i = 0; $i < count($widgetconfig); $i++) {
                    $isi .= ($i == 0) ? $widgetconfig[$i] : ';' . $widgetconfig[$i];
                }
                if ($isborder != 1) {
                    $isborder = 0;
                }
                $sql = "UPDATE widget SET judul='$judul', isi = '$isi', isborder = $isborder WHERE id = $pid";
                $result = $mysql->query($sql);
                if ($result) {
                    header("Location:?p=$modulename&notification=editsuccess");
                } else {
                    $builtnotification = createnotification(_DBERROR, _ERROR, "error");
                }
            }
        } else {
            list($id, $type, $judul, $isi, $posisi, $isborder, $urutan, $namatampilan) = $mysql->fetch_row($result);
        }
    }


    $sql = "SELECT id, modul, jenis, namatampilan, isconfigurable FROM widgettype WHERE id='$type'";
    $result = $mysql->query($sql);
    list($id, $modul, $jenis, $namatampilan, $isconfigurable) = $mysql->fetch_row($result);

    $isborderchecked = ($isborder) ? 'checked' : '';

    if ($isconfigurable) {
        if (file_exists("../modul/$modul/lang/$lang/definisi.php")) {
            include("../modul/$modul/lang/$lang/definisi.php");
        }
        if (file_exists("../modul/$modul/widgetistrasi.php")) {
            include("../modul/$modul/widgetistrasi.php");
        }
    }

    $admintitle .= _EDITWIDGET;
    $admincontent .= '
	<form class=\"form-horizontal\" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="pid" value="' . $pid . '" />
            ' . $builtnotification . '
            <div class="control-group">
                    <label class="control-label">' . _TITLE . '</label>
                    <div class="controls">
                            <input type="text" name="judul" placeholder="' . _TITLE . '" class="span12" value="' . $judul . '">
                    </div>
            </div>
            <div class="control-group">
                    <div class="controls ">
                            <label class="checkbox">
                            <input type="checkbox" value="1" name="isborder" ' . $isborderchecked . '>' . _BORDER . '</label>
                    </div>
            </div>';
    /* MODUL URASI */
    if ($modulurasi != "") {
        $admincontent .= '<div class="control-group">
                    <div class="controls ">
                            ' . $modulurasi . '
                    </div>
            </div>';
    }
    $admincontent .= '
            <div class = "control-group">
                <div class = "controls">
                    <input type = "submit" name = "submit" class = "btn btn-danger" value = "' . _EDIT . '" >
                    <input type = "submit" name = "back" class = "btn" value = "' . _BACK . '" >
                </div>
            </div>
            </form>
    ';
    $specialadmin = "";
}

if ($action == "morelang") {
    $activelang = $availangs[1];
    $nama_activelang = $nama_availangs[1];

    $admincontent .= '<script type = "text/javascript" src = "' . $rooturl . $cfg_app_url . '/modul/widget/js/widget.js"></script>';

    $sql = "SELECT ht.modul, ht.jenis, ht.isconfigurable, h.judul, h.isi FROM widget h, widgettype ht WHERE ht.id=h.type AND h.id='$pid'";
    $result = $mysql->query($sql);
    list($widget_modul, $widget_jenis, $isconfigurable, $judul, $isi) = $mysql->fetch_row($result);
    $admincontent .= "<h2 id=\"headerlang\">$nama_activelang: $judul</h2>";

    $admincontent .= "<h3>";
    $jsonarray = json_encode($availangs);
    foreach ($availangs as $key => $langl) {
        if ($langl != $defaultlang) {
            $admincontent .= "<a href=\"#\" class=\"langchoice\" id=\"langchoice$langl\" 
				onClick=\"showHide('$langl', " . htmlspecialchars($jsonarray, ENT_QUOTES) . ", 
					'$judul', '" . $nama_availangs[$key] . "', '$defaultlang', '$widget_modul'
					, '$widget_jenis', '$isconfigurable')\" >" .
                    $nama_availangs[$key] . "</a> 
			";
        }
    }
    $admincontent .= "</h3>";

    $tempjudul = $judul;
    $tempisi = $isi;

    $jsonarray = json_encode($availangs);
    $jsonarray2 = json_encode($nama_availangs);
    $admincontent .= "<input type=\"button\" onClick='copas(\"" . urlencode($tempjudul) . "\",
		\"" . urlencode($tempisi) . "\", 
		" . htmlspecialchars($jsonarray, ENT_QUOTES) . ", \"$defaultlang\", 
		" . htmlspecialchars($jsonarray2, ENT_QUOTES) . ", \"$widget_modul\", 
		\"$widget_jenis\", \"$isconfigurable\" )' value=\"" . _COPYFROMORI . "\">";

    $admincontent .= '
		<form method="POST" action="' . $thisfile . '">
			<input type="hidden" name="action" value="ubahlang">
			<input type="hidden" name="pid" value="' . $pid . '">
			<table border="0" cellspacing="1" cellpadding="3">';

    $ctr = 0;
    foreach ($availangs as $key => $langl) {
        if ($langl != $defaultlang) {
            $sql = "SELECT judul FROM widget_lang WHERE id='$pid' AND lang='$langl'";
            $result = $mysql->query($sql);
            $ctr++;

            $judul = "";
            if ($mysql->num_rows($result) > 0) {
                list($judul) = $mysql->fetch_row($result);
            }

            $admincontent .= '
				<tr id="juduledit_' . $langl . '"';
            if ($ctr > 1) {
                $admincontent .= ' style=\'visibility: hidden; display:none;\'>';
            } else {
                $admincontent .= '>';
            }
            $admincontent .= '<td align="right">' . _TITLE . ' ';
            $admincontent .= '(' . $nama_availangs[$key] . ')';
            $admincontent .= ':</td>
					<td><input type="text" id="tdjuduledit_' . $langl . '" name="juduledit_' . $langl . '" size="40" value="' . $judul . '"></td>
				</tr>';
        }
    }

    if ($widget_jenis == "custom") {
        $ctr = 0;
        foreach ($availangs as $key => $langl) {
            if ($langl != $defaultlang) {
                $sql = "SELECT isi FROM widget_lang WHERE id='$pid' AND lang='$langl'";
                $result = $mysql->query($sql);
                $ctr++;

                $isi = "";
                if ($mysql->num_rows($result) > 0) {
                    list($isi) = $mysql->fetch_row($result);
                }

                $admincontent .= '
					<tr id="isiedit_' . $langl . '"';
                if ($ctr > 1) {
                    $admincontent .= ' style=\'visibility: hidden; display:none;\'>';
                } else {
                    $admincontent .= '>';
                }
                $admincontent .= '<td align="right">' . _WIDGETCONTENT . ' ';
                $admincontent .= '(' . $nama_availangs[$key] . ')';
                $admincontent .= ':</td>
						<td><textarea id="tdisiedit_' . $langl . '" name="isiedit_' . $langl . '" 
							rows="20" cols="60" class="usetiny">' . $isi . '</textarea></td>
					</tr>';
            }
        }
    }

    $admincontent .= '
			  <tr>
				<td align="right" valign="top">&nbsp;</td>
				<td><input type="submit" name="submit" value="' . _SAVE . '"></td>
			  </tr>
			</table>
		</form>';

    $specialadmin .= "<a href=\"?p=widget&action=edit&pid=$pid&r=$random\">" . _BACK . "</a><br />";
}

if ($action == 'ubahlang') {
    $sql = "SELECT ht.modul, ht.jenis, ht.isconfigurable FROM widget h, widgettype ht WHERE ht.id=h.type AND h.id='$pid'";
    $result = $mysql->query($sql);
    list($widget_modul, $widget_jenis, $isconfigurable) = $mysql->fetch_row($result);

    foreach ($availangs as $key => $langl) {
        if ($langl != $defaultlang) {
            if ($widget_jenis != "custom") {
                $isiedit[$langl] = "";
            }

            $sql = "SELECT id, lang FROM widget_lang WHERE lang='$langl' AND id='$pid'";
            $result = $mysql->query($sql);
            if ($mysql->num_rows($result) > 0) {
                $sql = "UPDATE widget_lang SET judul='" . $juduledit[$langl] . "' ";
                $sql .= ", isi='" . $isiedit[$langl] . "' ";
                $sql .= "WHERE lang='$langl' AND id='$pid'";
            } else {
                if (!empty($juduledit[$langl])) {
                    $sql = "INSERT INTO widget_lang (id, lang, judul, isi) VALUES ('$pid', '$langl', '" . $juduledit[$langl] . "', '" . $isiedit[$langl] . "')";
                }
            }

            if (!$mysql->query($sql)) {
                pesan(_ERROR, _DBERROR);
            }
        }
    }

    pesan(_SUCCESS, _DBSUCCESS, "?p=widget&action=morelang&pid=$pid&r=$random");
}
//if ($specialadmin == '') {
//    $specialadmin = "<a href=\"?p=widget\">" . _BACK . "</a>";
//}
if ($action == "edit" and count($availangs) > 1)
    $specialadmin .= " <a href=\"?p=widget&action=morelang&pid=$pid\">" . _MORELANG . "</a>";
?>
