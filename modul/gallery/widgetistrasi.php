<?php

if (substr($jenis, -8) == 'category') {
    //default value harus ada!!! default: ""
    $default_value = "0";
    $sql = 'SELECT id FROM gallerycat ORDER BY urutan ASC LIMIT 1';
    $result = $mysql->query($sql);
    while (list($id) = $mysql->fetch_row($result)) {
        $default_value = $id;
    }
    //default value harus ada!!! default: ""

    list($widgetcatid) = explode(';', $isi);
    $sql = 'SELECT id, nama FROM gallerycat ORDER BY urutan';
	
    $result = $mysql->query($sql);
    $modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">" . _CATEGORY . "</label>\r\n";
    $modulurasi .= "<div class=\"controls\"><select name=\"widgetconfig[]\">\r\n";
    $selected = ($widgetcatid == 0) ? 'selected' : '';
	$modulurasi .= "<option value=\"0\">/</option>";
    while (list($id, $nama) = $mysql->fetch_row($result)) {
        $selected = ($id == $widgetcatid) ? 'selected' : '';
        $modulurasi .= "<option value=\"$id\" $selected>$nama</option>";
    }
    $modulurasi .= '</select></div></div>';
}
?>
