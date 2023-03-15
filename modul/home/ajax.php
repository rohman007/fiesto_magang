<?php

Header('Cache-Control: no-cache, must-revalidate');
Header('Pragma: no-cache');
session_start();

include ("../../kelola/urasi.php");
include ("../../kelola/fungsi.php");
include ("../../kelola/lang/$lang/definisi.php");

$action = $_GET['action'];
if ($action == "save") {
    $id = trim($_GET['id']);
    $posisi = trim($_GET['posisi']);
    $sql = "SELECT `AUTO_INCREMENT`
            FROM  INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = '$cfg_db'
            AND TABLE_NAME   = 'home';";
    $result = $mysql->query($sql);
    $row = $mysql->fetch_row($result);
    $current_id = $row[0];
    $sql_free_home = "SELECT id, namatampilan 
                FROM hometype WHERE id = $id 
                ORDER BY namatampilan";
    $result_free_home = $mysql->query($sql_free_home);
    while (list($home_id, $namatampilan) = $mysql->fetch_row($result_free_home)) {
        /* GETTING DEFAULT_VALUE FOR EVERY WIDGET */
        $default_value = "";
        $sql = "SELECT modul, jenis, isconfigurable FROM hometype WHERE id='$home_id'";
        $result = $mysql->query($sql);
        list($modul, $jenis, $isconfigurable) = $mysql->fetch_row($result);
        if ($isconfigurable) {
            if (file_exists("../$modul/homeistrasi.php")) {
                include("../$modul/homeistrasi.php");
            }
        }
        /* GETTING DEFAULT_VALUE FOR EVERY WIDGET */

        $sql = "SELECT IFNULL(MAX(urutan),0)+1
            FROM  home
            WHERE posisi = '$posisi'";
        $result = $mysql->query($sql);
        $row = $mysql->fetch_row($result);
        $urutan = $row[0];
        $isborder = 1;
        if ($home_id != "" && $namatampilan != "" && $posisi != "") {
            $sql = "INSERT INTO home (type, judul, isi, posisi, isborder, urutan) "
                    . "values ('$home_id','$namatampilan','$default_value','$posisi','$isborder','$urutan')";
            $result = $mysql->query($sql);
        }
    }
    echo $current_id;
}

if ($action == "order") {
    parse_str($_REQUEST['sort'], $sort);
    $posisi = trim($_GET['posisi']);
    $index = 1;
    if (count($sort["id"]) > 0) {
        foreach ($sort["id"] as $sorted) {
            $sql = "UPDATE home SET urutan = $index, posisi='$posisi' WHERE id = $sorted";
            $result = $mysql->query($sql);
            $index++;
        }
    }
}

if ($action == "del") {
    $id = trim(str_replace("id_", "", $_GET['id']));
    if ($id != "") {
        $sql = "DELETE FROM home WHERE id='$id'";
        $result = $mysql->query($sql);
    }
}
?>