<?php

$sort = "title";

if ($jenis == 'category' || $jenis == 'random' || $jenis == 'featuredmarquee') {
    //default value harus ada!!! default: ""
    $default_value = "";
    $sql = 'SELECT id FROM bannercat ORDER BY urutan ASC LIMIT 1';
    $result = mysql_query($sql);
    while (list($id) = mysql_fetch_row($result)) {
        $default_value = $id;
    }
    //default value harus ada!!! default: ""
	ob_start();
	echo "
	  <div class='control-group'>
		<label class='control-label'>". _CATEGORY . "</label>
		<div class='controls'>";
	 $sql = 'SELECT id, nama FROM bannercat ORDER BY urutan';
    $result = mysql_query($sql);
   
    echo "<select name=\"homeconfig[]\">\r\n";
    $selected = ($isi == 0) ? 'selected' : '';
    while (list($id, $nama) = mysql_fetch_row($result)) {
        $selected = ($id == $isi) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$nama</option>";
    }
    
	echo "</select></div></div>";
	$modulurasi .=ob_get_clean();
}

if ($jenis == 'single') {
    //default value harus ada!!! default: ""
    $default_value = "";
    $sql = 'SELECT id FROM bannerdata ORDER BY urutan ASC LIMIT 1';
    $result = mysql_query($sql);
    while (list($id) = mysql_fetch_row($result)) {
        $default_value = $id;
    }
    //default value harus ada!!! default: ""
    $sql = 'SELECT id, title FROM bannerdata ORDER BY ' . $sort;
    $result = mysql_query($sql);
    $modulurasi .= "<div class=\"control-group\"><label class=\"control-label\">" . _BANNER . "</label>\r\n";
    $modulurasi .= "<div class=\"control\"><select name=\"homeconfig[]\">\r\n";
    $selected = ($isi == 0) ? 'selected' : '';
    while (list($id, $nama) = mysql_fetch_row($result)) {
        $selected = ($id == $isi) ? 'selected' : '';
        $modulurasi .= "<option value=\"$id\" $selected>$nama</option>";
    }
    $modulurasi .= '</select></div></div>';
}
?>
