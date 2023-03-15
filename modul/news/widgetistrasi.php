<?php

if ($jenis == 'newest' || $jenis == 'archive' || $jenis == 'carousel') {
    //default value harus ada!!! default: ""
    $default_value = "";
    $sql = 'SELECT id FROM newscat ORDER BY urutan ASC LIMIT 1';
    $result = $mysql->query($sql);
    while (list($id) = $mysql->fetch_row($result)) {
        $default_value = $id;
    }
    //default value harus ada!!! default: ""

    list($widgetcatid, $widgetmaxnews) = explode(';', $isi);
    $sql = 'SELECT id, nama FROM newscat ORDER BY urutan';
    $result = $mysql->query($sql);
    $modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">" . _CATEGORY . "</label>\r\n";
    $modulurasi .= "<div class=\"controls\"><select name=\"widgetconfig[]\">\r\n";
    $selected = ($widgetcatid == 0) ? 'selected' : '';
    $modulurasi .= "<option value=\"0\" $selected>" . _ALL . "</option>";
    while (list($id, $nama) = $mysql->fetch_row($result)) {
        $selected = ($id == $widgetcatid) ? 'selected' : '';
        $modulurasi .= "<option value=\"$id\" $selected>$nama</option>";
    }
    $modulurasi .= '</select></div></div>';
    if ($jenis == 'newest') {
        $modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">" . _MAXDISPLAYED . "</label>\r\n";
        $modulurasi .= "<div class=\"controls\"><input type=\"text\" size=\"2\" name=\"widgetconfig[]\" value=\"" . $widgetmaxnews . "\"></div></div>\r\n";
    }
}
?>
