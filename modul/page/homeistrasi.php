<?php

if ($jenis == 'single') {
    //default value harus ada!!! default: ""
    $default_value = "";
    $sql = 'SELECT id FROM page ORDER BY judul ASC LIMIT 1';
    $result = $mysql->query($sql);
    while (list($id) = $mysql->fetch_row($result)) {
        $default_value = $id;
    }
    //default value harus ada!!! default: ""
    $sql = 'SELECT id, judul FROM page ORDER BY judul';
    $result = $mysql->query($sql);
    $modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">" . _TITLE . "</label>\r\n";
    $modulurasi .= "<div class=\"controls\"><select name=\"homeconfig[]\">\r\n";
    $selected = ($isi == 0) ? 'selected' : '';
    while (list($id, $judulhalaman) = $mysql->fetch_row($result)) {
        $selected = ($id == $isi) ? 'selected' : '';
        $modulurasi .= "<option value=\"$id\" $selected>$judulhalaman</option>";
    }
    $modulurasi .= '</select></div></div>';
}
?>