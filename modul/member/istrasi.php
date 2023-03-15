<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}
$keyword = fiestolaundry($_GET['keyword'], 100);
$screen = fiestolaundry($_GET['screen'], 11);
$pid = fiestolaundry($_REQUEST['pid'], 11);

$action = fiestolaundry($_REQUEST['action'], 20);
$newusername = fiestolaundry($_POST['newusername'], 16);
$password1 = fiestolaundry($_POST['password1'], 100);
$password2 = fiestolaundry($_POST['password2'], 100);
$newfullname = fiestolaundry($_POST['newfullname'], 40);
$newlevel = fiestolaundry($_POST['newlevel'], 2);
$modthis = fiestolaundry($_POST['modthis'], 1);
$fullnameedited = fiestolaundry($_POST['fullnameedited'], 40);
$leveledited = fiestolaundry($_POST['leveledited'], 2);

$old_pass = fiestolaundry($_POST['old_pass']);
$new_pass1 = fiestolaundry($_POST['new_pass1']);
$new_pass2 = fiestolaundry($_POST['new_pass2']);

$notification = fiestolaundry(($_GET['notification']));

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    header("Location:?p=$modulename");
}

if ($action == '') {
    if ($level == 1) {
        header("Location:?p=$modulename&action=restricted");
    }

    if ($notification == "addsuccess") {
        $admincontent .= createnotification(_ADDSUCCESS, _SUCCESS, "success");
    } else if ($notification == "editsuccess") {
        $admincontent .= createnotification(_EDITSUCCESS, _SUCCESS, "success");
    } else if ($notification == "delsuccess") {
        $admincontent .= createnotification(_DELETESUCCESS, _SUCCESS, "success");
    } else if ($notification == "dberror") {
        $admincontent .= createnotification(_DBERROR, _ERROR, "error");
    }

    $sql = "SELECT userid FROM users";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) > 0) {
        $start = $screen * $max_page_list;
        $sql = "SELECT userid, username, password, level, fullname FROM users ORDER BY level, fullname LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "");
        }
        $admincontent .= "<table class=\"stat-table\">\n";
        // $admincontent .= "<tr><th>" . _LEVEL . "</th><th>" . _NAME . "</th><th>" . _USERNAME . "</th><th align=\"center\">" . _EDIT . "</th><th align=\"center\">" . _DEL . "</th></tr>\n";
        $admincontent .= "<tr><th>" . _LEVEL . "</th><th>" . _NAME . "</th><th>" . _USERNAME . "</th><th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($cuserid, $cusername, $cpassword, $clevel, $cfullname) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr>\n";
            $admincontent .= "<td>$namalevel[$clevel]</td><td>$cfullname</td><td>$cusername</td>\n";
            // $admincontent .= "<td align=\"center\"><a href=\"?p=member&action=modify&pid=$cuserid\">";
            // $admincontent .= "<img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a></td>\n";
            // $admincontent .= "<td align=\"center\"><a href=\"?p=member&action=remove&pid=$cuserid\">";
            // $admincontent .= "<img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
			$admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"?p=$modulename&action=modify&pid=$cuserid\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$cuserid\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";		
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {//menampilkan record di halaman yang sesuai
        $admincontent .= createstatus(_NOUSER, "error");
    }
    $specialadmin .= "<a class=\"buton\" href=\"?p=member&action=add\">" . _ADDUSER . "</a>\n";
}

if ($action == 'search') {
    if ($level == 1) {
        header("Location:?p=$modulename&action=restricted");
    }
    $admintitle = _SEARCHRESULTS;

    $sql = "SELECT userid FROM users WHERE username LIKE '%$keyword%' OR fullname LIKE '%$keyword%'";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) > 0) {
        //menampilkan record di halaman yang sesuai
        $start = $screen * $max_page_list;
        $sql = "SELECT userid, username, password, level, fullname FROM users WHERE username LIKE '%$keyword%' OR fullname LIKE '%$keyword%' ORDER BY level, fullname LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "");
        }
        $admincontent .= "<table class=\"stat-table\">\n";
        $admincontent .= "<tr><th>" . _LEVEL . "</th><th>" . _NAME . "</th><th>" . _USERNAME . "</th><th>" . _EDIT . "</th><th>" . _DEL . "</th></tr>\n";
        while (list($cuserid, $cusername, $cpassword, $clevel, $cfullname) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr>\n";
            $admincontent .= "<td>$namalevel[$clevel]</td><td>$cfullname</td><td>$cusername</td>\n";
            $admincontent .= "<td align=\"center\"><a href=\"?p=member&action=modify&pid=$cuserid\">";
            $admincontent .= "<img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a></td>\n";
            $admincontent .= "<td align=\"center\"><a href=\"?p=member&action=remove&pid=$cuserid\">";
            $admincontent .= "<img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        $admincontent .= createstatus(_NOSEARCHRESULTS, "error");
    }
    $specialadmin = "<a href=\"?p=$modulename\" class=\"btn\">" . _BACK . "</a>";
}

if ($action == "add") {
    if ($level == 1) {
        header("Location:?p=$modulename&action=restricted");
    }
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($newusername, _USERNAME, false);
        $notificationbuilder .= validation($newfullname, _NAME, false);
        $notificationbuilder .= validation($password1, _PASSWORD, false);
        $notificationbuilder .= validation($password2, _RETYPEPASS, false);
        if (!preg_match('/^[A-Za-z0-9]\w*$/', $newusername)) {
            $notificationbuilder .= _USERNAMERULE . "!<br/>";
        }

        if ($password1 != $password2) {
            $notificationbuilder .= _WRONGNRETYPED . "!<br/>";
        }

        if ($password1 == $newusername) {
            $notificationbuilder .= _SAMEUSERPASS . "!<br/>";
        }

        $sql1 = $mysql->query("SELECT username FROM users WHERE username='$newusername'");
        if ($mysql->num_rows($sql1) > 0) {
            $notificationbuilder .= _ALREADYEXIST . "!<br/>";
        }

        if ($notificationbuilder != "") {
            $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
        } else {
            $password1 = fiestopass($password1);
            $sql = "INSERT INTO users (username, password, level, fullname) "
                    . "VALUES ('$newusername', '$password1', '$newlevel', '$newfullname')";
            $result = $mysql->query($sql);
            if ($result) {
                header("Location:?p=$modulename&notification=addsuccess");
            } else {
                $builtnotification = createnotification(_DBERROR, _ERROR, "error");
            }
        }
    } else {
        /* Bug fixed by Aly 12-02-2014 */
		
		/* default value untuk form */
        // $newusername = "";
        // $newfullname = 0;
        // $password1 = 0;
        // $password2 = 0;
		
    }

    $admintitle = _ADDUSER;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            ' . $builtnotification . '
            <div class="control-group">
                    <label class="control-label">' . _USERNAME . '</label>
                    <div class="controls">
                            <input type="text" name="newusername" placeholder="' . _USERNAME . '" class="span12" value=' . $newusername . '>
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _PASSWORD . '</label>
                    <div class="controls">
                            <input type="password" name="password1" placeholder="' . _PASSWORD . '" class="span12" value="' . $password1 . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _RETYPEPASS . '</label>
                    <div class="controls">
                            <input type="password" name="password2" placeholder="' . _RETYPEPASS . '" class="span12" value="' . $password2 . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _NAME . '</label>
                    <div class="controls">
                            <input type="text" name="newfullname" placeholder="' . _NAME . '" class="span12" value="' . $newfullname . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _LEVEL . '</label>
                    <div class="controls">
                            <select name="newlevel" class="span12">';
    $jumlahlevel = count($namalevel);
    for ($i = 0; $i < $jumlahlevel; $i++) {
        $admincontent .= ($i == $newlevel) ? "<option value=\"$i\" selected>$namalevel[$i]</option>" : "<option value=\"$i\">$namalevel[$i]</option>";
    }
    $admincontent .= '
		  </select>
                    </div>
            </div>
            <div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="buton" value="' . _ADD . '">
                            <input type="submit" name="back" class="btn" value="' . _BACK . '">
                    </div>
            </div>
	</form>
    ';
}

if ($action == "modify") {
    if ($level == 1) {
        header("Location:?p=$modulename&action=restricted");
    }

    $sql = "SELECT * FROM users WHERE userid='$pid'";
    $result = $mysql->query($sql);
    $result_list = $result;

    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($newfullname, _NAME, false);

        if ($notificationbuilder != "") {
            $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
        } else {
            if ($clevel == "0" && $leveledited != $clevel) {
                $sql = "SELECT * FROM users WHERE level='0'";
                $result = $mysql->query($sql);
                if ($mysql->num_rows($result) > 1) {
                    $sql = "UPDATE users SET fullname='$newfullname', level='$newlevel' WHERE userid='$pid'";
                    $result = $mysql->query($sql);
                } else {
                    $builtnotification = '<div class="alert alert-error">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <i class="icon-exclamation-sign"></i><strong>' . _ERROR . '!</strong><br/>
                                        ' . _1SUPERADMIN . '
                                  </div>';
                }
            } else {
                $sql = "UPDATE users SET fullname='$newfullname', level='$newlevel' WHERE userid='$pid'";
                $result = $mysql->query($sql);
            }
            if ($result) {
                header("Location:?p=$modulename&notification=editsuccess");
            } else {
                $builtnotification = createnotification(_DBERROR, _ERROR, "error");
            }
        }
    } else if (isset($_POST['resetpassword'])) {
        $newpassword = fiestopass($newusername);
        $sql1 = "UPDATE users SET password='$newpassword' WHERE userid='$pid'";
        $result = $mysql->query($sql1);
        if ($result) {
            $builtnotification = createnotification(_PASSRESET, _SUCCESS, "success");
        } else {
            $builtnotification = createnotification(_DBERROR, _ERROR, "error");
        }
    } else {
        /* default value untuk form */
        list($cuserid, $newusername, $password1, $newlevel, $newfullname) = $mysql->fetch_row($result);
    }

    $admintitle = _EDITUSER;
    if ($mysql->num_rows($result_list) > 0) {
        $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="newusername" value="' . $newusername . '">
            <input type="hidden" name="action" value="modify">
            ' . $builtnotification . '
            <div class="control-group">
                    <label class="control-label">' . _USERNAME . '</label>
                    <div class="controls">
                        <input type="text" disabled="disable" value="'.$newusername.'">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _NAME . '</label>
                    <div class="controls">
                            <input type="text" name="newfullname" placeholder="' . _NAME . '" class="span12" value="' . $newfullname . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _PASSWORD . '</label>
                    <div class="controls">';
					if (isset($_POST['resetpassword'])) {
						$admincontent .= '<input type="text" disabled="disable" value="'.$newusername.'">';
					}
		$admincontent .= '
                        <input type="submit" name="resetpassword" class="buton" value="' . _RESET . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _LEVEL . '</label>
                    <div class="controls">
                            <select name="newlevel" class="span12">';
        $jumlahlevel = count($namalevel);
        for ($i = 0; $i < $jumlahlevel; $i++) {
            $admincontent .= ($i == $newlevel) ? "<option value=\"$i\" selected>$namalevel[$i]</option>" : "<option value=\"$i\">$namalevel[$i]</option>";
        }

        $admincontent .= '
		  </select>
                    </div>
            </div>
            <div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="buton" value="' . _EDIT . '">
                            <input type="submit" name="back" class="btn" value="' . _BACK . '">
                    </div>
            </div>
	</form>
    ';
    } else {
        $admincontent .= createstatus(_NOUSER, "error");
    }
}

if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    if ($level == 1) {
        header("Location:?p=$modulename&action=restricted");
    }

    $admintitle = _DELUSER;
    // $sql = "SELECT id FROM users WHERE userid='$pid'";
    $sql = "SELECT userid FROM users WHERE userid='$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $admincontent .= createstatus(_NOUSER, "error");
    } else {
        list($id) = $mysql->fetch_row($result);
        if ($action == "remove") {
            $admincontent .= "<h5>" . _PROMPTDEL . "</h5>";
            $admincontent .= '<div class="control-group">
                                <div class="controls">
                                        <a class="buton" href="?p=' . $modulename . '&action=delete&pid=' . $id . '">' . _YES . '</a>
                                        <a class="btn" href="javascript:history.go(-1)">' . _NO . '</a></p>
                                </div>
                        </div>';
        } else {
            if ($clevel == "0") {
                $sql = "SELECT * FROM users WHERE level = '0'";
                $result = $mysql->query($sql);
                if ($mysql->num_rows($result) > 1) {
                    if ($userid != $pid) {
                        $sql = "DELETE FROM users WHERE userid='$pid'";
                        $result = $mysql->query($sql);
                        if ($result) {
                            //UPDATE TABEL LAIN YANG MENGANDUNG USERID YANG DIHAPUS --> TANGGUNG JAWAB DIAMBILALIH OLEH ADMIN YANG MENGHAPUS
                            /* dimatikan krn SWU diputuskan 1 level (14:15 12/02/2010), $dbtanggungjawab diambil dari config
                              foreach ($dbtanggungjawab as $namatabel) {
                              $sql1 = "UPDATE $namatabel SET penanggungjawab='$userid' WHERE penanggungjawab='$pid'";
                              $result1 = $mysql->query($sql1);
                              }
                             */
                            header("Location:?p=$modulename&notification=delsuccess");
                        } else {
                            header("Location:?p=$modulename&notification=dberror");
                        }
                    } else {
                        '<div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <i class="icon-exclamation-sign"></i><strong>' . _ERROR . '!</strong><br/>
                                    ' . _DBERROR . '
                              </div>';
                        pesan(_ERROR, _CANTDELSELF);
                    }
                } else {
                    pesan(_ERROR, _1SUPERADMIN);
                }
            } else {
                $sql = "DELETE FROM users WHERE userid='$pid'";
                $result = $mysql->query($sql);
                if ($result) {
                    //UPDATE TABEL LAIN YANG MENGANDUNG USERID YANG DIHAPUS --> TANGGUNG JAWAB DIAMBILALIH OLEH ADMIN YANG MENGHAPUS
                    /* dimatikan krn SWU diputuskan 1 level (14:15 12/02/2010), $dbtanggungjawab diambil dari config
                      foreach ($dbtanggungjawab as $namatabel) {
                      $sql1 = "UPDATE $namatabel SET penanggungjawab='$userid' WHERE penanggungjawab='$pid'";
                      $result1 = $mysql->query($sql1);
                      }
                     */
                    header("Location:?p=$modulename&notification=delsuccess");
                } else {
                    header("Location:?p=$modulename&notification=dberror");
                }
            }
        }
    }
}

if ($action == "editpass") {
	
    if (isset($_POST['submit'])) {
        $sql = "SELECT userid, password, username FROM users WHERE userid = $userid";
        $result = $mysql->query($sql);
        list($id, $password, $username) = $mysql->fetch_row($result);
		
        $notificationbuilder = "";
        $notificationbuilder .= validation($old_pass, _OLDPASSWORD, false);
        $notificationbuilder .= validation($new_pass1, _NEWPASSWORD1, false);
        $notificationbuilder .= validation($new_pass2, _NEWPASSWORD2, false);
		
        if (fiestopass($old_pass) != $password) {
            $notificationbuilder .= _WRONGOLDPASSWORD . "!<br/>";
        }

        if ($new_pass1 != $new_pass2) {
			die($new_pass1." != ".$new_pass2);
            $notificationbuilder .= _WRONGNRETYPED . "!<br/>";
        }
        if ($new_pass1 == $cusername) {
            $notificationbuilder .= _SAMEUSERPASS . "!<br/>";
        }
	
        if ($notificationbuilder != "") {
            $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
        } else {
            $new_pass1 = fiestopass($new_pass1);
            $sql = "UPDATE users SET password='$new_pass1' WHERE userid=$userid";
			
			
            $result = $mysql->query($sql);
			
            if ($result) {
                //header("Location:?p=$modulename&notification=addsuccess");
				$builtnotification = createnotification(_SUCCESSCHANGEPASSWORD, _SUCCESS, "success");
            } else {
                $builtnotification = createnotification(_DBERROR, _ERROR, "error");
            }
        }
			
    } else {
        /* default value untuk form */
        $old_pass = "";
        $new_pass1 = "";
        $new_pass2 = "";
    }

    $admintitle = _EDITPASSWORD;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="editpass">
            ' . $builtnotification . '
            <div class="control-group">
                    <label class="control-label">' . _OLDPASSWORD . '</label>
                    <div class="controls">
                            <input type="password" name="old_pass" placeholder="' . _OLDPASSWORD . '" class="span12" ">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _NEWPASSWORD1 . '</label>
                    <div class="controls">
                            <input type="password" name="new_pass1" placeholder="' . _NEWPASSWORD1 . '" class="span12" ">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _NEWPASSWORD2 . '</label>
                    <div class="controls">
                            <input type="password" name="new_pass2" placeholder="' . _NEWPASSWORD2 . '" class="span12" >
                    </div>
            </div>
            <div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="buton" value="' . _SAVE . '">
                            <!--<input type="submit" name="back" class="btn" value="' . _BACK . '">-->
                    </div>
            </div>
	</form>
    ';
	
}

if ($action == "restricted") {
    $admincontent .= "<h2>" . _NORIGHT . "</h2>";
}
?>