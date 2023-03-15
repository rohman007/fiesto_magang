<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$sort = "title";

$keyword = fiestolaundry($_GET['keyword'], 100);
$screen = fiestolaundry($_GET['screen'], 11);
$pid = fiestolaundry($_REQUEST['pid'], 11);

$filetitle = fiestolaundry($_POST['filetitle'], 200);
$publish = fiestolaundry($_POST['publish'], 1);
$cat_id = fiestolaundry($_REQUEST['cat_id'], 11);
$action = fiestolaundry($_REQUEST['action'], 20);
$nama = fiestolaundry($_POST['nama'], 255);
$urutan = fiestolaundry($_POST['urutan'], 11);

$modulename = $_GET['p'];

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        //$notificationbuilder .= validation($filetitle, _FILETITLE, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            $sqlcat = "SELECT id FROM filecat WHERE id='$cat_id' ";
            $resultcat = $mysql->query($sqlcat);
            if ($mysql->num_rows($resultcat) == 0) {
                $action = createmessage(_CATEGORYERROR, _ERROR, "error", "add");
            }

            $namafile = $_FILES['filename']['name'];
            $ukuranfile = $_FILES['filename']['size'];
            $hasilupload = fiestoupload('filename', $cfg_file_path, '', $maxfilesize, $allowedfiletypes);
            if ($hasilupload == _SUCCESS) {
                $sql = "INSERT INTO filedata (cat_id, filename, filesize, title, urutan) VALUES ('$cat_id', '$namafile', '$ukuranfile', '$filetitle', '$urutan')";
                $result = $mysql->query($sql);
				$newid = $mysql->insert_id();
				if ($_FILES['thumbnail']['error'] != UPLOAD_ERR_NO_FILE) {
					$hasilupload = fiestoupload('thumbnail', $cfg_fullsizepics_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
					if ($hasilupload != _SUCCESS) $action = createmessage($hasilupload, _ERROR, "error", "add");
					//ambil informasi basename dan extension
					$temp = explode(".", $_FILES['thumbnail']['name']);
					$extension = $temp[count($temp) - 1];
					$basename = '';
					for ($i = 0; $i < count($temp) - 1; $i++) {
						$basename .= $temp[$i];
					}
					// kalsi tidak mau kalau nama file ada IDnya 
					$modifiedfilename = "$basename-$newid.$extension";
					// $modifiedfilename = "$basename.$extension";
						
					list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name']);
					
					//create thumbnail
					$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
					if ($hasilresize != _SUCCESS) {
						$action = createmessage($hasilresize, _ERROR, "error", "add");
					}

					if ($filewidth > $cfg_max_width) {

						//rename sambil resize gambar asli sesuai yang diizinkan
						$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name'], "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
						if ($hasilresize != _SUCCESS) {
							$action = createmessage($hasilresize, _ERROR, "error", "add");
						}

						//del gambar asli
						@unlink("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name']);
					} else {
						rename("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name'], "$cfg_fullsizepics_path/$modifiedfilename");
					}
					
					$sql = "UPDATE filedata SET thumbnail='$modifiedfilename' WHERE id='$newid'";
					if (!$mysql->query($sql)) $action = createmessage(_DBERROR, _ERROR, "error", "edit");						
				}
				
                if ($result) {
                    $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
                } else {
                    $action = createmessage(_DBERROR, _ERROR, "error", "add");
                }
            } else {
                $action = createmessage($hasilupload, _ERROR, "error", "add");
            }
        }
    } else {
        $action = "add";
    }
}

if ($action == "add") {
    $catselect = adminselectcategories('filecat');
    $admintitle = _ADDFILE;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
	  <input type="hidden" name="action" value="save">
		  <div class="control-group">
			<label class="control-label">' . _THUMBNAIL . '</label>
			<div class="controls">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height:  150px; line-height: 20px;"></div>
					<div>
						<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
						<input type="file" name="thumbnail"/>
						</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
				</div>
			</div>
		  </div>	  
		  <div class="control-group">
			<label class="control-label">' . _CATEGORY . '</label>
			<div class="controls">' . $catselect . '</div>
		  </div>
	      <div class="control-group">
	        <label class="control-label">' . _FILENAME . '</label>
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
	        <label class="control-label">' . _FILETITLE . '</label>
	        <div class="controls"><input type="text" name="filetitle" size="40"></div>
	      </div>';
    /* $admincontent .= '<tr>
      <td align="right" valign="top">'._ORDER.':</td>
      <td><input type="text" name="urutan" size="2" /></td>
      </tr>'; */
    $admincontent .= '<div class="control-group">
	        <div class="controls">
                    <input type="submit" name="submit" class="buton" value="' . _SAVE . '">
                    <a href="'.param_url("?p=$modulename").'" class="buton">' . _BACK . '</a>
                 </div>
	      </div>
	</form>
    ';
}

if ($action == "update") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        //$notificationbuilder .= validation($filetitle, _FILETITLE, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
        } else {
            $sqlcat = "SELECT id FROM filecat WHERE id='$cat_id' ";
            $resultcat = $mysql->query($sqlcat);
            if ($mysql->num_rows($resultcat) == 0) {
                $action = createmessage(_CATEGORYERROR, _ERROR, "error", "modify");
            }
			
			if ($_FILES['thumbnail']['error'] != UPLOAD_ERR_NO_FILE) {
				$hasilupload = fiestoupload('thumbnail', $cfg_fullsizepics_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
				if ($hasilupload != _SUCCESS) $action = createmessage($hasilupload, _ERROR, "error", "modify");

				//ambil informasi basename dan extension
				$temp = explode(".", $_FILES['thumbnail']['name']);
				$extension = $temp[count($temp) - 1];
				$basename = '';
				for ($i = 0; $i < count($temp) - 1; $i++) {
					$basename .= $temp[$i];
				}
				$modifiedfilename = "$basename-$pid.$extension";
					
				list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name']);
				
				//create thumbnail
				$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
				if ($hasilresize != _SUCCESS) {
					$action = createmessage($hasilresize, _ERROR, "error", "modify");
				}

				if ($filewidth > $cfg_max_width) {

					//rename sambil resize gambar asli sesuai yang diizinkan
					$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name'], "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
					if ($hasilresize != _SUCCESS) {
						$action = createmessage($hasilresize, _ERROR, "error", "modify");
					}

					//del gambar asli
					@unlink("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name']);
				} else {
					// echo "$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name'], "$cfg_fullsizepics_path/$modifiedfilename";
					rename("$cfg_fullsizepics_path/" . $_FILES['thumbnail']['name'], "$cfg_fullsizepics_path/$modifiedfilename");
				}
				
				$sql = "SELECT thumbnail FROM filedata WHERE id='$pid'";
				$result = $mysql->query($sql);
				list($oldfilename) = $mysql->fetch_row($result);
				if ($modifiedfilename != $oldfilename)  {
					@unlink("$cfg_fullsizepics_path/$oldfilename");
					@unlink("$cfg_thumb_path/$oldfilename");

					$sql = "UPDATE filedata SET thumbnail='$modifiedfilename' WHERE id='$pid'";
					if (!$mysql->query($sql)) $action = createmessage(_DBERROR, _ERROR, "error", "modify");
				}

			}
			
            $sql = "UPDATE filedata SET cat_id='$cat_id', title='" . $filetitle . "', urutan='$urutan' WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    } else {
        $action = "viewcat";
    }
}

if ($action == "modify") {
    $admintitle = _EDITFILE;

    $sql = "SELECT  id, cat_id, filename, filesize, title, clickctr, urutan, thumbnail FROM filedata WHERE id='$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOFILE, _INFO, "info", "");
    } else {

        list($id, $cat_id, $filename, $filesize, $title, $clickctr, $urutan, $thumbnail) = $mysql->fetch_row($result);
        $publishstatus = ($publish == 1) ? 'checked' : '';
        $catselect = adminselectcategories('filecat', $cat_id);
        $admincontent .= '
			<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
				<input type="hidden" name="action" value="update">
				<input type="hidden" name="pid" value="' . $pid . '">
				<div class="control-group">
					<label class="control-label">' . _THUMBNAIL . '</label>
					<div class="controls">
						<div class="fileupload fileupload-new" data-provides="fileupload">';
							if ($thumbnail!='' && file_exists("$cfg_fullsizepics_path/$thumbnail")) {
								$admincontent .= '
									<div class="fileupload-new thumbnail" style="width: 200px; height: ;">
										<img src="'.$cfg_thumb_url.'/'.$thumbnail.'" alt="img"/>
									</div>';
								$isFileEmpty = '<span>'._ISFILEEMPTY.'</span>';
							}
							$admincontent .= '<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height:  150px; line-height: 20px;"></div>
							<div>
								<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
								<input type="file" name="thumbnail"/>
								</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
							</div>
						</div>
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _CATEGORY . '</label>
					<div class="controls">' . $catselect . '</div>
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _FILENAME . '</label>
					<div class="controls">' . $filename . '</div>
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _FILESIZE . '</label>
					<div class="controls">' . convertbyte($filesize) . '</div>
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _FILETITLE . '</label>
					<div class="controls"><input type="text" name="filetitle" value="' . $title . '" size="40"></div>
				  </div>';
        /* $admincontent .= '<tr>
          <td align="right" valign="top">'._ORDER.':</td>
          <td><input type="text" name="urutan" value="'.$urutan.'" size="2" /></td>
          </tr>'; */
        $admincontent .= '<div class="control-group">
					<div class="controls"><input type="submit" name="submit" class="buton" value="' . _SAVE . '"> <a href="'.param_url("?p=$modulename").'" class="buton">' . _BACK . '</a></div>
				  </div>
			</form>';
    }
}

if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $admintitle = _DELFILE;
    $sql = "SELECT id, cat_id, filename, thumbnail FROM filedata WHERE id = '$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOFILE, _INFO, "info", "");
    } else {
        list($id, $cat_id, $filename, $thumbnail) = $mysql->fetch_row($result);
        if ($action == "remove") {
            $admincontent .="<h5>"._PROMPTDEL."</h5>";
            $admincontent .= "<a class=\"buton\" href=\"?p=download&action=delete&pid=$pid\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
        } else {
			
            $sql = "DELETE FROM filedata WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "viewcat");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "viewcat");
            }
            if ($filename != '' && file_exists("$cfg_file_path/$filename")) unlink($cfg_file_path . '/' . $filename);
            if ($thumbnail != '' && file_exists("$cfg_fullsizepics_path/$thumbnail")) unlink($cfg_fullsizepics_path . '/' . $thumbnail);
            if ($thumbnail != '' && file_exists("$cfg_thumb_path/$thumbnail")) unlink($cfg_thumb_path . '/' . $thumbnail);
        }
    }
}
// ADD/EDIT/DEL CATEGORY
// savecat, updatecat,delcat
if ($action == "savecat") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _CATNAME, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "catnew");
        } else {
            if (!preg_match('/[0-9]*/', $urutan))
                $urutan = 1;
            $mx = getMaxNumber('filecat', 'urutan') + 2;
            $sql = "INSERT filecat (nama, urutan) values ('$nama','$mx')";
            $result = $mysql->query($sql);
            $mid = $mysql->insert_id();
            $result = urutkan('filecat', $urutan, "", $mid, "");
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "catnew");
            }
        }
    } else {
        $action = "catnew";
    }
}

if ($action == "updatecat") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _CATNAME, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "catedit");
        } else {
            $nama = checkrequired($nama, _CATNAME);
            $cat_id = checkrequired($cat_id, _CATID);
            if (!preg_match('/[0-9]*/', $urutan))
                $urutan = 1;

            $kondisiprev = "";
            $kondisi = "";

            $sql = "UPDATE filecat set nama='$nama' WHERE id='$cat_id'";
            $result = $mysql->query($sql);

            $result = urutkan('filecat', $urutan, $kondisi, $cat_id, $kondisiprev);
            if ($kondisi != $kondisiprev) {
                urutkansetelahhapus('filecat', $kondisiprev);
            }

            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "catedit");
            }
        }
    } else {
        $action = "catedit";
    }
}

if ($action == "delcat") {
    if ($cat_id == '')
        $action = createmessage(_NOFILE, _ERROR, "error", "");

    $sql = "SELECT * FROM filecat ";
    $result = $mysql->query($sql);

    if ($mysql->num_rows($result) <= 1) {
        $action = createmessage(_1CAT, _ERROR, "error", "");
    }

    $sql = "DELETE FROM filecat WHERE id='$cat_id'";
    if (!$mysql->query($sql)) {
        $action = createmessage(_DBERROR, _ERROR, "error", "");
    } else {
        urutkansetelahhapus("filecat", "");
    }
    $sql = "DELETE FROM filedata WHERE cat_id='$cat_id'";
    if ($mysql->query($sql)) {
        $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
    } else {
        $action = createmessage(_DBERROR, _ERROR, "error", "");
    }
}

if ($action == "catnew" || $action == "catedit") {
    $admincontent .= "<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\">";
    if ($action == "catnew") {
        $admintitle = _ADDCAT;
        $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"savecat\" />";
    }
    if ($action == "catedit") {
        if (empty($cat_id))
            $action = createmessage(_NOCAT, _INFO, "info", "");
        $admintitle = _EDITCAT;
        $sql = "SELECT nama,urutan FROM filecat WHERE id=$cat_id ORDER BY urutan";
        $result = $mysql->query($sql);
        if ($mysql->num_rows($result) == 0)
            $action = createmessage(_NOCAT, _INFO, "info", "");
        $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"updatecat\" />";
        $admincontent .= "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\" />";
        list($cat_name, $cat_order) = $mysql->fetch_row($result);
    }
    $admincontent .= "<div class=\"control-group\"><label class=\"control-label\">" . _CATNAME . "</label><div class=\"controls\"><input type=\"text\" name=\"nama\" value=\"$cat_name\" /></div></div>\r\n";
    $admincontent .= "<div class=\"control-group\"><label class=\"control-label\">" . _ORDER . "</label><div class=\"controls\">";
    $admincontent .= "<div id=\"urutancontent\">";
    $admincontent .= createurutan("filecat", "", $cat_id);
    $admincontent .= "</div>";
    //$admincontent .= "<input type=\"text\" size=\"2\" name=\"urutan\" value=\"$cat_order\" />";
    $admincontent .= "</div></div>\r\n";
    $admincontent .= "<div class=\"control-group\"><div class=\"controls\">"
            ."<input type=\"submit\" name=\"submit\" class=\"buton\" value=\"" ._SAVE ."\" />"
            ."<a href=\"".param_url("?p=$modulename")."\" class=\"buton\">"._BACK."</a>"
            ."</div></div>\r\n";
    $admincontent .= "</form>\r\n";
}

if ($action == "catdel") {
    if (empty($cat_id))
        $action = createmessage(_NOCAT, _ERROR, "error", "");
    $admintitle = _DELCAT;
    $sql = "SELECT nama,urutan FROM filecat WHERE id=$cat_id ORDER BY urutan";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == 0)
        $action = createmessage(_NOCAT, _INFO, "info", "");
    $admincontent = "<h5>"._PROMPTDELCAT."</h5>";
    $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=download&action=delcat&cat_id=$cat_id")."\">" . _YES . "</a> &nbsp;<a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a>";
}

if ($action == "viewcat") {
    $sql = "SELECT id FROM filedata WHERE cat_id='$cat_id'";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOFILE, _INFO, "info", "");
    } else {
        $sql = "SELECT nama FROM filecat WHERE id='$cat_id'";
        $result = $mysql->query($sql);
        list($catname) = $mysql->fetch_row($result);
        $admintitle = $catname;
        $start = $screen * $max_page_list;
        $sql = "SELECT id, cat_id, filename, filesize, title, clickctr, urutan FROM filedata WHERE cat_id='$cat_id' ORDER BY $sort LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen);
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
        $admincontent .= "<tr>";
        //$admincontent .= "<th>"._ORDER."</th>";
        $admincontent .= "<th>" . _FILENAME . "</th><th>" . _FILESIZE . "</th><th>" . _FILETITLE . "</th><th>" . _FILEVISITED . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        $i = $start + 1;
        while (list($id, $cat_id, $filename, $filesize, $title, $clickctr, $urutan) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr>\n";
            //$admincontent .= "<td>$i</td>";
            $admincontent .= "<td>$filename</td><td align=\"right\">" . convertbyte($filesize) . "</td><td>$title</td><td>$clickctr</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
            $i++;
        }
        $admincontent .= "</table>";
    }

    $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=download&action=add")."\">" . _ADDFILE . "</a> <a class=\"buton\" href=\"".param_url("?p=download")."\">" . _BACK . "</a>";
}

if ($action == "") {
    $admincontent = adminlistcategories('filecat', 'download');
    $sqlcat = "SELECT id FROM filecat ";
    $resultcat = $mysql->query($sqlcat);

    $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=download&action=catnew")."\">" . _ADDCAT . "</a> ";
    if ($mysql->num_rows($resultcat) > 0) {
        $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=download&action=add")."\">" . _ADDFILE . "</a>";
    }
}
if ($action == "search") {
    $admintitle = _SEARCHRESULTS;

    $sql = "SELECT id FROM filedata WHERE filename LIKE '%$keyword%' OR title LIKE '%$keyword%'";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) > 0) {
        $admintitle = _SEARCHRESULTS;
        $sql = "SELECT nama FROM filecat WHERE id='$cat_id'";
        $result = $mysql->query($sql);
        list($catname) = $mysql->fetch_row($result);
        $admintitle = $catname;
        $start = $screen * $max_page_list;
        $sql = "SELECT id, cat_id, filename, filesize, title, clickctr, urutan FROM filedata WHERE filename LIKE '%$keyword%' OR title LIKE '%$keyword%' ORDER BY $sort LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen, "action=search&keyword=$keyword");
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
        $admincontent .= "<tr>";
        //$admincontent .= "<th>"._ORDER."</th>";
        $admincontent .= "<th>" . _FILENAME . "</th><th>" . _FILESIZE . "</th><th>" . _FILETITLE . "</th><th>" . _FILEVISITED . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        $i = $start + 1;
        while (list($id, $cat_id, $filename, $filesize, $title, $clickctr, $urutan) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr>\n";
            //$admincontent .= "<td>$i</td>";
            $admincontent .= "<td>$filename</td><td align=\"right\">" . convertbyte($filesize) . "</td><td>$title</td><td>$clickctr</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        createnotification(_NOSEARCHRESULTS, _INFO, "info");
    }
    $admincontent .= "<a href=\"".param_url("?p=$modulename")."\" class=\"buton\">" . _BACK . "</a>";
}
?>
