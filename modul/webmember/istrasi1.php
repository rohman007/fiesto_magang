<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}
$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    header("Location:?p=$modulename");
}

$action = fiestolaundry($_REQUEST['action'], 20);
$status = fiestolaundry($_REQUEST['status'], 1);
$keyword = fiestolaundry($_GET['keyword'], 100);
$screen = fiestolaundry($_GET['screen'], 11);

if ($screen == '') {
    $screen = 0;
}
$pid = fiestolaundry($_REQUEST['pid'], 11);

$postuser = fiestolaundry($_POST['txtUser'], 12);
$postpass1 = trim($_POST['txtPass1']);
$postpass2 = trim($_POST['txtPass2']);
$postnama = fiestolaundry($_POST['txtNama'], 50);
$postperusahaan = fiestolaundry($_POST['txtPerusahaan'], 50);
$postalamat1 = fiestolaundry($_POST['txtAlamat1'], 50);
$postalamat2 = fiestolaundry($_POST['txtAlamat2'], 50);
$postkota = fiestolaundry($_POST['txtKota'], 30);
$postkodepos = fiestolaundry($_POST['txtKodepos'], 10);
$posttelepon = fiestolaundry($_POST['txtTelepon'], 20);
$postponsel1 = fiestolaundry($_POST['txtPonsel1'], 20);
$postponsel2 = fiestolaundry($_POST['txtPonsel2'], 20);
$postfax = fiestolaundry($_POST['txtFax'], 20);
$postemail = fiestolaundry($_POST['txtEmail'], 50);

$notification = fiestolaundry(($_GET['notification']));

if ($status == '') {
    $status = 1;
}
$screen = fiestolaundry($_GET['screen'], 11);

if ($action == '' or $action == 'search') { /*
  Status == 0 --> Baru
  Status == 1 --> Aktif
  Status == 2 --> Expired
  Status == 3 --> Banned
 */
    $jumlah_kolom = 4;
    if ($status == "0") {
        $judulstatus = _NEWMEMBER;
        $sql = "SELECT * FROM $tabelwebmember WHERE activity='0' ";
        $sql .= "AND activedate='0000-00-00 00:00:00' ";
        /*
          if($expired_periode > 0)
          {	$sql .= "AND DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), DATE_FORMAT(activedate,'%Y-%m-%d')) <= '$expired_periode' ";
          }
         */
        if ($action == 'search') {
            $sql = "AND (username LIKE '%$keyword%' OR fullname LIKE '%$keyword%') ";
        }
        $sql .= "ORDER BY user_regdate ASC ";
    } elseif ($status == "1") {
        $judulstatus = _ACTIVEMEMBER;
        $sql = "SELECT * FROM $tabelwebmember WHERE activity='1' ";
        if ($action == 'search') {
            $sql = "AND (username LIKE '%$keyword%' OR fullname LIKE '%$keyword%') ";
        }
        $sql .= "ORDER BY username ASC ";
        $jumlah_kolom = 5;
    } elseif ($status == "2") {
        $judulstatus = _EXPIREDMEMBER;
        $sql = "SELECT * FROM $tabelwebmember WHERE activity='0' ";
        $sql .= "AND activedate='0000-00-00 00:00:00' ";

        if ($expired_periode > 0) {
            $sql .= "AND DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), DATE_FORMAT(activedate,'%Y-%m-%d')) > '$expired_periode' ";
        }
        if ($action == 'search') {
            $sql = "AND (username LIKE '%$keyword%' OR fullname LIKE '%$keyword%') ";
        }
        $sql .= "ORDER BY user_regdate ASC ";
    } elseif ($status == "3") {
        $judulstatus = _BANNEDMEMBER;
        $sql = "SELECT * FROM $tabelwebmember WHERE activity='0' ";
        $sql .= "AND bandate!='0000-00-00 00:00:00' ";
        if ($action == 'search') {
            $sql = "AND (username LIKE '%$keyword%' OR fullname LIKE '%$keyword%') ";
        }
        $sql .= "ORDER BY bandate DESC ";
        $jumlah_kolom = 5;
    }

    $admintitle .= $judulstatus;

    $urlstatus = "index.php?p=$namamodul&action=$action&status=";
    $admincontent .= _STATUS . " : ";

    $admincontent .= "<select name=\"status\" id=\"status\" onchange=\"window.location='$urlstatus'+this.value;\">";
    $admincontent .= "<option value=\"0\" " . (($status == 0) ? "selected=\"selected\" " : "") . " >" . _NEWMEMBER . "</option>";
    $admincontent .= "<option value=\"1\" " . (($status == 1) ? "selected=\"selected\" " : "") . " >" . _ACTIVEMEMBER . "</option>";
    $admincontent .= "<option value=\"2\" " . (($status == 2) ? "selected=\"selected\" " : "") . " >" . _EXPIREDMEMBER . "</option>";
    $admincontent .= "<option value=\"3\" " . (($status == 3) ? "selected=\"selected\" " : "") . " >" . _BANNEDMEMBER . "</option>";
    $admincontent .= "</select>";
    $admincontent .= "<br/><br/>";

    $result = mysql_query($sql);
    $total_records = mysql_num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($notification == "addsuccess") {
        $admincontent .= createnotification(_ADDSUCCESS, _SUCCESS, "success");
    } else if ($notification == "editsuccess") {
        $admincontent .= createnotification(_EDITSUCCESS, _SUCCESS, "success");
    } else if ($notification == "delsuccess") {
        $admincontent .= createnotification(_DELETESUCCESS, _SUCCESS, "success");
    } else if ($notification == "dberror") {
        $admincontent .= createnotification(_DBERROR, _ERROR, "error");
    } else if ($notification == "activationsuccess") {
        $admincontent .= createnotification(_ACTIVATIONSUCCESS, _SUCCESS, "success");
    } else if ($notification == "sendcodesuccess") {
        $admincontent .= createnotification(_SENDCODESUCCESS, _SUCCESS, "success");
    } else if ($notification == "bansuccess") {
        $admincontent .= createnotification(_BANSUCCESS, _SUCCESS, "success");
    }

    if (mysql_num_rows($result) > 0) {
        $start = $screen * $max_page_list;
        $sql .= "LIMIT $start, $max_page_list ";
        $result = mysql_query($sql);

        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "");
        }

        $admincontent .= "<form name=\"myform\" id=\"myform\" method=\"POST\" action=\"$thisfile\" >";
        $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"aktivasi\">";

        $admincontent .= "<table class=\"stat-table\">\n";
        $admincontent .= "<tr>";
        $admincontent .= "<th>&nbsp;</th>";
        $admincontent .= "<th>" . _USERNAME . "</th>";
        $admincontent .= "<th>" . _NAME . "</th>";
        $admincontent .= "<th>" . _TIMEREGISTER . "</th>";
        if ($status == "1") {
            $admincontent .= "<th>" . _TIMEACTIVE . "</th>";
        }
        if ($status == "3") {
            $admincontent .= "<th>" . _TIMEBANNED . "</th>";
        }
        $admincontent .= "<th>" . _EDIT . "</th>";
        //$admincontent .= "<th>"._DEL."</th>";
        $admincontent .= "</tr>\n";

        while ($data_member = mysql_fetch_array($result)) {
            $member_id = $data_member["user_id"];
            $admincontent .= "<tr valign=\"top\">";
            $admincontent .= "<td>";
            $admincontent .= "<input type=\"checkbox\" name='cekMember[]' id='cekMember[]'  value='" . $member_id . "'>";
            $admincontent .= "</td>";
            $admincontent .= "<td>" . $data_member["username"] . "</td>";
            $admincontent .= "<td>" . $data_member["fullname"] . "&nbsp;</td>";
            $admincontent .= "<td>" . tglformat($data_member["user_regdate"]) . "</td>";
            if ($status == "1") {
                $admincontent .= "<td>" . tglformat($data_member["activedate"]) . "</td>";
            }
            if ($status == "3") {
                $admincontent .= "<td>" . tglformat($data_member["bandate"]) . "</td>";
            }
            $admincontent .= "<td align=\"center\"><a href=\"?p=webmember&action=modify&pid=$member_id\">";
            $admincontent .= "<img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a></td>\n";

            //$admincontent .= "<td>"._EDIT."</td>";
            //$admincontent .= "<td>"._DEL."</td>";
            $admincontent .= "</tr>\n";
        }

        $admincontent .= "<tr>";
        $admincontent .= "<td>";
        $admincontent .= "&nbsp;";
        $admincontent .= "</td>";
        $admincontent .= "<td valign=\"top\" colspan=\"$jumlah_kolom\">";
        if ($status == 0) {
            $event = "document.myform.elements['action'].value='aktivasi'; document.getElementById('myform').submit(); ";
            $admincontent .= "<input class=\"btn btn-success\" type=\"button\" value=\"" . _ACTIVATION . "\" onclick=\"$event\" />\r\n";
            $admincontent .= "&nbsp;&nbsp;";

            if ($is_public_activation) {
                $event = "document.myform.elements['action'].value='resendcode'; document.getElementById('myform').submit(); ";
                $admincontent .= "<input class=\"btn btn-info\" type=\"button\" value=\"" . _RESENDCODE . "\" onclick=\"$event\" />\r\n";
                $admincontent .= "&nbsp;&nbsp;";
            }

            $event = "document.myform.elements['action'].value='ban'; document.getElementById('myform').submit(); ";
            $admincontent .= "<input class=\"btn btn-danger\" type=\"button\" value=\"" . _BAN . "\" onclick=\"$event\" />\r\n";
            $admincontent .= "&nbsp;&nbsp;";
        }
        if ($status == 1) {
            $event = "document.myform.elements['action'].value='ban'; document.getElementById('myform').submit(); ";
            $admincontent .= "<input class=\"btn btn-danger\" type=\"button\" value=\"" . _BAN . "\" onclick=\"$event\" />\r\n";
            $admincontent .= "&nbsp;&nbsp;";
        }
        if ($status == 3) {
            $event = "document.myform.elements['action'].value='aktivasi'; document.getElementById('myform').submit(); ";
            $admincontent .= "<input class=\"btn btn-success\" type=\"button\" value=\"" . _RESTORE . "\" onclick=\"$event\" />\r\n";
            $admincontent .= "&nbsp;&nbsp;";
        }
        $event = "document.myform.elements['action'].value='remove'; document.getElementById('myform').submit(); ";
        $admincontent .= "<input class=\"btn btn-warning\" type=\"button\" value=\"" . _DELETE . "\"  onclick=\"$event\" />\r\n";
        $admincontent .= "</td>";
        $admincontent .= "</tr>";
        $admincontent .= "</table>";
        $admincontent .= "</form>";
    } else {
        if ($action == 'search') {
            $admincontent .= createstatus(_NOSEARCHRESULTS, "error");
        } else {
            $admincontent .= createstatus(_NOMEMBER, "error");
        }
    }
    if ($action == '') {
        $specialadmin .= "<a class=\"btn btn-danger\" href=\"?p=webmember&action=add\">" . _ADDWEBMEMBER . "</a>\n";
    }
}

if ($action == 'aktivasi') {
    $daftar_id = $_POST["cekMember"];
    $daftar_id = checkrequired($daftar_id, _NOCHECK);
    $list_id = implode(",", $daftar_id);
    $sql = "UPDATE $tabelwebmember SET activity='1',activedate=NOW(), bandate='' WHERE user_id IN ($list_id) ";
    if (!mysql_query($sql)) {
        header("Location:?p=$modulename&notification=dberror");
    } else {
        header("Location:?p=$modulename&r=$random&notification=activationsuccess");
    }
}

if ($action == 'resendcode') {
    $option = "From: $client_email";

    $daftar_id = $_POST["cekMember"];
    $daftar_id = checkrequired($daftar_id, _NOCHECK);
    $list_id = implode(",", $daftar_id);
    $sql = "SELECT * FROM $tabelwebmember WHERE user_id IN ($list_id) ";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        while ($data_member = mysql_fetch_array($result)) {
            $postemail = $data_member["user_email"];
            $activationcode = $data_member["activationcode"];
            include("$cfg_app_path/modul/$namamodul/lang/$lang/mailactvcode.php");
            fiestomail($postemail, $subject, $message, $option, $islocal);
        }
    }
    header("Location:?p=$modulename&r=$random&notification=sendcodensuccess");
}

if ($action == 'ban') {
    $daftar_id = $_POST["cekMember"];
    $daftar_id = checkrequired($daftar_id, _NOCHECK);
    $list_id = implode(",", $daftar_id);
    $sql = "UPDATE $tabelwebmember SET activity='0',bandate=NOW() WHERE user_id IN ($list_id) ";
    if (!mysql_query($sql)) {
        header("Location:?p=$modulename&notification=dberror");
    } else {
        header("Location:?p=$modulename&r=$random&notification=bansuccess");
    }
}

if ($action == 'remove') {
    $daftar_id = $_POST["cekMember"];
    $daftar_id = checkrequired($daftar_id, _NOCHECK);
    $admintitle = _DELMEMBER;
    $admincontent .= "<h5>" . _PROMPTDEL . "</h5>";
    $admincontent .= "<form name=\"myform\" id=\"myform\" method=\"POST\" action=\"$thisfile\" >";
    $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"hapus\">";
    foreach ($daftar_id as $key => $value) {
        $admincontent .= "<input type=\"hidden\" name='cekMember[]' id='cekMember[]'  value='" . $value . "'>";
    }
    $admincontent .= "<div class=\"control-group\"><div class=\"controls\">";
    $admincontent .= "<a class=\"btn btn-danger\" href=\"javascript:document.myform.submit();\">" . _YES . "</a> "
            . "<a class=\"btn\" href=\"?p=$modulename\">" . _NO . "</a>";
    $admincontent .= "</div></div>";
    $admincontent .= "</form>";
}

if ($action == 'hapus') {
    $daftar_id = $_POST["cekMember"];
    $daftar_id = checkrequired($daftar_id, _NOCHECK);
    $list_id = implode(",", $daftar_id);
    /*
      $sql = "SELECT filename FROM $tabelwebmember WHERE user_id IN ($list_id) ";
      $result=mysql_query($sql);
      list($filename) = mysql_fetch_row($result);
      if ($filename!='' && file_exists("$cfg_fullsizepics_path/$filename")) unlink("$cfg_fullsizepics_path/$filename");
      if ($filename!='' && file_exists("$cfg_thumb_path/$filename")) unlink("$cfg_thumb_path/$filename");
     */
    $sql = "DELETE FROM $tabelwebmember WHERE user_id IN ($list_id) ";
    $result = mysql_query($sql);
    if ($result) {
        header("Location:?p=$modulename&notification=delsuccess");
    } else {
        header("Location:?p=$modulename&notification=dberror");
    }
}

if ($action == 'add') {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($postuser, _USERNAME, false);
        $notificationbuilder .= validation($postpass1, _PASSWORD, false);
        $notificationbuilder .= validation($postpass2, _RETYPEPASS, false);
        $notificationbuilder .= validation($postnama, _NAME, false);
        //$notificationbuilder .= validation($postperusahaan, _COMPANY, false);
        $notificationbuilder .= validation($postalamat1, _ADDRESS, false);
        //$notificationbuilder .= validation($postalamat2, _ADDRESS2, false);
        $notificationbuilder .= validation($postkota, _CITY, false);
        //$notificationbuilder .= validation($postkodepos, _ZIP, false);
        $notificationbuilder .= validation($posttelepon, _TELP1, false);
        //$notificationbuilder .= validation($postponsel1, _MOBILE1, false);
        //$notificationbuilder .= validation($postponsel2, _MOBILE2, false);
        //$notificationbuilder .= validation($postfax, _FAX, false);
        $notificationbuilder .= emailvalidation($postemail, true);
        if ($postuser != null || $postuser != "") {
            //cek apakah username 3-12 karakter
            if (strlen($postuser) < 3 || strlen($postuser) > 12 || !preg_match("/^[A-Za-z0-9][A-Za-z0-9]*(_[A-Za-z0-9]+)*$/", $postuser)) {
                $notificationbuilder .= _INVALIDUSERNAME . "!<br/>";
            }
        }
        if ($postpass1 != null || $postpass1 != "") {
            if ($postpass2 != null || $postpass2 != "") {
                if ($postpass1 != $postpass2) {
                    $notificationbuilder .= _PASSWORDANDRETYPENOTSAME . "!<br/>";
                }
            }
            if (strlen($postpass1) < 6) {
                $notificationbuilder .= _CORRECTPASS . "!<br/>";
            }
            $pos = strpos($postpass1, $postuser);
            if ($pos !== false) {
                $notificationbuilder .= _USERINPASSWORD . "!<br/>";
            }
        }
        //cek apakah password termasuk kata-kata terlarang
        foreach ($prohibitedpasses as $prohibitedpass) {
            if (strtolower($postpass1) == $prohibitedpass) {
                $notificationbuilder .= _PROHIBITEDPASS . "!<br/>";
                break;
            }
        }
        //cek apakah username sudah terpakai
        $sql = "SELECT user_id FROM $tabelwebmember WHERE username='$postuser'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $notificationbuilder .= _USERNAME . " " . _ALREADYEXIST . "!<br/>";
        }
        //cek apakah email sudah terpakai
        $sql = "SELECT user_id FROM $tabelwebmember WHERE user_email='$postemail'";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $notificationbuilder .= _EMAIL . " " . _ALREADYEXIST . "!<br/>";
        }
        $cookedpass = fiestopass($postpass1);
        if ($notificationbuilder != "") {
            $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
        } else {
            $sql = "INSERT INTO $tabelwebmember SET username='$postuser', 
				user_password='$cookedpass', fullname='$postnama', level='1',
				company='$postperusahaan', address1='$postalamat1', activity='0', 
				address2='$postalamat2', city='$postkota', zip='$postkodepos',
				telephone='$posttelepon', cellphone1='$postponsel1', 
				cellphone2='$postponsel2', fax='$postfax', user_email='$postemail',
				user_regdate=NOW()";
            $result = mysql_query($sql);

            if ($result) {
                $uid = mysql_insert_id();

                //mail kode aktivasi
                $activationcode = substr($cookedpass, 16, 16) . $uid . substr($cookedpass, 0, 16);
                $activationcode = md5(sha1($activationcode));

                include("$cfg_app_path/modul/$namamodul/lang/$lang/mailactvcode.php");
                $option = "From: $client_email";

                $sql = "UPDATE $tabelwebmember SET activationcode='" . $activationcode . "' 
				WHERE user_id='$uid' ";
                $result = mysql_query($sql);

                if ($is_public_activation) {
                    // fiestomail($postemail, $subject, $message, $option, $islocal);
                    $sqlmailcontact = "SELECT value FROM contact WHERE name='umbalemail'";
                    $resultcontact = mysql_query($sqlmailcontact);
                    list($emailadmin) = mysql_fetch_row($resultcontact);
                    fiestophpmailer($postemail, $subject, $message, $smtpuser, $smtpuser, $emailadmin);
                } else {
                    $sql = "UPDATE $tabelwebmember SET activity='1', activedate=NOW() 
					WHERE user_id='$uid' ";
                    $result = mysql_query($sql);
                }
                header("Location:?p=$modulename&r=$random&notification=addsuccess");
            } else {
                $builtnotification = createnotification(_DBERROR, _ERROR, "error");
            }
        }
    } else {
        /* default value untuk form */
        $postuser = "";
        $postpass1 = "";
        $postpass2 = "";
        $postnama = "";
        $postperusahaan = "";
        $postalamat1 = "";
        $postalamat2 = "";
        $postkota = "";
        $postkodepos = "";
        $posttelepon = "";
        $postponsel1 = "";
        $postponsel2 = "";
        $postfax = "";
        $postemail = "";

        $postpass1 = "fiesto";
        $postpass2 = "fiesto";
        $postnama = "Tester";
        $postperusahaan = "Fiesto";
        $postalamat1 = "Ngagel";
        $postalamat2 = "Ngagel 2";
        $postkota = "Surabaya";
        $postkodepos = "123456";
        $posttelepon = "031123456";
        $postponsel1 = "081123456";
        $postponsel2 = "081123457";
        $postfax = "031123456";
        $postemail = "test@test.com";
    }

    $admintitle = _ADDMEMBER;
    $admincontent .= '
	<form class=\"form-horizontal\" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            ' . $builtnotification . '
            <span class="label label-warning">' . _REQNOTE . '</span> 
            <div class="control-group">
                    <label class="control-label">' . _USERNAME . '*</label>
                    <div class="controls">
                            <input type="text" name="txtUser" placeholder="' . _USERNAME . '" class="span12" value=' . $postuser . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _PASSWORD . '*</label>
                    <div class="controls">
                            <input type="password" name="txtPass1" placeholder="' . _PASSWORD . '" class="span12" value=' . $postpass1 . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _RETYPEPASS . '*</label>
                    <div class="controls">
                            <input type="password" name="txtPass2" placeholder="' . _RETYPEPASS . '" class="span12" value=' . $postpass2 . '>
                    </div>
                    <div class="controls">
                        <span class="label label-warning">' . _CORRECTPASS . '</span> 
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _NAME . '*</label>
                    <div class="controls">
                            <input type="text" name="txtNama" placeholder="' . _NAME . '" class="span12" value=' . $postnama . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _COMPANY . '</label>
                    <div class="controls">
                            <input type="text" name="txtPerusahaan" placeholder="' . _COMPANY . '" class="span12" value=' . $postperusahaan . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _ADDRESS . '*</label>
                    <div class="controls">
                            <input type="text" name="txtAlamat1" placeholder="' . _ADDRESS . '" class="span12" value=' . $postalamat1 . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _ADDRESS2 . '</label>
                    <div class="controls">
                            <input type="text" name="txtAlamat2" placeholder="' . _ADDRESS2 . '" class="span12" value=' . $postalamat2 . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _CITY . '*</label>
                    <div class="controls">
                            <input type="text" name="txtKota" placeholder="' . _CITY . '" class="span12" value=' . $postkota . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _ZIP . '</label>
                    <div class="controls">
                            <input type="text" name="txtKodepos" placeholder="' . _ZIP . '" class="span12" value=' . $postkodepos . '>
                    </div>
            </div>
             <div class="control-group">
                    <label class="control-label">' . _TELP1 . '*</label>
                    <div class="controls">
                            <input type="text" name="txtTelepon" placeholder="' . _TELP1 . '" class="span12" value=' . $posttelepon . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _MOBILE1 . '</label>
                    <div class="controls">
                            <input type="text" name="txtPonsel1" placeholder="' . _MOBILE1 . '" class="span12" value=' . $postponsel1 . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _MOBILE2 . '</label>
                    <div class="controls">
                            <input type="text" name="txtPonsel2" placeholder="' . _MOBILE2 . '" class="span12" value=' . $postponsel2 . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _FAX . '</label>
                    <div class="controls">
                            <input type="text" name="txtFax" placeholder="' . _FAX . '" class="span12" value=' . $postfax . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _EMAIL . '*</label>
                    <div class="controls">
                            <input type="text" name="txtEmail" placeholder="' . _EMAIL . '" class="span12" value=' . $postemail . '>
                    </div>
            </div>

            <div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="btn btn-danger" value="' . _ADD . '">
                            <input type="submit" name="back" class="btn" value="' . _BACK . '">
                    </div>
            </div>
	</form>
    ';
}

if ($action == "modify") {
    if (isset($_POST['submit'])) {
        if ($_POST['active'] == "profile") {
            $notificationbuilder = "";
            $notificationbuilder .= validation($postnama, _NAME, false);
            $notificationbuilder .= validation($postalamat1, _ADDRESS, false);
            $notificationbuilder .= validation($postkota, _CITY, false);
            $notificationbuilder .= validation($posttelepon, _TELP1, false);
            $notificationbuilder .= emailvalidation($postemail, true);
            //cek apakah email sudah terpakai
            $sql = "SELECT user_id FROM $tabelwebmember WHERE user_email='$postemail'";
            $result = mysql_query($sql);
            if (mysql_num_rows($result) > 0) {
                $notificationbuilder .= _EMAIL . " " . _ALREADYEXIST . "!<br/>";
            }
            //check for errors
            if ($notificationbuilder != "") {
                $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
            } else {
                $sql = "UPDATE $tabelwebmember SET fullname='$postnama',
			company='$postperusahaan', address1='$postalamat1', address2='$postalamat2',
			city='$postkota', zip='$postkodepos', telephone='$posttelepon', 
			cellphone1='$postponsel1', cellphone2='$postponsel2', fax='$postfax', 
			user_email='$postemail' WHERE user_id='$pid'";
                $result = mysql_query($sql);
                if ($result) {
                    header("Location:?p=$modulename&r=$random&notification=editsuccess");
                } else {
                    $builtnotification = createnotification(_DBERROR, _ERROR, "error");
                }
            }
            $activeProfile = "active";
            $activePassword = "";
        } else if ($_POST['active'] == "password") {
            $sql_get_username = "SELECT username FROM $tabelwebmember WHERE user_id='$pid'";
            $result_get_username = mysql_query($sql_get_username);
            $row = mysql_fetch_assoc($result_get_username);
            $notificationbuilder = "";
            $notificationbuilder .= validation($postpass1, _PASSWORD, false);
            $notificationbuilder .= validation($postpass2, _RETYPEPASS, false);
            if ($postpass1 != null || $postpass1 != "") {
                if ($postpass2 != null || $postpass2 != "") {
                    if ($postpass1 != $postpass2) {
                        $notificationbuilder .= _PASSWORDANDRETYPENOTSAME . "!<br/>";
                    }
                }
                if (strlen($postpass1) < 6) {
                    $notificationbuilder .= _CORRECTPASS . "!<br/>";
                }
                $pos = strpos($postpass1, $row['username']);
                if ($pos !== false) {
                    $notificationbuilder .= _USERINPASSWORD . "!<br/>";
                }
            }
            //cek apakah password termasuk kata-kata terlarang
            foreach ($prohibitedpasses as $prohibitedpass) {
                if (strtolower($postpass1) == $prohibitedpass) {
                    $notificationbuilder .= _PROHIBITEDPASS . "!<br/>";
                    break;
                }
            }
            //check for errors
            if ($notificationbuilder != "") {
                $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
            } else {
                $new_pass1 = fiestopass($new_pass1);
                $sql = "UPDATE $tabelwebmember SET user_password='$new_pass1' 
			WHERE user_id='$pid'";
                $result = mysql_query($sql);
                if ($result) {
                    header("Location:?p=$modulename&r=$random&notification=editsuccess");
                } else {
                    $builtnotification = createnotification(_DBERROR, _ERROR, "error");
                }
            }
            $activeProfile = "";
            $activePassword = "active";
        }
    } else {
        /* default value untuk form */
        $sql = "SELECT fullname, company, address1, address2, city, zip, telephone, 
				cellphone1, cellphone2, fax, user_email 
			FROM $tabelwebmember WHERE user_id='$pid'";
        $result = mysql_query($sql);
        list($postnama, $postperusahaan, $postalamat1, $postalamat2, $postkota, $postkodepos,
                $posttelepon, $postponsel1, $postponsel2, $postfax, $postemail) = mysql_fetch_row($result);
        $activeProfile = "active";
        $activePassword = "";
    }

    $admintitle = _EDITNONPASSWORD;
    $admincontent .= '
        <ul class="nav nav-tabs" id="myTab2">
                    <li class="' . $activeProfile . '"><a href="#profile">' . _EDITNONPASSWORD . '</a></li>
                    <li class="' . $activePassword . '"><a href="#password">' . _EDITPASSWORD . '</a></li>
            </ul>
            <div class="tab-content">
                    <div class="tab-pane ' . $activeProfile . '" id="profile">
                        <form class=\"form-horizontal\" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="modify">
                            <input type="hidden" name="active" value="profile">
                            ' . $builtnotification . '
                            <span class="label label-warning">' . _REQNOTE . '</span> 
                            <div class="control-group">
                                    <label class="control-label">' . _NAME . '*</label>
                                    <div class="controls">
                                            <input type="text" name="txtNama" placeholder="' . _NAME . '" class="span12" value=' . $postnama . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _COMPANY . '</label>
                                    <div class="controls">
                                            <input type="text" name="txtPerusahaan" placeholder="' . _COMPANY . '" class="span12" value=' . $postperusahaan . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _ADDRESS . '*</label>
                                    <div class="controls">
                                            <input type="text" name="txtAlamat1" placeholder="' . _ADDRESS . '" class="span12" value=' . $postalamat1 . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _ADDRESS2 . '</label>
                                    <div class="controls">
                                            <input type="text" name="txtAlamat2" placeholder="' . _ADDRESS2 . '" class="span12" value=' . $postalamat2 . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _CITY . '*</label>
                                    <div class="controls">
                                            <input type="text" name="txtKota" placeholder="' . _CITY . '" class="span12" value=' . $postkota . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _ZIP . '</label>
                                    <div class="controls">
                                            <input type="text" name="txtKodepos" placeholder="' . _ZIP . '" class="span12" value=' . $postkodepos . '>
                                    </div>
                            </div>
                             <div class="control-group">
                                    <label class="control-label">' . _TELP1 . '*</label>
                                    <div class="controls">
                                            <input type="text" name="txtTelepon" placeholder="' . _TELP1 . '" class="span12" value=' . $posttelepon . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _MOBILE1 . '</label>
                                    <div class="controls">
                                            <input type="text" name="txtPonsel1" placeholder="' . _MOBILE1 . '" class="span12" value=' . $postponsel1 . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _MOBILE2 . '</label>
                                    <div class="controls">
                                            <input type="text" name="txtPonsel2" placeholder="' . _MOBILE2 . '" class="span12" value=' . $postponsel2 . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _FAX . '</label>
                                    <div class="controls">
                                            <input type="text" name="txtFax" placeholder="' . _FAX . '" class="span12" value=' . $postfax . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _EMAIL . '*</label>
                                    <div class="controls">
                                            <input type="text" name="txtEmail" placeholder="' . _EMAIL . '" class="span12" value=' . $postemail . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <div class="controls">
                                            <input type="submit" name="submit" class="btn btn-danger" value="' . _EDIT . '">
                                            <input type="submit" name="back" class="btn" value="' . _BACK . '">
                                    </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane ' . $activePassword . '" id="password">
                        <form class=\"form-horizontal\" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="modify">
                            <input type="hidden" name="active" value="password">
                            ' . $builtnotification . '
                            <div class="control-group">
                                    <label class="control-label">' . _ENTERNEWPASSWORD1 . '*</label>
                                    <div class="controls">
                                            <input type="password" name="txtPass1" placeholder="' . _ENTERNEWPASSWORD1 . '" class="span12" value=' . $postpass1 . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <label class="control-label">' . _ENTERNEWPASSWORD2 . '</label>
                                    <div class="controls">
                                            <input type="password" name="txtPass2" placeholder="' . _ENTERNEWPASSWORD2 . '" class="span12" value=' . $postpass2 . '>
                                    </div>
                            </div>
                            <div class="control-group">
                                    <div class="controls">
                                            <input type="submit" name="submit" class="btn btn-danger" value="' . _EDIT . '">
                                            <input type="submit" name="back" class="btn" value="' . _BACK . '">
                                    </div>
                            </div>
                        </form>
                    </div>
            </div>
            ';
}

//if ($specialadmin == '') {
//    $specialadmin = "<a class=\"btn\" href=\"?p=webmember\">" . _BACK . "</a>";
//}
?>
