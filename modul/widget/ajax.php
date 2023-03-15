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
            AND TABLE_NAME   = 'widget';";
    $result = $mysql->query($sql);
    $row = $mysql->fetch_row($result);
    $current_id = $row[0];
    $sql_widget = "SELECT id, namatampilan  
                FROM widgettype WHERE id = $id 
                ORDER BY namatampilan";
    $result_widget = $mysql->query($sql_widget);
    while (list($widget_id, $namatampilan) = $mysql->fetch_row($result_widget)) {
        /* GETTING DEFAULT_VALUE FOR EVERY WIDGET */
        $default_value = "";
        $sql = "SELECT modul, jenis, isconfigurable FROM widgettype WHERE id='$widget_id'";
        $result = $mysql->query($sql);
        list($modul, $jenis, $isconfigurable) = $mysql->fetch_row($result);
        if ($isconfigurable) {
            if (file_exists("../$modul/widgetistrasi.php")) {
                include("../$modul/widgetistrasi.php");
            }
        }
        /* GETTING DEFAULT_VALUE FOR EVERY WIDGET */
        
        $sql = "SELECT IFNULL(MAX(urutan),0)+1
            FROM  widget
            WHERE posisi = '$posisi'";
        $result = $mysql->query($sql);
        $row = $mysql->fetch_row($result);
        $urutan = $row[0];
        $isborder = 1;
        if ($widget_id != "" && $namatampilan != "" && $posisi != "") {
            $sql = "INSERT INTO widget (type, judul, isi, posisi, isborder, urutan) "
                    . "values ('$widget_id','$namatampilan','$default_value','$posisi','$isborder','$urutan')";
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
            $sql = "UPDATE widget SET urutan = $index, posisi='$posisi' WHERE id = $sorted";
            $result = $mysql->query($sql);
            $index++;
        }
    }
}

if ($action == "del") {
    $id = trim(str_replace("id_", "", $_GET['id']));
    if ($id != "") {
        $sql = "DELETE FROM widget WHERE id='$id'";
        $result = $mysql->query($sql);
    }
}
?>