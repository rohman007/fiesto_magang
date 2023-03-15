<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$action = fiestolaundry($_POST['action'], 20);

if ($action == "translate") {
    $sukses = true;
    foreach ($_POST['id_judulfrontend'] as $idx => $id) {
        $sql1 = "UPDATE module SET judulfrontend='" . $_POST['judulfrontend'][$idx] . "' WHERE id='$id'";
        if (!$mysql->query($sql1)) {
            $sukses = false;
            $action = createmessage(_DBERROR, _ERROR, "error", "");
        }
    }

    foreach ($_POST['id_terjemahan'] as $idx => $id) {
        $sql1 = "UPDATE translation SET terjemahan='" . $_POST['terjemahan'][$idx] . "' WHERE id='$id'";
        if (!$mysql->query($sql1)) {
            $action = createmessage(_DBERROR, _ERROR, "error", "");
            $sukses = false;
        }
    }
    if ($sukses) {
        $action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
    }
    $action = "";
}

if ($action == "") {
    $i = 0;
    $sql = "SELECT distinct m.judul judul FROM translation t, module m WHERE t.modul=m.nama ORDER BY m.judul";
    $result = $mysql->query($sql);
    $admincontent .="<ul class=\"nav nav-tabs\" id=\"myTab2\">\r\n";
	 $admincontent .="<li class=\"active\"><a href=\"#tab0\">"._MODULENAMES."</a></li>\r\n";
    while ($d = $mysql->fetch_array($result)) {
        $i++;
        // if ($i == 2) {
            // $admincontent .="<li class=\"active\"><a href=\"#tab0\">" . $d['judul'] . "</a></li>\r\n";
        // } else {
            $admincontent .="<li class=\"\"><a href=\"#tab$i\">" . $d['judul'] . "</a></li>\r\n";
//        }
    }
    $admincontent .= "</ul>\r\n";
    $j = 0;
    $admincontent .= "<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\" enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"action\" value=\"translate\">";
    //start tab content
    $admincontent .= "<div class=\"tab-content\">\r\n";
    $admincontent .= "<div class=\"tab-pane active\" id=\"tab$j\">\r\n";
    $sql = "SELECT id, judul, judulfrontend FROM module ORDER BY judul";
    $result = $mysql->query($sql);
    while (list ($id, $judul, $judulfrontend) = $mysql->fetch_row($result)) {
        $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">$judul</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <input type=\"hidden\" name=\"id_judulfrontend[]\" value=\"$id\" />\r\n
                                            <input type=\"text\" name=\"judulfrontend[]\" value=\"$judulfrontend\" size=\"50\" max_length=\"50\"/></td></tr>\r\n
                                    </div>\r\n
                            </div>\r\n";
    }
    $admincontent .= "</div>\r\n"; //end tab0

    $sql = "SELECT t.id, t.asli, t.terjemahan, m.judul FROM translation t, module m WHERE t.modul=m.nama ORDER BY m.judul";
    $result = $mysql->query($sql);
    $currentmodule = "";
    while (list ($id, $asli, $terjemahan, $namamodul) = $mysql->fetch_row($result)) {
        if ($currentmodule != $namamodul) {
            $j++;
            if ($j == 1) {
                $admincontent .= "<div class=\"tab-pane\" id=\"tab$j\">\r\n";
            } else {
                $admincontent .= "</div>\r\n";
                $admincontent .= "<div class=\"tab-pane\" id=\"tab$j\">\r\n";
            }
        }
        $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">$asli</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <input type=\"hidden\" name=\"id_terjemahan[]\" value=\"$id\" />\r\n
                                            <input type=\"text\" name=\"terjemahan[]\" value=\"$terjemahan\" size=\"50\" max_length=\"50\"/>\r\n
                                    </div>\r\n
                            </div>\r\n";
        $currentmodule = $namamodul;
    }
    $admincontent .= "</div>\r\n";
    //end tab-content
    $admincontent .= "<div class=\"control-group\">\r\n
                            <div class=\"controls\">\r\n
                                    <input class=\"btn btn-danger\" type=\"submit\" name=\"submit\" value=\"" . _SAVE . "\" />\r\n
                            </div>\r\n
                    </div>\r\n";
    $admincontent .= "</form>";
}
?>
