<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$keyword = fiestolaundry($_GET['keyword'], 100);
$screen = fiestolaundry($_GET['screen'], 11);
if ($screen == '') {
    $screen = 0;
}

$pid = fiestolaundry($_REQUEST['pid'], 11);

$nama = fiestolaundry($_POST['nama'], 50);
$judul = fiestolaundry($_POST['judul'], 200);
$keterangan = fiestolaundry($_POST['keterangan'], 65536, TRUE);
$summary = fiestolaundry($_POST['summary'], 65536, TRUE);
$ishot = fiestolaundry($_POST['ishot'], 1);
$publish = fiestolaundry($_POST['publish'], 1);
$usia = fiestolaundry($_POST['usia'], 11);
$action = fiestolaundry($_REQUEST['action'], 20);

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    back($modulename);
}

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _NAME, false);
        // $notificationbuilder .= validation($judul, _JOB, false);
        $notificationbuilder .= validation($summary, _SUMMARY, false);
        $notificationbuilder .= validation($keterangan, _DESCRIPTION, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            //upload dulu sebelum insert record
            if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
                $hasilupload = fiestoupload('filename', $cfg_thumb_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
                if ($hasilupload != _SUCCESS) {
                    $action = createmessage($hasilupload, _ERROR, "error", "");
                }
                //ambil informasi basename dan extension
                $temp = explode(".", $_FILES['filename']['name']);
                $extension = $temp[count($temp) - 1];
                $basename = '';
                for ($i = 0; $i < count($temp) - 1; $i++) {
                    $basename .= $temp[$i];
                }
            }
            $sql = "INSERT INTO testi (nama, judul, summary, keterangan, publish, ishot, usia) VALUES ('$nama', '$judul', '$summary', '$keterangan', '$publish', '$ishot', '$usia')";
            $result = mysql_query($sql);
            if ($result) {
                if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
                    $newid = mysql_insert_id();
                    $modifiedfilename = "$basename-$newid.$extension";
                    $sql = "UPDATE testi SET filename='$modifiedfilename' WHERE id='$newid'";
                    if (!mysql_query($sql)) {
                        $action = createmessage(_DBERROR, _ERROR, "error", "add");
                    }
                }
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "add");
            }
            //pesan(_SUCCESS,_DBSUCCESS,"?p=testi&r=$random");
            $namafile = $_FILES['filename']['name'];
            //create thumbnail
            if ($result && $_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
                $hasilresize = fiestoresize("$cfg_thumb_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
                if ($hasilresize != _SUCCESS)
                    pesan(_ERROR, $hasilresize);
                //del gambar asli
                unlink("$cfg_thumb_path/" . $_FILES['filename']['name']);
            }
            $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
        }
    }else {
        $action = "add";
    }
}

if ($action == "add") {
    $admintitle = _ADDTESTI;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
	  <input type="hidden" name="action" value="save">
	      <div class="control-group">
	        <label class="control-label">' . _PHOTO . '</label>
			<div class="controls">
						<div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden" />
							<div class="input-append">
						<div class="uneditable-input span2">
							<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
						</div>
							<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Change</span>
							<input type="file" name="filename" >
						</span><a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
					</div>
				</div>
			</div>
	      </div>
	      <div class="control-group">
	        <label class="control-label">' . _NAME . '</label>
	        <div class="controls"><input type="text" name="nama" size="40" value=' . $nama . '></div>
	      </div>
	      <div class="control-group">
	        <label class="control-label">' . _AGE . '</label>
	        <div class="controls"><input type="text" name="usia" size="40" value=' . $usia . '></div>
	      </div>
		  <div class="control-group">
	        <label class="control-label">' . _JOB . '</label>
	        <div class="controls"><input type="text" name="judul" size="40" value=' . $judul . '> <!--<input type="checkbox" name="ishot" value="1" />Hot--></div>
	      </div>
		  <div class="control-group">
			<label class="control-label">' . _SUMMARY . '</label>
		    <div class="controls"><textarea class="usetiny" cols="60" rows="10" name="summary" >' . $summary . '</textarea></div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _DESCRIPTION . '</label>
		    <div class="controls"><textarea class="usetiny" cols="60" rows="20" name="keterangan" >' . $keterangan . '</textarea></div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _PUBLISHED . '</label>
			<div class="controls"><input type="checkbox" name="publish" value="1" checked></div>
		  </div>
		  <div class="control-group">
	        <div class="controls">
                    <input type="submit" name="submit" class="buton" value="' . _ADD . '">
                    <input type="submit" name="back" class="buton" value="' . _BACK . '">
                </div>
	      </div>
	</form>
    ';
}

if ($action == "update") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _NAME, false);
        // $notificationbuilder .= validation($judul, _JOB, false);
        $notificationbuilder .= validation($summary, _SUMMARY, false);
        $notificationbuilder .= validation($keterangan, _DESCRIPTION, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
                //upload
                $hasilupload = fiestoupload('filename', $cfg_thumb_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
                if ($hasilupload != _SUCCESS)
                    $action = createmessage($hasilupload, _ERROR, "error", "modify");
                //ambil informasi basename dan extension
                $temp = explode(".", $_FILES['filename']['name']);
                $extension = $temp[count($temp) - 1];
                $basename = '';
                for ($i = 0; $i < count($temp) - 1; $i++) {
                    $basename .= $temp[$i];
                }
                $modifiedfilename = "$basename-$pid.$extension";
                list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_thumb_path/" . $_FILES['filename']['name']);

                //create thumbnail
                $hasilresize = fiestoresize("$cfg_thumb_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
                if ($hasilresize != _SUCCESS)
                    $action = createmessage($hasilresize, _ERROR, "error", "modify");
                unlink("$cfg_thumb_path/" . $_FILES['filename']['name']);

                //del gambar yang dioverwrite (hanya jika filename beda)
                $sql = "SELECT filename FROM testi WHERE id='$pid'";
                $result = mysql_query($sql);
                list($oldfilename) = mysql_fetch_row($result);
                if ($modifiedfilename != $oldfilename && $oldfilename != '') {
                    unlink("$cfg_thumb_path/$oldfilename");
                }
                $sql = "UPDATE testi SET filename='$modifiedfilename', nama='$nama', 
			judul='$judul', summary='$summary', keterangan='$keterangan', ishot='$ishot', publish='$publish', usia='$usia' WHERE id='$pid'";
            } else {
                $sql = "UPDATE testi SET nama='$nama', 
			judul='$judul', publish='$publish', summary='$summary', keterangan='$keterangan', ishot='$ishot', usia='$usia' WHERE id='$pid'";
            }
            $result = mysql_query($sql);
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    } else {
        $action = "modify";
    }
}

if ($action == "modify") {
    $admintitle = _EDITTESTI;
    $sql = "SELECT  id, filename, nama, judul, publish, summary, keterangan, ishot, usia FROM testi WHERE id='$pid'";
    $result = mysql_query($sql);

    if (mysql_num_rows($result) > 0) {
//        $publishstatus = ($publish == 1) ? 'checked' : '';
        list($id, $filename, $nama, $judul, $publish, $summary, $keterangan, $ishot, $usia) = mysql_fetch_row($result);

        if ($filename != '')
            $admincontent .= '<img src="' . $cfg_thumb_url . '/' . $filename . '" alt="' . $judul . '">';

        if ($ishot == '1') {
            $hotchecked = "checked";
        } else {
            $hotchecked = "";
        }
        if ($publish == '1') {
            $publishchecked = "checked";
        } else {
            $publishchecked = "";
        }
        $admincontent .= '
    <form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
		  <input type="hidden" name="action" value="update">
		  <input type="hidden" name="pid" value="' . $pid . '">
                          
        <div class="control-group">
	        <label class="control-label">' . _PHOTO . '</label>
			<div class="controls">
						<div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden" />
							<div class="input-append">
						<div class="uneditable-input span2">
							<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
						</div>
							<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Change</span>
							<input type="file" name="filename" >
						</span><a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
					</div>
				</div>
			</div>
	      </div>
	      <div class="control-group">
	        <label class="control-label">' . _NAME . '</label>
	        <div class="controls"><input type="text" name="nama" size="40" value="' . $nama . '"></div>
	      </div>
	      <div class="control-group">
	        <label class="control-label">' . _AGE . '</label>
	        <div class="controls"><input type="text" name="usia" size="40" value=' . $usia . '></div>
	      </div>
            <div class="control-group">
	        <label class="control-label">' . _JOB . '</label>
	        <div class="controls"><input type="text" name="judul" size="40" value="' . $judul . '"> <!--<input type="checkbox" name="ishot" value="1" ' . $hotchecked . '/>Hot-->
	      </div>
	      </div>
            <div class="control-group">
			<label class="control-label">' . _SUMMARY . '</label>
		    <div class="controls"><textarea class="usetiny" cols="60" rows="10" name="summary" >' . $summary . '</textarea></div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _DESCRIPTION . '</label>
		    <div class="controls"><textarea class="usetiny" cols="60" rows="20" name="keterangan" >' . $keterangan . '</textarea></div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _PUBLISHED . '</label>
			<div class="controls"><input type="checkbox" name="publish" value="1" ' . $publishchecked . '></div>
		  </div>
		  <div class="control-group">
	        <div class="controls">
                    <input type="submit" name="submit" class="buton" value="' . _EDIT . '">
                    <input type="submit" name="back" class="buton" value="' . _BACK . '">
                </div>
	      </div>
            </form>
		';
    } else {
        $action = createmessage(_NOTESTI, _INFO, "info", "");
    }
}

if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $admintitle = _DELTESTI;
    $sql = "SELECT id, filename FROM testi WHERE id = '$pid'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == "0") {
        $action = createmessage(_NOTESTI, _INFO, "info", "");
    } else {
        list($id, $filename) = mysql_fetch_row($result);
        if ($action == "remove") {
            $admincontent .= _PROMPTDEL;
            $admincontent .= "<a class=\"buton\" href=\"?p=testi&action=delete&pid=$pid\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
        } else {
            if ($filename != '' && file_exists("$cfg_thumb_path/$filename"))
                unlink("$cfg_thumb_path/$filename");

            $sql = "DELETE FROM testi WHERE id='$pid'";
            $result = mysql_query($sql);
            if ($result) {
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            } else {
                pesan(_ERROR, _DBERROR);
                $action = createmessage(_DBERROR, _ERROR, "error", "");
            }
        }
    }
}
if ($action == "") {
    $admintitle = "";
    $sql = "SELECT id FROM testi ";
    $result = mysql_query($sql);
    $total_records = mysql_num_rows($result);
    $pages = ceil($total_records / $max_page_list);
    if (mysql_num_rows($result) == "0") {
        createnotification(_NOTESTI, _INFO, "info");
    } else {
        $start = $screen * $max_page_list;
        $sql = "SELECT id, nama, ishot FROM testi ORDER BY $sort LIMIT $start, $max_page_list";
        $result = mysql_query($sql);
        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen, "");
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _NAME . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($id, $nama, $ishot) = mysql_fetch_row($result)) {
            $admincontent .= "<tr class=\"newshotis$ishot\" valign=\"top\">\n";
            $admincontent .= "<td>$nama</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"?p=$modulename&action=modify&pid=$id\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$id\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    }
    $admincontent .= "<a href=\"?p=$modulename&action=add\" class=\"buton\">" . _ADDTESTI . "</a>";
}

if ($action == "search") {
    $admintitle = _SEARCHRESULTS;
    $sql = "SELECT id FROM testi WHERE nama LIKE '%$keyword%' OR judul LIKE '%$keyword%' OR keterangan LIKE '%$keyword%' OR summary LIKE '%$keyword%'";
    $result = mysql_query($sql);
    $total_records = mysql_num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if (mysql_num_rows($result) > 0) {
        $admintitle = _SEARCHRESULTS;
        $start = $screen * $max_page_list;
        $sql = "SELECT id, nama FROM testi 
			WHERE nama LIKE '%$keyword%' OR keterangan LIKE '%$keyword%' OR summary LIKE '%$keyword%'
			ORDER BY $sort LIMIT $start, $max_page_list";
        $result = mysql_query($sql);

        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen, "action=search&keyword=$keyword");
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _NAME . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        $i = $start + 1;
        while (list($id, $nama) = mysql_fetch_row($result)) {
            $admincontent .= "<tr class=\"newshotis$ishot\" valign=\"top\">\n";
            $admincontent .= "<td>$nama</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"?p=$modulename&action=modify&pid=$id\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$id\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        createnotification(_NOSEARCHRESULTS, _INFO, "info");
    }
    $admincontent .= "<a href=\"?p=$modulename\" class=\"buton\">" . _BACK . "</a>";
}
?>
