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

$action = fiestolaundry($_REQUEST['action'], 10);
$nama = fiestolaundry($_POST['nama'], 200);

//$notification = fiestolaundry(($_GET['notification']));

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    back($modulename);
}

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _BRAND, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            /*  $sql = "INSERT INTO catalogmerek (nama) VALUES ('$nama')";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "add");
            } */
			
			if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
                // $valid = createnotification(_FILEERROR1, _ERROR, "error"); //pesan(_ERROR,_FILEERROR1);
            
				//upload dulu sebelum insert record
                $hasilupload = fiestoupload('filename', $cfg_fullsizepics_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
                if ($hasilupload != _SUCCESS) {
                    $action = createmessage($hasilupload, _ERROR, "error", "add");
                }
				
				//ambil informasi basename dan extension
                $temp = explode(".", $_FILES['filename']['name']);
                $extension = $temp[count($temp) - 1];
                $basename = '';
                for ($i = 0; $i < count($temp) - 1; $i++) {
                    $basename .= $temp[$i];
                }

				//jika berhasil, baru add record
                $sql = "INSERT INTO catalogmerek (nama) VALUES ('$nama')";
                if ($mysql->query($sql)) {
                    $newid = $mysql->insert_id();
                    $modifiedfilename = "$basename-$newid.$extension";
                    $sql = "UPDATE catalogmerek SET filename='$modifiedfilename' WHERE id='$newid'";
                    if (!$mysql->query($sql)) {
                        $action = createmessage(_DBERROR, _ERROR, "error", "add");
                    }
                } else {
                    $action = createmessage(_DBERROR, _ERROR, "error", "add");
                }

                list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_fullsizepics_path/" . $_FILES['filename']['name']);

				//create thumbnail
                $hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
                if ($hasilresize != _SUCCESS) {
                    $action = createmessage($hasilresize, _ERROR, "error", "add");
                }
                if ($filewidth > $cfg_max_width) {

					//rename sambil resize gambar asli sesuai yang diizinkan
                    $hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
                    if ($hasilresize != _SUCCESS) {
                        $action = createmessage($hasilresize, _ERROR, "error", "add");
                    }
					//del gambar asli
                    unlink("$cfg_fullsizepics_path/" . $_FILES['filename']['name']);
                } else {
                    rename("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename");
                }
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
            } else {
				
				$sql = "INSERT INTO catalogmerek (nama) VALUES ('$nama')";
				$result = $mysql->query($sql);
				if ($result) {
					$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
				} else {
					$action = createmessage(_DBERROR, _ERROR, "error", "add");
				}
			}
        }
    } else {
        /* default value untuk form */
        $action = "";
    }
}
if ($action == "add") {
    $admintitle = _ADDBRAND;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
			<div class="control-group">
			<label class="control-label">' . _FILENAME . ':</label>
			<div class="controls">
				<div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden"  name="filename" size="35" />
					<div class="input-append">
						<div class="uneditable-input span2">
							<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
						</div>
						<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">'._CHANGE.'</span>
						<input type="file" name="filename" size="35">
						</span><a data-dismiss="fileupload" class=\btn fileupload-exists" href="#">'._REMOVE.'</a>
					</div>
				</div>
			</div>
			</div>
            <div class="control-group">
                    <label class="control-label">' . _BRAND . '</label>
                    <div class="controls">
                            <input type="text" name="nama" placeholder="' . _BRAND . '" class="span12">
                    </div>
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
    $sql = "SELECT id, nama FROM catalogmerek WHERE id='$pid'";
    $result = $mysql->query($sql);
    $result_list = $result;
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _BRAND, false);
        if ($notificationbuilder != "") {
            $builtnotification = createnotification($notificationbuilder, _ERROR, "error");
        } else {
            /* $sql = "UPDATE catalogmerek SET nama='$nama' WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            } */
			
			if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
//upload
                $hasilupload = fiestoupload('filename', $cfg_fullsizepics_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
                if ($hasilupload != _SUCCESS) {
                    $action = createmessage($hasilupload, _ERROR, "error", "modify");
                }
//ambil informasi basename dan extension
                $temp = explode(".", $_FILES['filename']['name']);
                $extension = $temp[count($temp) - 1];
                $basename = '';
                for ($i = 0; $i < count($temp) - 1; $i++) {
                    $basename .= $temp[$i];
                }
                $modifiedfilename = "$basename-$pid.$extension";

                list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_fullsizepics_path/" . $_FILES['filename']['name']);

//create thumbnail
                $hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
                if ($hasilresize != _SUCCESS) {
                    $action = createmessage($hasilresize, _ERROR, "error", "modify");
                }
                if ($filewidth > $cfg_max_width) {

//rename sambil resize gambar asli sesuai yang diizinkan
                    $hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
                    if ($hasilresize != _SUCCESS) {
                        $action = createmessage($hasilresize, _ERROR, "error", "modify");
                    }
//del gambar asli
                    unlink("$cfg_fullsizepics_path/" . $_FILES['filename']['name']);
                } else {
                    rename("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename");
                }

//del gambar yang dioverwrite (hanya jika filename beda)
                $sql = "SELECT filename FROM catalogmerek WHERE id='$pid'";
                $result = $mysql->query($sql);
                list($oldfilename) = $mysql->fetch_row($result);
                if ($modifiedfilename != $oldfilename) {
                    if ($oldfilename != '' && file_exists("$cfg_fullsizepics_path/$oldfilename")) unlink("$cfg_fullsizepics_path/$oldfilename");
                    if ($oldfilename != '' && file_exists("$cfg_thumb_path/$oldfilename")) unlink("$cfg_thumb_path/$oldfilename");
                }
                $sql = "UPDATE catalogmerek SET nama='$nama', filename='$modifiedfilename' WHERE id='$pid'";
            } else {
                $sql = "UPDATE catalogmerek SET nama='$nama' WHERE id='$pid'";
            }
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    } else {
        /* default value untuk form */
        $action = "modify";
    }
}

if ($action == "modify") {
    $sql = "SELECT id, nama, filename FROM catalogmerek WHERE id='$pid'";
    $result = $mysql->query($sql);

    $admintitle = _EDITBRAND;
    if ($mysql->num_rows($result) > 0) {
        list($id, $merek, $filename) = $mysql->fetch_row($result);
		if ($filename != '')
            $admincontent .= "<p class='align-center'><img src=\"$cfg_thumb_url/$filename\" /></p>\r\n";
        $admincontent .= '
		  <form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="pid" value="' . $pid . '">
						<div class="control-group">
						<label class="control-label">' . _FILENAME . ':</label>
						<div class="controls">
							<div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden"  name="filename" size="35" />
								<div class="input-append">
									<div class="uneditable-input span2">
										<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
									</div>
									<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">'._CHANGE.'</span>
									<input type="file" name="filename" size="35">
									</span><a data-dismiss="fileupload" class=\btn fileupload-exists" href="#">'._REMOVE.'</a>
								</div>
							</div>
						</div>
						</div>
                        <div class="control-group">
                                <label class="control-label">' . _BRAND . '</label>
                                <div class="controls">
                                    <input type="text" name="nama" value="' . $merek . '" placeholder="' . _BRAND . '" class="span12">
                                </div>
                        </div>
                        <div class="control-group">
                                <div class="controls">
                                        <input type="submit" name="submit" class="buton" value="' . _EDIT . '">
                                        <input type="submit" name="back" class="buton" value="' . _BACK . '">
                                </div>
                        </div>
		</form>';
    } else {
        $admincontent .= createstatus(_NOBRAND, "error");
    }
}

// DELETE BRAND
if ($action == "remove" || $action == "delete") {
    $admintitle = _DELBRAND;
    $sql = "SELECT id, nama, filename FROM catalogmerek WHERE id='$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOBRAND, _INFO, "info", "");
    } else {
        list($id, $merek, $filename) = $mysql->fetch_row($result);
        if ($action == "remove") {
            $admincontent .= "<h5>" . _PROMPTDEL . "</h5>";
            $admincontent .= '<div class="control-group">
                                <div class="controls">
                                        <a class="buton" href="?p=' . $modulename . '&action=delete&pid=' . $id . '">' . _YES . '</a>
                                        <a class="buton" href="javascript:history.go(-1)">' . _NO . '</a></p>
                                </div>
                        </div>';
        } else {
			
			if ($filename != '' && file_exists("$cfg_fullsizepics_path/$filename")) unlink("$cfg_fullsizepics_path/$filename");
            if ($filename != '' && file_exists("$cfg_thumb_path/$filename")) unlink("$cfg_thumb_path/$filename");
					
            $sql = "DELETE FROM catalogmerek WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    }
}
if ($action == "") {
    $admintitle = "";
    $sql = "SELECT id FROM catalogmerek";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

//    if ($notification == "addsuccess") {
//        $admincontent .= createnotification(_ADDSUCCESS, _SUCCESS, "success");
//    } else if ($notification == "editsuccess") {
//        $admincontent .= createnotification(_EDITSUCCESS, _SUCCESS, "success");
//    } else if ($notification == "delsuccess") {
//        $admincontent .= createnotification(_DELETESUCCESS, _SUCCESS, "success");
//    } else if ($notification == "dberror") {
//        $admincontent .= createnotification(_DBERROR, _ERROR, "error");
//    }

    if ($mysql->num_rows($result) > 0) {
        $start = $screen * $max_page_list;

        $sql = "SELECT id, nama FROM catalogmerek ORDER BY nama LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen);
        }
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
        $admincontent .= "<tr><th>" . _BRAND . "</th>";
        // $admincontent .= "<th align=\"center\">" . _EDIT . "</th><th >" . _DEL . "</th></tr>\n";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($id, $merek) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr class=\"merek\">\r\n";
            $admincontent .= "<td>$merek</td>\r\n";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"?p=$modulename&action=modify&pid=$id\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$id\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        createnotification(_NOBRAND, _INFO, "info");
    }
    $admincontent .= "<a href=\"?p=$modulename&action=add\" class=\"buton\">" . _ADDBRAND . "</a>";
}

if ($action == 'search') {
    $admintitle = _SEARCHRESULTS;
    $sql = "SELECT id, nama FROM catalogmerek WHERE (nama LIKE '%$keyword%') ORDER BY nama";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) > 0) {
        $start = $screen * $max_page_list;
        $sql = "SELECT id, nama FROM catalogmerek WHERE (nama LIKE '%$keyword%') ORDER BY nama LIMIT $start, $max_page_list ";
        $result = $mysql->query($sql);
        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen);
        }
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
        $admincontent .= "<tr><th>" . _BRAND . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($id, $merek) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr class=\"merek\">\n";
            $admincontent .= "<td>$merek</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"?p=$modulename&action=modify&pid=$id\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$id\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {//menampilkan record di halaman yang sesuai
        createnotification(_NOSEARCHRESULTS, _INFO, "info");
    }
    $admincontent .= "<a href=\"?p=$modulename\" class=\"buton\">" . _BACK . "</a>";
}
?>
