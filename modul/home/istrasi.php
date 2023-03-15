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
$posisi = fiestolaundry($_POST['posisi'], 1);
$isborder = fiestolaundry($_POST['isborder'], 1);
$judul = fiestolaundry($_POST['judul'], 100);
$isi = fiestolaundry($_POST['isi'], 0, TRUE);
$urutan = fiestolaundry($_POST['urutan'], 11);
$type = fiestolaundry($_REQUEST['type'], 11);
$homeconfig = $_POST['homeconfig'];

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    header("Location:?p=$modulename");
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
        <div id='trash' class='span12' style='width;100%;height:100%;border:1px solid #000;'>
            <div id='sortable-delete' class='connectedSortable' style='width;100%;height:100%;border:1px solid #000;'></div>
        </div>
        <div id='content' class='span3'>
                <ul id='sortable1' class='connectedSortable'>" . _HOMETYPEAVAILABLE;
    $sql_home = "SELECT id, namatampilan 
                FROM hometype 
                ORDER BY namatampilan";
    $result_home = $mysql->query($sql_home);
    while (list($id, $namatampilan) = $mysql->fetch_row($result_home)) {
        $admincontent .= "
            <li class='ui-state-default' id='$id'>
                <div class='list-setting'>
                    <div class='title'>$namatampilan</div>
                    <a href=''><i class='icon-cog'></i></a>
                </div>
            </li>";
    }
    $admincontent .= "</ul>
        </div>
        ";
    $admincontent .= "

        <div id='content2' class='span9'>
        <ul id='sortable3' class='connectedSortable homestickytop'>" . _HOMESTICKYTOP;
    $sql_home = "SELECT id, type, judul 
                FROM home 

                WHERE posisi = '$posisi2' 
                ORDER BY urutan";
    $result_home = $mysql->query($sql_home);
    while (list($id, $type, $namatampilan) = $mysql->fetch_row($result_home)) {
        $admincontent .= "
            <li class='ui-state-default' id='id_$id'>
                <div class='list-setting'>
                    <div class='title'>$namatampilan</div>
                    <a href='?p=home&action=edit&pid=$id'><i class='icon-cog'></i></a>
                </div>
            </li>";
    }

    $admincontent .= "
                </ul>
                <ul id='sortable2' class='homesortable connectedSortable homenormal'>" . _HOMENORMAL;
    $sql_home = "SELECT id, type, judul 
                FROM home 

                WHERE posisi = '$posisi1' 
                ORDER BY urutan";
    $result_home = $mysql->query($sql_home);
    while (list($id, $type, $namatampilan) = $mysql->fetch_row($result_home)) {
        $admincontent .= "
            <li class='ui-state-default' id='id_$id'>
                <div class='list-setting'>
                    <div class='title'>$namatampilan</div>
                    <a href='?p=home&action=edit&pid=$id'><i class='icon-cog'></i></a>
                </div>
            </li>";
    }

    $admincontent .= "
                </ul>
                <ul id='sortable4' class='homesortable connectedSortable homestickybottom'>" . _HOMESTICKYBOTTOM;
    $sql_home = "SELECT id, type, judul 
                FROM home 
                WHERE posisi = '$posisi3' 
                ORDER BY urutan";
    $result_home = $mysql->query($sql_home);
    while (list($id, $type, $namatampilan) = $mysql->fetch_row($result_home)) {
        $admincontent .= "
            <li class='ui-state-default' id='id_$id'>
                <div class='list-setting'>
                    <div class='title'>$namatampilan</div>
                    <a href='?p=home&action=edit&pid=$id'><i class='icon-cog'></i></a>
                </div>
            </li>";
    }

    $admincontent .= "
                </ul>
        </div>";
    $specialadmin = "";
}

if ($action == "edit") {
    if (empty($pid)) {
        $admincontent .= createstatus(_NOHOME, "error");
    }
    $sql = "SELECT h.id, h.type, h.judul, h.isi, h.posisi, h.isborder, h.urutan, ht.namatampilan FROM home h, hometype ht WHERE h.id=$pid AND h.type=ht.id";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == 0) {
        $admincontent .= createstatus(_NOHOME, "error");
    } else {
        if (isset($_POST['submit'])) {
            $notificationbuilder = "";
            $notificationbuilder .= validation($judul, _TITLE, false);
            if ($notificationbuilder != "") {
                $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
            } else {
                for ($i = 0; $i < count($homeconfig); $i++) {
                    $isi .= ($i == 0) ? $homeconfig[$i] : ';' . $homeconfig[$i];
                }

                if ($isborder != 1) {
                    $isborder = 0;
                }
                $sql = "UPDATE home SET judul='$judul', isi = '$isi', isborder = $isborder WHERE id = $pid";
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


    $sql = "SELECT id, modul, jenis, namatampilan, isconfigurable FROM hometype WHERE id='$type'";
    $result = $mysql->query($sql);
    list($id, $modul, $jenis, $namatampilan, $isconfigurable) = $mysql->fetch_row($result);

    $isborderchecked = ($isborder) ? 'checked' : '';
    if ($isconfigurable) {
        if (file_exists("../modul/$modul/lang/$lang/definisi.php")) {
            include("../modul/$modul/lang/$lang/definisi.php");
        }

        if (file_exists("../modul/$modul/homeistrasi.php")) {
            include("../modul/$modul/homeistrasi.php");
        }
    }

    $admintitle .= _EDITHOME;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
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
        $admincontent .= '' . $modulurasi . '';
    }
    $admincontent .= '
            <div class = "control-group">
                <div class = "controls">
                    <input type = "submit" name = "submit" class = "buton" value = "' . _EDIT . '" >
                    <input type = "submit" name = "back" class = "buton" value = "' . _BACK . '" >
                </div>
            </div>
            </form>
    ';
    $specialadmin = "";
}

if ($action == "del") {
    if (empty($pid))
        pesan(_ERROR, _NOHOME);
    $admintitle .= _DELHOME;
    $sql = "SELECT id, type FROM home WHERE id=$pid";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
        list ($id, $type) = $mysql->fetch_row($result);
        if ($type) {
            $admincontent = "<p>" . _PROMPTDEL . " <a href=\"?p=home&action=hapus&pid=$pid\">" . _YES . "</a> <a href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
        } else {
            pesan(_ERROR, _HOMECANTBEDELETED);
        }
    } else {
        pesan(_ERROR, _NOhome);
    }
}

if ($action == "baru") {
    if (!preg_match('/[0-9]*/', $urutan))
        $urutan = 1;
    for ($i = 0; $i < count($homeconfig); $i++) {
        $isi .= ($i == 0) ? $homeconfig[$i] : ';' . $homeconfig[$i];
    }
    $mx = getMaxNumber('home', 'urutan') + 2;
    $sql = "INSERT INTO home (type, judul, isi, posisi, isborder, urutan) values ('$type','$judul','$isi','$posisi','$isborder','$mx')";
    $result = $mysql->query($sql);
    $mid = $mysql->insert_id();

    $result = urutkan('home', $urutan, "posisi='" . $posisi . "' ", $mid, "posisi='" . $posisi . "' ");

    if ($result) {
        pesan(_SUCCESS, _DBSUCCESS, "?p=home&r=$random");
    } else {
        pesan(_ERROR, _DBERROR);
    }
}

if ($action == "ubah") {
    if (!preg_match('/[0-9]*/', $urutan))
        $urutan = 1;
    for ($i = 0; $i < count($homeconfig); $i++) {
        $isi .= ($i == 0) ? $homeconfig[$i] : ';' . $homeconfig[$i];
    }

    $sqlcek = "SELECT * FROM home WHERE id='$pid'";
    $resultcek = $mysql->query($sqlcek);
    $datacek = $mysql->fetch_array($resultcek);
    $kondisiprev = "posisi='" . $datacek["posisi"] . "' ";

    $kondisi = "posisi='" . $posisi . "' ";

    $sql = "UPDATE home SET judul='$judul', isi='$isi', posisi='$posisi', isborder='$isborder' WHERE id='$pid'";
    $result = $mysql->query($sql);

    $result = urutkan('home', $urutan, $kondisi, $pid, $kondisiprev);
    if ($kondisi != $kondisiprev) {
        urutkansetelahhapus('home', $kondisiprev);
    }
    if ($result) {
        pesan(_SUCCESS, _DBSUCCESS, "?p=home&r=$random");
    } else {
        pesan(_ERROR, _DBERROR);
    }
}

if ($action == "edithal") {
    $sql = "UPDATE home SET isi='$isi' WHERE type='0'";
    if ($mysql->query($sql)) {
        pesan(_SUCCESS, _DBSUCCESS, "?p=home&r=$random");
    } else {
        pesan(_ERROR, _DBERROR);
    }
}

if ($action == "hapus") {
    $pid = checkrequired($pid, _ID);
    $sql = "SELECT id, posisi FROM home WHERE id=$pid";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
        list ($id, $posisi) = $mysql->fetch_row($result);
        $sql = "DELETE FROM home WHERE id='$pid'";
        if ($mysql->query($sql)) {

            urutkansetelahhapus("home", "posisi='" . $posisi . "' ");

            pesan(_SUCCESS, _DBSUCCESS, "?p=home&r=$random");
        } else {
            pesan(_ERROR, _DBERROR);
        }
    } else {
        pesan(_ERROR, _NOHOME);
    }
}
?>
