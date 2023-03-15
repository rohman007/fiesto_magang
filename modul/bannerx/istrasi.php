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

$bannertitle = fiestolaundry($_POST['bannertitle'], 200);
$bannerurl = fiestolaundry($_POST['bannerurl'], 300);
$publish = fiestolaundry($_POST['publish'], 1);

$cat_id = fiestolaundry($_REQUEST['cat_id'], 11);
$action = fiestolaundry($_REQUEST['action'], 10);
$nama = fiestolaundry($_POST['nama'], 200);
$urutan = fiestolaundry($_POST['urutan'], 11);
$slugurl = fiestolaundry($_POST['slugurl'],255);

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    back($modulename);
}
if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($bannertitle, _BANNERTITLE, false);
        if ($notificationbuilder != "") 
		{
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            if ($_FILES['filename']['error'] == UPLOAD_ERR_NO_FILE)
                $valid = createnotification(_FILEERROR1, _ERROR, "error"); //pesan(_ERROR,_FILEERROR1);
            $sqlcat = "SELECT id FROM bannercat WHERE id='" . $cat_id . "' ";
            $resultcat = $mysql->query($sqlcat);
            if ($mysql->num_rows($resultcat) == 0) {
                $action = createmessage(_CATEGORYERROR, _ERROR, "error", "add");
            }
            if ($valid != '') {
                $admincontent .= $valid;
                $action = "add";
            } else {
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
				if ($pakaislug) {
					$slugurl = $slugurl == '' ? seo_friendly_url($bannertitle, 'bannerdata', $pid) : seo_friendly_url($url, 'bannerdata', $pid);
				}
                $sql = "INSERT INTO bannerdata (cat_id, title, url, publish, urutan, slugurl) VALUES ('$cat_id', '$bannertitle', '$bannerurl', '$publish', '$urutan', '$slugurl')";
                if ($mysql->query($sql)) {
                    $newid = $mysql->insert_id();
                    $modifiedfilename = "$basename-$newid.$extension";
                    $sql = "UPDATE bannerdata SET filename='$modifiedfilename' WHERE id='$newid'";
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
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "viewcat");
            }
        }
    } else {
        $action = "add";
    }
}


if ($action == "add") {
    $get_cat_id = strlen($_GET['cat_id']) > 0 ? $_GET['cat_id'] : "";
    $catselect = adminselectcategories('bannercat', $get_cat_id);
    $admintitle = _ADDBANNER;
	
	if ($pakaislug) {
		$catcontent = '<div class="control-group">';
		$catcontent .= '<label class="control-label">'._SLUGURL.'</label>';
		$catcontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\"/></div>";
		$catcontent .= '</div>';
	}
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="?p=banner" enctype="multipart/form-data">
	  <input type="hidden" name="action" value="save">
		  <div class="control-group">
			<label class="control-label">' . _FILENAME . ':</label>
			<div class="controls">
				<div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden"  name="filename" size="35" />
					<div class="input-append">
						<div class="uneditable-input span2">
							<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
						</div>
						<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Change</span>
						<input type="file" name="filename" size="35">
						</span><a data-dismiss="fileupload" class=\btn fileupload-exists" href="#">Remove</a>
					</div>
				</div>
			</div>
		  </div>
		  <div class="control-group">
			<label class="control-label">' . _CATEGORY . ':</label>
			<div class="controls">' . $catselect . '</div>
		  </div>
	      <div class="control-group">
	        <label class="control-label">' . _URL . ':</label>
	        <div class="controls">http://<input type="text" name="bannerurl" size="40"></div>
	      </div>
	      <div class="control-group">
	        <label class="control-label">' . _BANNERTITLE . ':</label>
	        <div class="controls"><input type="text" name="bannertitle" size="40"></div>
	      </div>
		  '.$catcontent.'
	      <div class="control-group">
	        <label class="control-label">' . _PUBLISHED . ':</label>
	        <div class="controls"><input type="checkbox" name="publish" value="1" checked></div>
	      </div>';
    /* $admincontent .= '<tr>
      <div align="right" valign="top">'._ORDER.':</label>
      <div  class="controls"><input type="text" name="urutan" size="2" /></div>
      </div>'; */
    $admincontent .= '<div class="control-group">
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
        $notificationbuilder .= validation($bannertitle, _BANNERTITLE, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
        } else {
            $sqlcat = "SELECT id FROM bannercat WHERE id='" . $cat_id . "' ";
            $resultcat = $mysql->query($sqlcat);
            if ($mysql->num_rows($resultcat) == 0) {
                $action = createmessage(_CATEGORYERROR, _ERROR, "error", "modify");
            }

			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($jberita, 'newsdata', $pid) : seo_friendly_url($url, 'newsdata', $pid);
			}
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
                $sql = "SELECT filename FROM bannerdata WHERE id='$pid'";
                $result = $mysql->query($sql);
                list($oldfilename) = $mysql->fetch_row($result);
                if ($modifiedfilename != $oldfilename) {
                    unlink("$cfg_fullsizepics_path/$oldfilename");
                    unlink("$cfg_thumb_path/$oldfilename");
                }
                $sql = "UPDATE bannerdata SET cat_id='$cat_id', title='$bannertitle', url='$bannerurl', publish='$publish', urutan='$urutan', filename='$modifiedfilename', slugurl='$slugurl' WHERE id='$pid'";
            } else {
                $sql = "UPDATE bannerdata SET cat_id='$cat_id', title='$bannertitle', url='$bannerurl', publish='$publish', urutan='$urutan', slugurl='$slugurl' WHERE id='$pid'";
            }
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "viewcat");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    } else {
        $action = "modify";
    }
}

// MODIFY banner
if ($action == "modify") {
    $sql = "SELECT id, cat_id, title, url, filename, publish, urutan FROM bannerdata WHERE id='$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
        list($id, $cat_id, $title, $url, $filename, $publish, $urutan) = $mysql->fetch_row($result);
        $publishstatus = ($publish == 1) ? 'checked' : '';
        $catselect = adminselectcategories('bannercat', $cat_id);
        $admintitle = _EDITBANNER;
        if ($filename != '')
            $admincontent .= "<p class='align-center'><img src=\"$cfg_thumb_url/$filename\" /></p>\r\n";
        $admincontent .= '
			<form class="form-horizontal" method="POST" action="?p=banner" enctype="multipart/form-data">
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
						<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Change</span>
						<input type="file" name="filename" size="35">
						</span><a data-dismiss="fileupload" class=\btn fileupload-exists" href="#">Remove</a>
					</div>
				</div>
			</div>
		  </div>';
        if ($filename != '') {
            $admincontent .= '<div class="control-group"><div class="controls">' . _FRMPICEDITNOTE . '</div></div>';
        }
        $admincontent .= '
				  <div class="control-group">
					<label class="control-label">' . _CATEGORY . '</label>
					<div class="controls">' . $catselect . '</div>					
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _URL . '</label>
					<div class="controls">http://<input type="text" name="bannerurl" size="40" value="' . $url . '" /></div>					
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _BANNERTITLE . '</label>
					<div class="controls"><input type="text" name="bannertitle" size="40" value="' . $title . '" /></div>					
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _PUBLISHED . '</label>
					<div class="controls"><input type="checkbox" name="publish" value="1" ' . $publishstatus . ' /></div>					
				  </div>';
        /* $admincontent .= '<tr>
          <td align="right" valign="top">'._ORDER.':</td>
          <td><input type="text" name="urutan" size="2" value="'.$urutan.'" /></td>
          </tr>'; */
        $admincontent .= '
				  <div class="control-group">
					<div class="controls">
                                            <input type="submit" name="submit" class="buton" value="' . _EDIT . '">
                                            <input type="submit" name="back" class="buton" value="' . _BACK . '">
                                        </div>
				  </div>
			</form>';
    } else {
        $action = createmessage(_NOBANNER, _INFO, "info", "");
    }
}


// ADD/EDIT/DEL CATEGORY
if ($action == "savecat") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _CATNAME, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "catnew");
        } else {
            if (!preg_match('/[0-9]*/', $urutan))
                $urutan = 1;
            $mx = getMaxNumber('bannercat', 'urutan') + 2;
			if ($pakaislug) {
				$slugurl = $slugurl == '' ? seo_friendly_url($nama, 'bannercat', $pid) : seo_friendly_url($slugurl, 'bannercat', $pid);
			}
            $sql = "INSERT bannercat (nama, urutan, url) values ('$nama','$mx', '$slugurl')";
            $result = $mysql->query($sql);
            $mid = $mysql->insert_id();
            $result = urutkan('bannercat', $urutan, "", $mid, "");
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
            $action = createmessage($notificationbuilder, _ERROR, "error", "catnew");
        } else {
            $nama = checkrequired($nama, _CATNAME);
            $cat_id = checkrequired($cat_id, _CATID);
            if (!preg_match('/[0-9]*/', $urutan))
                $urutan = 1;

            $kondisiprev = "";
            $kondisi = "";
			if ($pakaislug) {
				$slugurl = $slugurl == '' ? seo_friendly_url($nama, 'bannercat', $cat_id) : seo_friendly_url($slugurl, 'bannercat', $cat_id);
			}
            $sql = "UPDATE bannercat set nama='$nama', url='$slugurl' WHERE id='$cat_id'";
            $result = $mysql->query($sql);

            $result = urutkan('bannercat', $urutan, $kondisi, $cat_id, $kondisiprev);
            if ($kondisi != $kondisiprev) {
                urutkansetelahhapus('bannercat', $kondisiprev);
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
if ($action == "deletecat") {

    $cat_id = checkrequired($cat_id, _CATID);
    $sql = "SELECT * FROM bannercat ";
    $result = $mysql->query($sql);

    if ($mysql->num_rows($result) <= 1) {
        $action = createmessage(_1CAT, _ERROR, "error", "");
    }

    $sql = "DELETE FROM bannercat WHERE id='$cat_id'";
    if (!$mysql->query($sql)) {
        $action = createmessage(_DBERROR, _ERROR, "error", "");
    } else {
        urutkansetelahhapus("bannercat", "");
    }
    $sql = "DELETE FROM bannerdata WHERE cat_id='$cat_id'";
    if ($mysql->query($sql)) {
        pesan(_SUCCESS, _DBSUCCESS, "");
        $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
    } else {
        $action = createmessage(_DBERROR, _ERROR, "error", "");
    }
}



if ($action == "catnew" || $action == "catedit") {
    $admincontent .= "<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\">";
    if ($action == "catnew") {
        $admintitle .= _ADDMAINCAT;
        $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"savecat\" />";
    }
    if ($action == "catedit") {
        if (empty($cat_id)) {
            $action = createmessage(_NOCAT, _ERROR, "error", "");
        }
        $admintitle .= _EDITCAT;
        $sql = "SELECT nama,urutan, url FROM bannercat WHERE id=$cat_id ORDER BY urutan";
        $result = $mysql->query($sql);
        if ($mysql->num_rows($result) == 0)
            $action = createmessage(_NOCAT, _ERROR, "error", "");
        $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"updatecat\" />";
        $admincontent .= "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\" />";
        list($cat_name, $cat_order, $cat_slugurl) = $mysql->fetch_row($result);
    }
    $admincontent .="<div class=\"control-group\"><label class=\"control-label\">" . _CATNAME . "</label><div class=\"controls\"><input type=\"text\" name=\"nama\" value=\"$cat_name\" /></div></div>\r\n";
	if ($pakaislug) {
		$admincontent .= '<div class="control-group">';
		$admincontent .= '<label class="control-label">'._SLUGURL.'</label>';
		$admincontent .= "<div class=\"controls\"><input type=\"text\" name=\"slugurl\" id=\"url\" value=\"".$cat_slugurl."\"/></div>";
		$admincontent .= '</div>';
	}
    $admincontent .= "<div class=\"control-group\"><label class=\"control-label\">" . _ORDER . "</label><div class=\"controls\">";
    $admincontent .= "<div id=\"urutancontent\">";
    $admincontent .= createurutan("bannercat", "", $cat_id);
    $admincontent .= "</div>";
    //$admincontent .= "<input type=\"text\" size=\"2\" name=\"urutan\" value=\"$cat_order\" />";
    $admincontent .= "</div></div>\r\n";
    $admincontent .= "<div class=\"control-group\"><div class=\"controls\">"
            . "<input type=\"submit\" name=\"submit\" class=\"buton\" value=\"" . _SAVE . "\" />"
            . "<input type=\"submit\" name=\"back\" class=\"buton\" value=\"" . _BACK . "\" />"
            . "</div></div>\r\n";
    $admincontent .= "</form>\r\n";
}

if ($action == "catdel") {
    if (empty($cat_id))
        $action = createmessage(_NOCAT, _ERROR, "error", "");

    $admintitle .= _DELCAT;
    $sql = "SELECT nama,urutan FROM bannercat WHERE id=$cat_id ORDER BY urutan";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == 0)
        $action = createmessage(_NOCAT, _ERROR, "error", "");
    $admincontent = _PROMPTDELCAT;
    $admincontent .= "<a class=\"buton\" href=\"?p=banner&action=deletecat&cat_id=$cat_id\">" . _YES . "</a> &nbsp;<a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a>";
}


if ($action == "viewcat") {

    $sql = "SELECT id FROM bannerdata WHERE cat_id='$cat_id'";

    $result = $mysql->query($sql);
    $total_records = $result->num_rows;
    $pages = ceil($total_records / $max_page_list);

    $sql = "SELECT nama FROM bannercat WHERE id='$cat_id'";
    $result = $mysql->query($sql);
    list($admintitle) = $result->fetch_row();
    if ($result->num_rows == "0") {
//        $admincontent .= _NOBANNER;
        $action = createmessage(_NOBANNER, _INFO, "info", "");
    } else {
        $titleurl = array();
        $titleurl["cat_id"] = $admintitle;

        $start = $screen * $max_page_list;
        $sql = "SELECT id, cat_id, title, url, clickctr, publish, urutan FROM bannerdata WHERE cat_id='$cat_id' ORDER BY $sort LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($mysql->num_rows($result) > 0) {
            if ($pages > 1)
                $adminpagination = pagination($namamodul, $screen, "action=viewcat&cat_id=$cat_id", $titleurl);
            $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
            $admincontent .= "<tr>";
            //$admincontent .= "<th>"._ORDER."</th>";
            $admincontent .= "<th>" . _BANNERDESC . "</th><th>" . _BANNERVISITED . "</th>";
            $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
            while (list($id, $catid, $title, $url, $clickctr, $publish, $urutan) = $mysql->fetch_row($result)) {
                $admincontent .= "<tr>\n";
                //$admincontent .= "<td>$urutan</td>";
                $admincontent .= "<td>$title</td><td>$clickctr</td>";
                $admincontent .= "<td align=\"center\" class=\"action-ud\">";
                $admincontent .= "<a href=\"?p=banner&action=modify&pid=$id\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
                $admincontent .= "<a href=\"?p=banner&action=remove&pid=$id\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
                $admincontent .= "</tr>\n";
            }
            $admincontent .= "</table>";
        } else {
            $action = createmessage(_NOBANNER, _INFO, "info", "viewcat");
        }
    }
    $admincontent .= "<a class=\"buton\" href=\"?p=banner&action=add&cat_id=$cat_id\">" . _ADDBANNER . "</a> <a class=\"buton\" href=\"?p=banner\">" . _BACK . "</a>";
}
// DELETE banner
if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $sql = "SELECT id, cat_id, filename FROM bannerdata WHERE id = '$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOBANNER, _INFO, "info", "");
    } else {
        if ($action == "remove") {
            $admintitle = _DELBANNER;
            $admincontent .= _PROMPTDEL;
            $admincontent .= "<a class=\"buton\" href=\"?p=banner&action=delete&pid=$pid\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
        } else {
            list($id, $cat_id, $filename) = $mysql->fetch_row($result);
            if ($filename != '' && file_exists("$cfg_fullsizepics_path/$filename")) unlink("$cfg_fullsizepics_path/$filename");
            $sql = "DELETE FROM bannerdata WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "");
            }
        }
    }
}
if ($action == "") {
    $admincontent = adminlistcategories('bannercat', 'banner');
    $sqlcat = "SELECT id FROM bannercat";
    $resultcat = $mysql->query($sqlcat);

    $admincontent .= "<a class=\"buton\" href=\"?p=banner&action=catnew\">" . _ADDCAT . "</a> ";
    if ($resultcat->num_rows > 0) {
        $admincontent .= "<a class=\"buton\" href=\"?p=banner&action=add\">" . _ADDBANNER . "</a>";
    } else {
        createnotification(_NOBANNER, _INFO, "info");
    }
}
?>
