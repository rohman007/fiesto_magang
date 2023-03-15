<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$action = fiestolaundry($_REQUEST['action'], 20);

$defaultfieldtexts = array(_NAME, _COMPANY, _ADDRESS, _CITY, _ZIP, _STATE, _COUNTRY, _TEL1, _TEL2, _TEL3, _FAX, _EMAIL, _WEBSITE, _COMMENT, _SUBMIT);
$defaultmisctexts = array(_UMBALEMAIL, _MSGASTERISK, _MSGOK, _MSGREQ, _MSGMAIL, _MSGHACK, _MSGBACK);

if ($action == "setform") {
    for ($i = 0; $i < count($defaultfieldtexts); $i++) {
        $pfield = ($_POST['pfield' . $i] == '') ? "0" : "1";
        $rfield = ($_POST['rfield' . $i] == '') ? "0" : "1";
        $dfield = $_POST['dfield' . $i];
        $sql = "SELECT name FROM contact WHERE grup='0' AND indeks='$i'";
        $result = $mysql->query($sql);
        list($name) = $mysql->fetch_row($result);
        if ($name != 'email' && $name != 'submit') {
            $sql = "UPDATE contact SET value='$dfield', published='$pfield', required='$rfield' WHERE grup='0' AND indeks='$i'";
        }
		if ($name == 'submit') {
			$sql="UPDATE contact SET value='$dfield' WHERE grup='0' AND indeks='$i'";
		}
        if (!$mysql->query($sql))
            $action = createmessage(_DBERROR, _ERROR, "error", "");
    }
    for ($i = 0; $i < count($defaultmisctexts); $i++) {
        $misc = fiestolaundry($_POST['misc' . $i]);
        $sql = "UPDATE contact SET value='$misc' WHERE grup='1' AND indeks='$i'";
        if (!$mysql->query($sql)) {
            $action = createmessage(_DBERROR, _ERROR, "error", "");
        }
    }
    $mysql->query("UPDATE contact SET value='" . fiestolaundry($_POST['notebefore'], 0, TRUE) . "' WHERE name='notebefore'");
    $mysql->query("UPDATE contact SET value='" . fiestolaundry($_POST['noteafter'], 0, TRUE) . "' WHERE name='noteafter'");
    $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
}
if ($action == "") {
    if (isset($_POST['submit'])) {
        for ($i = 0; $i < count($defaultfieldtexts); $i++) {
            $pfield = ($_POST['pfield' . $i] == '') ? "0" : "1";
            $rfield = ($_POST['rfield' . $i] == '') ? "0" : "1";
            $dfield = $_POST['dfield' . $i];
            $sql = "SELECT name FROM contact WHERE grup='0' AND indeks='$i'";
            $result = $mysql->query($sql);
            list($name) = $mysql->fetch_row($result);
            if ($name != 'email' && $name != 'submit') {
                $sql = "UPDATE contact SET value='$dfield', published='$pfield', required='$rfield' WHERE grup='0' AND indeks='$i'";
            }
			if ($name == 'submit') {
				$sql="UPDATE contact SET value='$dfield' WHERE grup='0' AND indeks='$i'";
			}
            if (!$mysql->query($sql)) {
                createnotification(_DBERROR, _ERROR, "");
            }
        }
        for ($i = 0; $i < count($defaultmisctexts); $i++) {
            $misc = fiestolaundry($_POST['misc' . $i]);
            $sql = "UPDATE contact SET value='$misc' WHERE grup='1' AND indeks='$i'";
            if (!$mysql->query($sql)) {
                createnotification(_DBERROR, _ERROR, "");
            }
        }
        $mysql->query("UPDATE contact SET value='" . fiestolaundry($_POST['notebefore'], 0, TRUE) . "' WHERE name='notebefore'");
        $mysql->query("UPDATE contact SET value='" . fiestolaundry($_POST['noteafter'], 0, TRUE) . "' WHERE name='noteafter'");
        createnotification(_EDITSUCCESS, _SUCCESS, "success", "");
    }
    $admincontent .= "<script type=\"text/javascript\" src=\"../modul/contact/js/contact.js\"></script>";
    $admincontent .= "$builtnotification";
    $admincontent .= "<form class=\"form-horizontal\" action=\"?p=contact\" method=\"POST\" name=\"fieldopt\" id=\"fieldopt\" onSubmit=\"return valform('fieldopt')\">\r\n";

    $admincontent .= "<h3>" . _QUESTIONLIST . "</h3>";
    $admincontent .= "<table class=\"stat-table\">";
    $admincontent .= "<tr><th>&nbsp;" . _PUBLISHED . " <input type=\"checkbox\" name=\"pcheckall\" onClick=\"checkall('p',this.checked)\"></th>";
    $admincontent .= "<th>&nbsp;" . _REQUIRED . " <input type=\"checkbox\" name=\"rcheckall\" onClick=\"checkall('r',this.checked)\"></th>";
    $admincontent .= "<th>" . _FIELD . "</th><th>" . _DISPLAYED . "</th></tr>";
    $sql = "SELECT name, value, published, penabled, required, renabled FROM contact WHERE published<='1' AND grup='0' ORDER BY indeks";
    $result = $mysql->query($sql);
    $i = 0;
    while (list($name, $value, $published, $penabled, $required, $renabled) = $mysql->fetch_row($result)) {
        $rowclass = (fmod($i, 2) == 0) ? "selang" : "seling";
        $checked = ($published == 1) ? 'checked' : '';
        $disabled = ($penabled == 0) ? 'disabled' : '';
        $admincontent .= "<tr class=\"$rowclass\"><td align=\"center\"><input type=\"checkbox\" name=\"pfield$i\" onClick=\"javascript:if (this.checked==false) document.fieldopt.rfield$i.checked=false\" value=\"1\" $checked $disabled></td>\r\n";
        $checked = ($required == 1) ? 'checked' : '';
        $disabled = ($renabled == 0) ? 'disabled' : '';
        $admincontent .= "<td align=\"center\"><input type=\"checkbox\" name=\"rfield$i\" onClick=\"javascript:if (this.checked==true) document.fieldopt.pfield$i.checked=true\" value=\"1\" $checked $disabled></td>\r\n";
        $admincontent .= "<td>$defaultfieldtexts[$i]</td>\r\n";
        $admincontent .= "<td><input type=\"text\" name=\"dfield$i\" value=\"$value\" maxlength=\"20\"/></td></tr>\r\n";
        $i++;
    }
    $admincontent .= "</table>\r\n";

    $admincontent .= "<h3>" . _MISCELLANEOUS . "</h3>";
    $sql = "SELECT name, value FROM contact WHERE grup='1' ORDER BY indeks";
    $result = $mysql->query($sql);
    $i = 0;
    while (list($name, $value) = $mysql->fetch_row($result)) {
        $admincontent .= "<div class=\"control-group\"><label class=\"control-label\">$defaultmisctexts[$i]</label><div class=\"controls\"><input type=\"text\" name=\"misc$i\" value=\"$value\"></div></div>";
        $i++;
    }

    $sql = "SELECT value FROM contact WHERE name='notebefore'";
    $result = $mysql->query($sql);
    list($note) = $mysql->fetch_row($result);
    $admincontent .= "<div class=\"control-group\">\r\n";
    $admincontent .= "<label class=\"control-label\">" . _NOTEBEFORE . "</label>";
    $admincontent .= "<div class=\"controls\"><textarea name=\"notebefore\" class=\"usetiny span12\">$note</textarea></div>";
    $admincontent .= "</div>\r\n";

    $sql = "SELECT value FROM contact WHERE name='noteafter'";
    $result = $mysql->query($sql);
    list($note) = $mysql->fetch_row($result);
    $admincontent .= "<div class=\"control-group\">\r\n";
    $admincontent .= "<label class=\"control-label\">" . _NOTEAFTER . "</label>";
    $admincontent .= "<div class=\"controls\"><textarea name=\"noteafter\" class=\"usetiny span12\">$note</textarea></div>";
    $admincontent .= "</div>\r\n";

    $admincontent .= "<div class=\"control-group\">\r\n";
    $admincontent .= "<div class=\"controls\"><input class=\"buton\" type=\"submit\" name=\"submit\" value=\"" . _SAVE . "\"></div>";
    $admincontent .= "</div>\r\n";
    $admincontent .= "</form>\r\n";
}
?>