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

$cat_id = fiestolaundry($_REQUEST['cat_id'], 11);
$parent = fiestolaundry($_REQUEST['parent'], 11);
if ($parent == "") {
    $parent = 0;
}
$action = fiestolaundry($_REQUEST['action'], 20);

$title = fiestolaundry($_POST['title'], 255);
$publish = fiestolaundry($_POST['publish'], 1);
$ishot = fiestolaundry($_POST['ishot'], 1);

$nama = fiestolaundry($_POST['nama'], 200);
$cat_desc = fiestolaundry($_POST['cat_desc'], 255);
$urutan = fiestolaundry($_POST['urutan'], 11);
$url = fiestolaundry($_POST['url'],255);

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    back($modulename);
}

$additional = count($customfield);
for ($i = 0; $i < $additional; $i++) {
    switch ($typecustom[$i]) {
        case 'TINYINT':
            $maxlength = 4;
            break;
        case 'SMALLINT':
            $maxlength = 6;
            break;
        case 'MEDIUMINT':
            $maxlength = 8;
            break;
        case 'INT':
            $maxlength = 11;
            break;
        case 'DECIMAL':
            $splitparameter = explode(",", $paracustom[$i]);
            $maxlength = $splitparameter[0] + 1; //akomodasi tanda minus
            break;
        case 'VARCHAR':
            $maxlength = $paracustom[$i];
            break;
        case 'DOLLAR':
            $maxlength = 9;
            break;
        case 'RUPIAH':
            $maxlength = 13;
            break;
        case 'TEXT':
            $maxlength = 65535;
            break;
        case 'ENUM':
            $splitparameter = explode("/", $paracustom[$i]);
            $maxlength = 0;
            foreach ($splitparameter as $pilihan) {
                if (strlen($pilihan) > $maxlength) {
                    $maxlength = strlen($pilihan);
                }
            }
            break;
        case 'DATE':
            $maxlength = 10;
            break;
        case 'DATETIME':
            $maxlength = 19;
            break;
    }
    $$customfield[$i] = fiestolaundry($_POST[$customfield[$i]], $maxlength, TRUE);
}

if ($action == "save") {
	$valid=true;
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
       // $notificationbuilder .= validation($title, _TITLE, false);
        // if ($notificationbuilder != "") {
            // $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        // } else {
		if ($_FILES['filename']['error'] == UPLOAD_ERR_NO_FILE) {
			$action = createmessage(_FILEERROR1, _ERROR, "error", "add");
			$valid=false;
		}
			
		if($valid)
		{

            //$title = checkrequired($title,_TITLE);
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
            $tambahan = "";
            $tambahaninsert = "";
            for ($i = 0; $i < $additional; $i++) {
                if ($i == 0) {
                    $tambahan .= "$customfield[$i]";
                    $tambahaninsert .= "'$" . $customfield[$i] . "'";
                } else {
                    $tambahan .= ", $customfield[$i]";
                    $tambahaninsert .= ", '$" . $customfield[$i] . "'";
                }
            }
            eval("\$tambahaninsert = \"$tambahaninsert\";");
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($title, 'gallerydata', $pid) : seo_friendly_url($url, 'gallerydata', $pid);
			}
            if ($additional > 0) {
                $sql = "INSERT INTO gallerydata (cat_id, date, title, publish, ishot, url, $tambahan) VALUES ('$cat_id', NOW(), '$title', '$publish', '$ishot', '$url', $tambahaninsert)";
            } else {
                $sql = "INSERT INTO gallerydata (cat_id, date, title, publish, ishot, url) VALUES ('$cat_id', NOW(), '$title', '$publish', '$ishot', '$url')";
            }
            if ($mysql->query($sql)) {
                $newid = $mysql->insert_id();
                $modifiedfilename = "$basename-$newid.$extension";
                $sql = "UPDATE gallerydata SET filename='$modifiedfilename' WHERE id='$newid'";
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

            $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");
			
        }
    } else {
        $action = "add";
    }
}

// ----------------------------------------------
// OVERWRITE OLD IMAGE DATA
// ----------------------------------------------

if ($action == "update") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($title, _TITLE, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            //$title = checkrequired($title,_TITLE);
            $tambahan = "";
            $tambahanupdate = "";
            for ($i = 0; $i < $additional; $i++) {
                if ($i == 0) {
                    $tambahan .= "$customfield[$i]";
                    $tambahanupdate .= "$customfield[$i]='$" . $customfield[$i] . "'";
                } else {
                    $tambahan .= ", $customfield[$i]";
                    $tambahanupdate .= ", $customfield[$i]='$" . $customfield[$i] . "'";
                }
            }
            eval("\$tambahanupdate = \"$tambahanupdate\";");
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($title, 'gallerydata', $pid) : seo_friendly_url($url, 'gallerydata', $pid);
			}
            if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {

                //upload
                $hasilupload = fiestoupload('filename', $cfg_fullsizepics_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
                if ($hasilupload != _SUCCESS) {
//            pesan(_ERROR, $hasilupload);
                    $action = createmessage($hasilupload, _ERROR, "error", "edit");
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
                    $action = createmessage($hasilresize, _ERROR, "error", "edit");
                }

                if ($filewidth > $cfg_max_width) {

                    //rename sambil resize gambar asli sesuai yang diizinkan
                    $hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
                    if ($hasilresize != _SUCCESS) {
                        $action = createmessage($hasilresize, _ERROR, "error", "edit");
                    }
                    //del gambar asli
                    unlink("$cfg_fullsizepics_path/" . $_FILES['filename']['name']);
                } else {
                    //create thumbnail
                    $hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
                    if ($hasilresize != _SUCCESS) {
                        $action = createmessage($hasilresize, _ERROR, "error", "edit");
                    }

                    rename("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename");
                }

                //del gambar yang dioverwrite (hanya jika filename beda)
                $sql = "SELECT filename FROM gallerydata WHERE id='$pid'";
                $result = $mysql->query($sql);
                list($oldfilename) = $mysql->fetch_row($result);
                if ($modifiedfilename != $oldfilename) {
                    if ($oldfilename != '' && file_exists("$cfg_fullsizepics_path/$oldfilename")) unlink("$cfg_fullsizepics_path/$oldfilename");
                    if ($oldfilename != '' && file_exists("$cfg_thumb_path/$oldfilename")) unlink("$cfg_thumb_path/$oldfilename");
                }
                if ($additional > 0) {
                    $sql = "UPDATE gallerydata SET cat_id='$cat_id', filename='$modifiedfilename', title='$title', publish='$publish', ishot='$ishot', url='$url', $tambahanupdate WHERE id='$pid'";
                } else {
                    $sql = "UPDATE gallerydata SET cat_id='$cat_id', filename='$modifiedfilename', title='$title', publish='$publish', ishot='$ishot', url='$url' WHERE id='$pid'";
                }
            } else {
                if ($additional > 0) {
                    $sql = "UPDATE gallerydata SET cat_id='$cat_id', title='$title', publish='$publish', ishot='$ishot', url='$url', $tambahanupdate WHERE id='$pid'";
                } else {
                    $sql = "UPDATE gallerydata SET cat_id='$cat_id', title='$title', publish='$publish', ishot='$ishot', url='$url' WHERE id='$pid'";
                }
            }
			
            if ($mysql->query($sql)) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");
				
				
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "edit");
				
            }
        }
    } else {
        $action = "edit";
    }
}

// ----------------------------------------------
// PRINT IMAGE UPLOAD FORM
// ----------------------------------------------

if (($action == "add") || ($action == "edit")) {
	$valid=true;
    if ($action == "add" && empty($cat_id) && $cat_id!=0) {
        $action = createmessage(_NOCAT, _INFO, "info", "");
    }
    if ($action == "edit" && empty($pid)) {
		$valid=false;
        $action = createmessage(_NOPHOTO, _INFO, "error", "");
    }
	if($valid)
	{
    $tambahan = "";
    for ($i = 0; $i < $additional; $i++) {
        if ($i == 0) {
            $tambahan .= "$customfield[$i]";
        } else {
            $tambahan .= ", $customfield[$i]";
        }
    }

    // if we're editing, get the image details
    if ($action == "edit") 
	{
        $admintitle .= _EDITPHOTO;
        if ($additional > 0) {
            $sql = "SELECT id, filename, date, title, publish, ishot,cat_id, url, $tambahan FROM gallerydata WHERE id='$pid'";
        } else {
            $sql = "SELECT id, filename, date, title, publish, ishot,cat_id, url FROM gallerydata WHERE id='$pid'";
        }
        $getpic = $mysql->query($sql);
        $hasil = $mysql->fetch_array($getpic);
    } else {
        $admintitle .= _ADDPHOTO;
    }
	
    $catcontent .= '<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">';
//    $catcontent .= '<form class="form-horizontal" action=\"$thisfile\" name=\"mainform\" method=\"POST\" enctype=\"multipart/form-data\">';
    // if we're adding an image, print the browse box
    if ($action == "edit") {
        if ($hasil['filename'] != '') {
            $catimg .= '<img src="' . $cfg_fullsizepics_url . '/' . $hasil['filename'] . '" ' . $imagesize . ' alt="' . $hasil['title'] . '">';
        } else {
            $catimg .= '<img src="' . $cfg_app_url . '/images/none.gif" border="0" />';
        }
    }

    //prevnext
    $sql = "SELECT id, title, filename FROM gallerydata";
    if ($action == "edit" || $action == "add") {
        if ($cat_id != '') {
            $sql .= " WHERE (cat_id='$cat_id')";
        }
        if ($keyword != '') {
            $sql .= " WHERE ((title LIKE '%$keyword%') $searchtambahan)";
        }
    }
    $sql .= " ORDER BY cat_id ASC, date DESC, title ASC";
    $result = $mysql->query($sql);
    $num = $mysql->num_rows($result);
    for ($i = 0; $i < $num; $i++) {
        if ($mysql->result($result, $i, "id") == $pid) {
            if ($i != 0) {
                $showprevid = $mysql->result($result, $i - 1, "id");
                $showprevname = $mysql->result($result, $i - 1, "filename");
                $showprevtitle = $mysql->result($result, $i - 1, "title");
            } else {
                $showprevid = -1;
            }
            if ($i != $num - 1) {
                $shownextid = $mysql->result($result, $i + 1, "id");
                $shownextname = $mysql->result($result, $i + 1, "filename");
                $shownexttitle = $mysql->result($result, $i + 1, "title");
            } else {
                $shownextid = -1;
            }

            $tempthumb = $cfg_thumb_url;
            if ($showprevid != -1) {
                if ((file_exists($cfg_thumb_path . "/" . $showprevname)) && ($showprevname != '')) {
                    $image_stats = getimagesize($cfg_thumb_path . "/" . $showprevname);
                    $new_w = $image_stats[0];
                    $new_h = $image_stats[1];
                    $if_thumb = "yes";
                } elseif ((!file_exists($cfg_thumb_path . "/" . $showprevname)) && (file_exists($cfg_fullsizepics_path . "/" . $showprevname)) && ($showprevname != '')) {
                    $image_stats = getimagesize($cfg_fullsizepics_path . "/" . $showprevname);
                    $imagewidth = $image_stats[0];
                    $imageheight = $image_stats[1];
                    $img_type = $image_stats[2];
                    if ($imagewidth > $imageheight) {
                        $new_w = $cfg_thumb_width;
                        $ratio = ($imagewidth / $cfg_thumb_width);
                        $new_h = round($imageheight / $ratio);
                    } else {
                        $new_h = $cfg_thumb_width;
                        $ratio = ($imageheight / $cfg_thumb_width);
                        $new_w = round($imagewidth / $ratio);
                    }
                    $cfg_thumb_url = $cfg_fullsizepics_url;
                    $if_thumb = "no";
                }
                if ($keyword != '') {
                    $actiondependent = "keyword=$keyword";
                }
                if ($cat_id != '') {
                    $actiondependent = "cat_id=$cat_id";
                }
                $catprev .= "<a href=\"?p=gallery&pid=$showprevid&action=$action&$actiondependent\">";
                if ($showprevname != '') {
                    $catprev .= "<img src=\"$cfg_thumb_url/$showprevname\" border=\"0\" alt=\"$showprevtitle\" title=\"$showprevtitle\" width=\"$new_w\" height=\"$new_h\">";
                } else {
                    $catprev .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" />";
                }
                $catprev .= "</a>";
                $catprev .= "<br><a href=\"?p=gallery&pid=$showprevid&action=$action&$actiondependent\">&lt;&lt; " . _PREVIOUS . "</a>";
            } else {
                $catprev .= '&nbsp;';
            }
            $cfg_thumb_url = $tempthumb;

            $tempthumb = $cfg_thumb_url;
            if ($shownextid != -1) {
                if ((file_exists($cfg_thumb_path . "/" . $shownextname)) && ($shownextname != '')) {
                    $new_w = $image_stats[0];
                    $new_h = $image_stats[1];
                    $if_thumb = "yes";
                } elseif ((!file_exists($cfg_thumb_path . "/" . $shownextname)) && (file_exists($cfg_fullsizepics_path . "/" . $shownextname)) && ($shownextname != '')) {
                    $image_stats = getimagesize($cfg_fullsizepics_path . "/" . $shownextname);
                    $imagewidth = $image_stats[0];
                    $imageheight = $image_stats[1];
                    $img_type = $image_stats[2];
                    if ($imagewidth > $imageheight) {
                        $new_w = $cfg_thumb_width;
                        $ratio = ($imagewidth / $cfg_thumb_width);
                        $new_h = round($imageheight / $ratio);
                    } else {
                        $new_h = $cfg_thumb_width;
                        $ratio = ($imageheight / $cfg_thumb_width);
                        $new_w = round($imagewidth / $ratio);
                    }
                    $cfg_thumb_url = $cfg_fullsizepics_url;
                    $if_thumb = "no";
                }
                if ($keyword != '') {
                    $actiondependent = "keyword=$keyword";
                }
                if ($cat_id != '') {
                    $actiondependent = "cat_id=$cat_id";
                }
                $catnext .= "<div align=\"center\"><a href=\"?p=gallery&pid=$shownextid&action=$action&$actiondependent\">";
                if ($shownextname != '') {
                    $catnext .= "<img src=\"$cfg_thumb_url/$shownextname\" border=\"0\" alt=\"$shownexttitle\" title=\"$shownexttitle\" width=\"$new_w\" height=\"$new_h\">";
                } else {
                    $catnext .= "<img src=\"$cfg_app_url/images/none.gif\" border=\"0\" />";
                }
                $catnext .= "</a>";
                $catnext .= "<br><a href=\"?p=gallery&pid=$shownextid&action=$action&$actiondependent\">" . _NEXT . " &gt;&gt;</a></div>";
            } else {
                $catnext .= '&nbsp;';
            }
            $cfg_thumb_url = $tempthumb;
        }
    }

    $catcontent .= '
            <input type="hidden" name="action" value="save">
           
            <div class="control-group">
                    <label class="control-label">' . _FILENAMEFOTO . '</label>
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
            </div>';
    if ($action == "edit") {
        $catcontent .= '
           <div class="control-group"><div class="controls">' . _FRMPICEDITNOTE . '</div></div>';
    }
    $catcontent .= '<div class="control-group"> '
            . ' <label class="control-label">' . _CATEGORY . ':</label>';
   /* if ($action == "add") {
        $sql = "SELECT nama FROM gallerycat WHERE id='$cat_id'";
        $cat = $mysql->query($sql);
        list($cat_name) = $mysql->fetch_row($cat);
        $catcontent .='<div class="controls">'
                . '<label class="control-label gallerycatname">' . $cat_name . '</label></div></div>';
        $catcontent .= "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">\n";
        $mysql->free_result($cat);
   } 
    */
	 //else 
	 //{
		$cat_id=$cat_id==""?"0":$cat_id;
        $catcontent .= '<div class="controls" id="gallery_cat"><select name="cat_id" onchange="tambah_cat(this.value,'.$cat_id.')">';
        $cats = new categories();
        $mycats = array();
        $sql = 'SELECT id, nama, parent FROM gallerycat ORDER BY urutan ';
        $result = $mysql->query($sql);
        while ($row = $mysql->fetch_array($result)) {
            $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'level' => 0);
        }
        $cats->get_cats($mycats);
        $mysql->free_result($result);

        $catcontent .= '<option value="0">'.$topcatnamecombo.' /</option>';
        for ($i = 0; $i < count($cats->cats); $i++) {
		
            $cats->cat_map($cats->cats[$i]['id'], $mycats);
            $cat_name = $cats->cats[$i]['nama'];
			$selected=$hasil['cat_id']==$cats->cats[$i]['id']?"selected='selected'":"";
            $catcontent .= '<option '.$selected.' value="' . $cats->cats[$i]['id'] . '"';
            $catcontent .= ($cats->cats[$i]['id'] == $cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
            for ($a = 0; $a < count($cats->cat_map); $a++) {
                $cat_parent_id = $cats->cat_map[$a]['id'];
                $cat_parent_name = $cats->cat_map[$a]['nama'];
                $catcontent .= " / $cat_parent_name";
            }
            $catcontent .= " / $cat_name</option>";
        }
		$catcontent .= "<option  class=\"tambah_cat\" value='do_tambah_cat' >"._TAMBAHKATEGORI."</option>\r\n";
        $catcontent .= '</select></div></div>';
    //}

    $catcontent .= ' <div class="control-group">'
            . '<label class="control-label">' . _TITLE . '</label>'
            . '<div class="controls"><input type="text" name="title" value="' . $hasil['title'] . '">'
            . '</div>'
            . '</div>';
	if ($pakaislug) {
		$catcontent .= '<div class="control-group">';
		$catcontent .= '<label class="control-label">'._SLUGURL.'</label>';
		$catcontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$hasil['url']."\"/></div>";
		$catcontent .= '</div>';
	}
    for ($i = 0; $i < $additional; $i++) {
        switch ($typecustom[$i]) {
            case 'TINYINT':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="4"></div></div>';
                break;
            case 'SMALLINT':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="6"></div></div>';
                break;
            case 'MEDIUMINT':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="8"></div></div>';
                break;
            case 'INT':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="11"></div></div>';
                break;
            case 'DECIMAL':
                $splitparameter = explode(",", $paracustom[$i]);
                $splitparameter[0] = $splitparameter[0] + 1; //akomodasi tanda minus
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="' . $splitparameter[0] . '"></div></div>';
                break;
            case 'VARCHAR':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="' . $paracustom[$i] . '"></div></div>';
                break;
            case 'DOLLAR':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="9"></div></div>';
                break;
            case 'RUPIAH':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="13"></div></div>';
                break;
            case 'TEXT':
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><textarea class="usetiny" cols="60" rows="20" name="' . $customfield[$i] . '">' . $hasil[$customfield[$i]] . '</textarea></div></div>';
                break;
            case 'ENUM':
                $splitparameter = explode("/", $paracustom[$i]);
                $j = 0;
                $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><select name="' . $customfield[$i] . '"></div></div>';
                foreach ($splitparameter as $pilihan) {
                    $catcontent .= "<option value=\"$pilihan\"";
                    if ($pilihan == $hasil[$customfield[$i]]) {
                        $catcontent .= " selected";
                    }
                    $catcontent .= ">$pilihan</option>\n";
                }
                $catcontent .= "</div></div>\n";
                break;
            case 'DATE':
                if ($ispickerused) {
                    $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input id="' . $customfield[$i] . '" type="text" name="' . $customfield[$i] . ' " value="' . $hasil[$customfield[$i]] . '" maxlength="19" onblur="return outsmtr(this.name)" onfocus="return insmtr(this.name)"></div></div>';
                    break;
                } else {
                    $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input id="' . $customfield[$i] . '" type="text" name="' . $customfield[$i] . ' " value="' . $hasil[$customfield[$i]] . '" maxlength="19" ></div></div>';
                    break;
                }
            case 'DATETIME':
                if ($ispickerused) {
                    $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="19" onblur="return outsmtr(this.name)" onfocus="return insmtr(this.name)"></div></div>';
                    break;
                } else {
                    $catcontent .= '<div class="control-group"><label class="control-label">' . $ketcustomfield[$i] . '</label><div class="controls"><input type="text" name="' . $customfield[$i] . '" value="' . $hasil[$customfield[$i]] . '" maxlength="19"></div></div>';
                    break;
                }
        }
    }

	if ($action == "edit") {
	$publish_checked=$hasil['publish']==1?"checked=\"checked\"":"";
	$ishot_checked=$hasil['ishot']==1?"checked=\"checked\"":"";
	}
	else
	{
	$publish_checked="checked=\"checked\"";
	}
	$catcontent .="
	<div class=\"control-group\">
	<label class='control-label'></label>
	<div class=\"controls\">
	<div class=\"gallery_ishot\">
	<div style=\"float:left;\" class=\"publish\"><input type=\"checkbox\" $publish_checked value=\"1\" name=\"publish\"> &nbsp;"._PUBLISHED."</div>
	<div style=\"float:left;\" class=\"publish\"><input type=\"checkbox\" $ishot_checked value=\"1\" name=\"ishot\"> &nbsp;"._SLIDESHOW."</div>
	</div>
	</div>
	</div>
	";
	
   

    $catcontent .= '<input type="hidden" name="pid" value="' . $hasil['id'] . '">';
    if ($action == "edit") {
        $catcontent .= '<input type="hidden" name="action" value="update">';
    } else {
        $catcontent .= '<input type="hidden" name="action" value="save">';
    }
    $catcontent .= '<div class="control-group">
                       <div class="controls">';
    $catcontent .= "<input type=\"submit\" name=\"submit\" class=\"buton\" value=\"" . _SAVE . "\">";
    //$catcontent .= "<a class=\"buton\" href=\"?p=gallery\">" . _GOTOMAIN . " "._GALLERY."</a>";
    $catcontent .= '</div>
                        </div>
            </form>';
	}		
}


// ----------------------------------------------
// SHOW THUMBNAILS
// ----------------------------------------------


if ($action == "images") {
    if (empty($cat_id)) {
//        pesan(_ERROR, _NOCAT);
        $action = createmessage(_NOCAT, _INFO, "info", "");
    }

    $sql = "SELECT id FROM gallerycat WHERE id=$cat_id";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $mysql->free_result($result);

    if ($total_records == 0) {
//        pesan(_ERROR, _NOCAT);
        $action = createmessage(_NOCAT, _INFO, "info", "");
    }

    $sql = "SELECT id, nama FROM gallerycat WHERE parent='$cat_id' ORDER BY urutan ";
    $result = $mysql->query($sql);
    $total_subcat = $mysql->num_rows($result);
    if ($total_subcat > 0) {
        //$catsubcat = "<div class=\"titleofsubcatlist\">"._SUBCAT."</div>\n";
        $catsubcat .= "<ul>\n";
        while (list($catsubcat_id, $catsubcat_name) = $mysql->fetch_row($result)) {
            $catsubcat .= "<li><a href=\"?p=gallery&action=images&cat_id=$catsubcat_id\">$catsubcat_name</a> ";
            $catsubcat .= "<a href=\"?p=gallery&action=images&cat_id=$catsubcat_id\"><img alt=\"" . _OPEN . "\" border=\"0\" src=\"../images/open.gif\"></a>\n";
            $catsubcat .= "<a href=\"?p=gallery&action=catedit&cat_id=" . $catsubcat_id . "\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a> ";
            $catsubcat .= "<a href=\"?p=gallery&action=catdel&cat_id=" . $catsubcat_id . "\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
        }
        $catsubcat .= "</ul>\n";
        $catcontent .= "<a href=\"?p=gallery&action=catnew&parent=$cat_id\">" . _ADDSUBCAT . "</a><br />";
    }

    $cats = new categories();
    $mycats = array();
    $sql = "SELECT id, nama, parent, description FROM gallerycat ORDER BY urutan ";
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'nama' => $row['nama'], 'parent' => $row['parent'], 'description' => $row['description'], 'level' => 0);
    }
    $cats->get_cats($mycats);
    $mysql->free_result($result);

    $catnav = "<a href=\"?p=gallery&action=main\">$topcatnamenav</a>";
    for ($i = 0; $i < count($cats->cats); $i++) {
        $cats->cat_map($cats->cats[$i]['id'], $mycats);
        if ($cats->cats[$i]['id'] == $cat_id) {
            $catdesc = $cats->cats[$i]['description'];
            for ($a = 0; $a < count($cats->cat_map); $a++) {
                $cat_parent_id = $cats->cat_map[$a]['id'];
                $cat_parent_name = $cats->cat_map[$a]['nama'];
                $catnav .= "$separatorstyle<a href=\"?p=gallery&action=images&cat_id=$cat_parent_id\">$cat_parent_name</a>";
            }
            $catnav .= $separatorstyle . $cats->cats[$i]['nama'];
        }
    }

    // hitung dulu untuk menentukan berapa halaman...
    $sql = "SELECT id FROM gallerydata WHERE cat_id='$cat_id'";

    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    $mysql->free_result($result);

    // get the image information
    if ($screen == '') {
        $screen = 0;
    }

    $start = $screen * $max_page_list;
    $sql = "SELECT id, cat_id, filename, title, publish, ishot FROM gallerydata WHERE cat_id='$cat_id' ORDER BY cat_id ASC, date DESC, title ASC LIMIT $start, $max_page_list";

    $photo = $mysql->query($sql);
    $total_images = $mysql->num_rows($photo);

    if ($total_images == "0") {
        if ($total_subcat == 0) {
            $catcontent .= "<p>" . _NOPHOTO . "</p>";
            $catcontent .= "<a class=\"buton\" href=\"?p=gallery&action=catnew&parent=$cat_id\">" . _ADDSUBCAT . "</a> <a class=\"buton\" href=\"?p=gallery&action=add&cat_id=$cat_id\">" . _ADDPHOTO . "</a> ";
        }
    } else {
        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "cat_id=$cat_id&action=images");
        }
        $catcontent .= show_me_the_images(true);
        $catcontent .= "<a class=\"buton\" href=\"?p=gallery&action=add&cat_id=$cat_id\">" . _ADDPHOTO . "</a> ";
    }
   // $catcontent .= "<a class=\"buton\" href=\"?p=gallery\">" . _GOTOMAIN . " $judulmodul</a>";
}


// ----------------------------------------------
// DELETE IMAGE
// ----------------------------------------------

if (($action == "delete") || ($action == "confirm delete")) {

    $admintitle = _DELPHOTO;
    if (empty($pid)) {
        $action = createmessage(_NOPHOTO, _INFO, "info", "");
    }
    $catcontent = "";
    if ($action == "delete") {
        $catcontent .= "<h5>" . _PROMPTDEL . "</h5>";
        $catcontent .= "<a class=\"buton\" href=\"?p=gallery&pid=$pid&action=confirm+delete&cat_id=$cat_id\">" . _YES . "</a>&nbsp;<a class=\"buton\" href=\"?p=gallery\">" . _NO . "</a></p>";
    } elseif ($action == "confirm delete") {
        $sql = "SELECT cat_id,filename FROM gallerydata WHERE id='$pid'";
        $getfilename = $mysql->query($sql);
        list($cat_id, $filename) = $mysql->fetch_row($getfilename);
        if ($filename != '' && file_exists("$cfg_fullsizepics_path/$filename")) {
            unlink("$cfg_fullsizepics_path/$filename");
        }
        if ($filename != '' && file_exists("$cfg_thumb_path/$filename")) {
            unlink("$cfg_thumb_path/$filename");
        }
        $sql = "DELETE FROM gallerydata WHERE id='$pid'";
        if ($mysql->query($sql)) {
//            pesan(_SUCCESS, _DBSUCCESS, "?p=gallery&action=images&cat_id=$cat_id&r=$random");
            $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
        } else {
            $action = createmessage(_DBERROR, _ERROR, "error", "");
        }
    }
}

// ----------------------------------------------
// ADD CATEGORY
// ----------------------------------------------

// ----------------------------------------------
// ADD / EDIT CATEGORY CONFIRMED
// add-> savecat
// edit-> updatecat
// ----------------------------------------------

if ($action == "savecat") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
		
        $notificationbuilder .= validation($nama, _CATNAME, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "catnew");
        } else {
            $nama = strip_tags($nama);
            $cat_desc = strip_tags($cat_desc);
            $nama = checkrequired($nama, _CATNAME);
            //$sql = "INSERT gallerycat (nama, parent, description) values ('$nama','$parent','$cat_desc')";

            if (!preg_match('/[0-9]*/', $urutan)) $urutan = 1;
            $mx = getMaxNumber('gallerycat', 'urutan', "parent='" . $parent . "' ") + 2;
			
			if(!file_exists($cfg_cat_path))
			{
			mkdir($cfg_cat_path);
			}
			if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
				$hasilupload = fiestoupload('filename', $cfg_cat_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
				if ($hasilupload != _SUCCESS) $notificationbuilder = createmessage(hasilupload, _ERROR, "modify");

				//ambil informasi basename dan extension
				$temp = explode(".", $_FILES['filename']['name']);
				$extension = $temp[count($temp) - 1];
				$basename = '';
				for ($i = 0; $i < count($temp) - 1; $i++) {
					$basename .= $temp[$i];
				}
				
				$sql = "INSERT gallerycat (nama, parent, description, urutan) values ('$nama','$parent','$cat_desc','$mx')";
				$result = $mysql->query($sql);
				$mid = $mysql->insert_id();
				$result = urutkan('gallerycat', $urutan, "parent='" . $parent . "' ", $mid, "parent='" . $parent . "' ");
				if ($result) {
					$modifiedfilename = "$basename-$mid.$extension";
					$sql = "UPDATE gallerycat SET filename='$modifiedfilename' WHERE id='$mid'";
					if (!$mysql->query($sql)) {
						$action = createmessage(_DBERROR, _ERROR, "error", "add");
					}
					$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
				} else {
					$action = createmessage(_DBERROR, _ERROR, "error", "");
				}
				
				list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_cat_path/" . $_FILES['filename']['name']);

				//create thumbnail
				$hasilresize = fiestoresize("$cfg_cat_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_cat_thumb_width);
				if ($hasilresize != _SUCCESS) $notificationbuilder = createmessage($hasilresize, _ERROR, "modify");

				if ($filewidth > $cfg_cat_thumb_width) {
					//rename sambil resize gambar asli sesuai yang diizinkan
					$hasilresize = fiestoresize("$cfg_cat_path/" . $_FILES['filename']['name'], "$cfg_cat_path/$modifiedfilename", 'w', $cfg_cat_thumb_width);
					if ($hasilresize != _SUCCESS) $notificationbuilder = createmessage($hasilresize, _ERROR, "modify");

					//del gambar asli
					unlink("$cfg_cat_path/" . $_FILES['filename']['name']);
				} else {
					rename("$cfg_cat_path/" . $_FILES['filename']['name'], "$cfg_cat_path/$modifiedfilename");
				}				

			} else {
				$sql = "INSERT gallerycat (nama, parent, description, urutan) VALUES ('$nama', '$parent', '$cat_desc', '$mx')";
				$result = $mysql->query($sql);
				$mid = $mysql->insert_id();
				$result = urutkan('gallerycat', $urutan, "parent='" . $parent . "' ", $mid, "parent='" . $parent . "' ");
				if ($result) {
					$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
				} else {
					$action = createmessage(_DBERROR, _ERROR, "error", "");
				}
			}
            // $sql = "INSERT gallerycat (nama, parent, description, urutan) values ('$nama','$parent','$cat_desc','$mx')";
            // $result = $mysql->query($sql);
            // // $mid = $mysql->insert_id();
            // $result = urutkan('gallerycat', $urutan, "parent='" . $parent . "' ", $mid, "parent='" . $parent . "' ");
            // if ($result) {
                // $mid = $mysql->insert_id();
				// $modifiedfilename = "$basename-$mid.$extension";
				// $sql = "UPDATE gallerycat SET filename='$modifiedfilename'";
				// if (!$mysql->query($sql)) {
                    // $action = createmessage(_DBERROR, _ERROR, "error", "add");
                // }
				// $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
            // } else {
                // $action = createmessage(_DBERROR, _ERROR, "error", "");
            // }
		
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
            $nama = strip_tags($nama);
            $cat_desc = strip_tags($cat_desc);

            if (empty($cat_id)) {
                $action = createmessage(_NOCAT, _INFO, "info", "");
            } else {
                if (!preg_match('/[0-9]*/', $urutan))
                    $urutan = 1;

                $sqlcek = "SELECT * FROM gallerycat WHERE id='$cat_id'";
                $resultcek = $mysql->query($sqlcek);
                $datacek = $mysql->fetch_array($resultcek);
                $kondisiprev = "parent='" . $datacek["parent"] . "' ";

                $kondisi = "parent='" . $parent . "'";
				
				//=== IMAGE
				if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
					//upload
					$hasilupload = fiestoupload('filename', $cfg_cat_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
					if ($hasilupload != _SUCCESS)
					$notificationbuilder = createmessage(hasilupload, _ERROR, "modify");

					//ambil informasi basename dan extension
					$temp = explode(".", $_FILES['filename']['name']);
					$extension = $temp[count($temp) - 1];
					$basename = '';
					for ($i = 0; $i < count($temp) - 1; $i++) {
						$basename .= $temp[$i];
					}
					$modifiedfilename = "$basename-$cat_id.$extension";

					list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_cat_path/" . $_FILES['filename']['name']);

					//create thumbnail
					$hasilresize = fiestoresize("$cfg_cat_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_cat_thumb_width);
					if ($hasilresize != _SUCCESS)
					$notificationbuilder = createmessage($hasilresize, _ERROR, "modify");

					if ($filewidth > $cfg_cat_thumb_width) {
						//rename sambil resize gambar asli sesuai yang diizinkan
						$hasilresize = fiestoresize("$cfg_cat_path/" . $_FILES['filename']['name'], "$cfg_cat_path/$modifiedfilename", 'w', $cfg_cat_thumb_width);
						if ($hasilresize != _SUCCESS)
						$notificationbuilder = createmessage($hasilresize, _ERROR, "modify");

						//del gambar asli
						unlink("$cfg_cat_path/" . $_FILES['filename']['name']);
					} else {
						rename("$cfg_cat_path/" . $_FILES['filename']['name'], "$cfg_cat_path/$modifiedfilename");
					}

					//del gambar yang dioverwrite (hanya jika filename beda)
					$sql = "SELECT filename FROM gallerycat WHERE id='$cat_id'";
					$result = $mysql->query($sql);
					list($oldfilename) = $mysql->fetch_row($result);
					if ($modifiedfilename != $oldfilename) {
						@unlink("$cfg_cat_path/$oldfilename");
					}
					$sql =	"UPDATE gallerycat SET filename='$modifiedfilename' WHERE id='$cat_id'";
					if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);
				}		
				
				//=== END OF IMAGE
				
                $sql = "UPDATE gallerycat SET nama='$nama', description='$cat_desc' WHERE id='$cat_id'";
				
                $result = $mysql->query($sql);
                $result = urutkan('gallerycat', $urutan, $kondisi, $cat_id, $kondisiprev);
                if ($kondisi != $kondisiprev) {
                    urutkansetelahhapus('gallerycat', $kondisiprev);
                }
            }

            if ($result) {
//        pesan(_SUCCESS, _DBSUCCESS, "?p=gallery&action=main&r=$random");
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");
				$cat_id=$datacek["parent"];
				
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "");
            }
        }
    } else {
        $action = "catedit";
    }
}

if ($action == "catnew") 
{
    $admintitle .= ($cat_id == 0) ? _ADDMAINCAT : _ADDSUBCAT;
    $catcontent .= '
		  <form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
		  				<div class="control-group">
							<label class="control-label">' . _THUMBNAILFOLDER . '</label>
							<div class="controls">
								 <div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden" />
									<div class="input-append">
										<div class="uneditable-input span2">
											<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
										</div>
										<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Change</span>
											<input type="file" name="filename" >
										</span>
										<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
									</div>
								</div>
								'._KOSONGKANTHUMNAILFOLDER.'
							</div>
						</div>
                        <div class="control-group">
                                <label class="control-label">' . _CATNAME . '</label>
                                <div class="controls">
                                    <input type="text" name="nama" value="' . $cat_name . '" placeholder="' . _CATNAME . '" >
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">' . _CATDESC . '</label>
                                <div class="controls">
                                    <input type="text" name="cat_desc" value="' . $cat_desc . '" placeholder="' . _CATDESC . '" >
                                </div>
                        </div>';
    // $sql = "SELECT nama FROM gallerycat where id='$cat_id'";
    // $catlist = $mysql->query($sql);
    // list($catlist_name) = $mysql->fetch_row($catlist);
    // $catcontent .= '<input type="hidden" name="parent" value="' . $parent . '">';
    // $catcontent .= '<div class="control-group"><label class="control-label">' . _SUBCATOF . '</label><div class="controls gallery-subcat">';
	
	// $catcontent .="<select name=\"parent\" id=\"parent\" onchange=\"ajaxpage('$cfg_app_url/kelola/ajax.php?p=gallerycat&action=urutan&parent='+this.value, 'urutancontent')\">\n";
		// $cats = new categories();
		// $mycats = array();
		// $sql = 'SELECT id, nama, parent FROM gallerycat ORDER BY urutan ';
		// $result = $mysql->query($sql);
		// while($row = $mysql->fetch_array($result)) {
			// $mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		// }
		// $cats->get_cats($mycats);
		
		// $catcontent .="<option value=\"0\" >"._TOP."</option>\r\n";
		// for ($i=0; $i<count($cats->cats); $i++) {
			
				// $cats->cat_map($cats->cats[$i]['id'],$mycats);
				// $cat_name = $cats->cats[$i]['nama'];
				// $selected=$cat_id==$cats->cats[$i]['id']?"selected":"";
				// $catcontent .='<option '.$selected.' value="'.$cats->cats[$i]['id'].'"';
				// $catcontent .=">$topcatnamecombo";
				
				// for ($a=0; $a<count($cats->cat_map); $a++) {
					// $cat_parent_id = $cats->cat_map[$a]['id'];
					// $cat_parent_name = $cats->cat_map[$a]['nama'];
					// $catcontent .=" . . . ";//$cat_parent_name
				// }
				// $catcontent .="$cat_name</option>";
			
			
		// }
		// $catcontent .="</select>";
	// $catcontent .= '</div></div>';
	    $catcontent .= '
                        <div class="control-group">
                          <label class="control-label">' . _SUBCATOF . '</label>
                                <div class="controls">';
        $sql = "SELECT nama FROM gallerycat where id='$cat_id'";
		$catlist = $mysql->query($sql);
		list($catlist_name) = $mysql->fetch_row($catlist);
		$catcontent .= '<input type="hidden" name="parent" value="' . $cat_id . '">';
		$catlist_name=$catlist_name==""?"/":$catlist_name;
		$catcontent .= $catlist_name;
		
	$catcontent .= '</div></div>';
    
    $catcontent .= ' <div class="control-group"><label class="control-label">' . _ORDER . '</label><div class="controls">';
    $catcontent .= "<div id=\"urutancontent\">";
    $catcontent .= createurutan("gallerycat",$cat_id);
    $catcontent .= "</div>";
    $catcontent .= "</div></div>";

    $mysql->free_result($catlist);

    $catcontent .= '<div class="control-group"><div class="controls">';
    
        $catcontent .= '<input type = "hidden" name = "action" value = "savecat">';
        $catcontent .= '<input type="submit" name="submit" class="buton" value="' . _SAVE . '">';
        
    
    $catcontent .= '</div></div>';
    $catcontent .= '</form>';
}

// ----------------------------------------------
// DELETE CATEGORY
// ----------------------------------------------
if ($action == "catdel") {
    if (empty($cat_id)) {
//        pesan(_ERROR, _NOCAT);
        $action = createmessage(_NOCAT, _INFO, "info", "");
    }
    // determine if the category is empty and notify the user if the category is not empty
    $sql = "SELECT id FROM gallerydata where cat_id='$cat_id'";
    $cat = $mysql->query($sql);
    $number = $mysql->num_rows($cat);
    if ($number > 0) {
        $catcontent .= "<h5>" . _PROMPTDELCAT . "</h5>";
        $catcontent .= "<a class=\"buton\" href=\"?p=gallery&action=delete_cat_confirmed&cat_id=$cat_id\">" . _YES . "</a> &nbsp;<a class=\"buton\" href=\"?p=gallery\">" . _NO . "</a></p>";
    } else {
        $sql = "SELECT id FROM gallerycat where parent='$cat_id' ORDER BY urutan ";
        $result = $mysql->query($sql);
        $catsubcatnumber = $mysql->num_rows($result);
        if ($catsubcatnumber > 0) {
            pesan(_ERROR, _HAVECHILD);
        } else {
            $catcontent .= "<h5>"._PROMPTDELCAT."</h5>";
            $catcontent .= "<a class=\"buton\" href=\"?p=gallery&action=delete_cat_confirmed&cat_id=$cat_id\">" . _YES . "</a>&nbsp;<a class=\"buton\" href=\"?p=gallery\">" . _NO . "</a></p>";
        }
    }
}
// ----------------------------------------------
// EDIT CATEGORY
// ----------------------------------------------

if ($action == "catedit") {

    if (empty($cat_id)) {
        $action = createmessage(_NOCAT, _INFO, "info", "");
    }

    $admintitle .= _EDITCAT;
    $sql = "SELECT nama, parent, description, filename FROM gallerycat where id='$cat_id'";
    $cat = $mysql->query($sql);
    list($cat_name, $cat_parent_id, $cat_desc, $filename) = $mysql->fetch_row($cat);
    $mysql->free_result($cat);
    $cat_name = preg_replace('/"/', '&quot;', $cat_name);
	
	if ($filename!='' and file_exists("$cfg_cat_path/$filename")) 
	{
	$catcontent .= "<img src=\"$cfg_cat_url/$filename\" />";
	}
	//$catcontent .= "<img src=\"$cfg_app_url/images/none.gif\" />";
    $catcontent .= '
		  <form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
		  
						<div class="control-group">
							<label class="control-label">' . _FILENAMEFOTO . '</label>
							<div class="controls">
								 <div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden" />
									<div class="input-append">
										<div class="uneditable-input span2">
											<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
										</div>
										<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Change</span>
											<input type="file" name="filename" >
										</span>
										<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
									</div>
								</div>
							</div>
							<div class="controls">' . _FRMPICEDITNOTE . '</div>
						</div>
                        <div class="control-group">
                                <label class="control-label">' . _CATNAME . ':</label>
                                <div class="controls">
                                    <input type="text" name="nama" value="' . $cat_name . '" placeholder="' . _CATNAME . '" >
                                </div>
                        </div>
                        <div class="control-group">
                                <label class="control-label">' . _CATDESC . '</label>
                                <div class="controls">
                                    <input type="text" name="cat_desc" value="' . $cat_desc . '" placeholder="' . _CATDESC . '" >
                                </div>
                        </div>';
    
    
    $catcontent .= '
                        <div class="control-group">
                          <label class="control-label">' . _SUBCATOF . '</label>
                                <div class="controls">';
        $sql = "SELECT nama FROM gallerycat where id='$cat_parent_id'";
		$catlist = $mysql->query($sql);
		list($catlist_name) = $mysql->fetch_row($catlist);
		$catcontent .= '<input type="hidden" name="parent" value="' . $cat_parent_id . '">';
		$catlist_name=$catlist_name==""?"/":$catlist_name;
		$catcontent .= $catlist_name;
		
	$catcontent .= '</div></div>';
    

    $catcontent .= '<div class="control-group">
                        <label class="control-label">' . _ORDER . '</label>
                        <div class="controls">';
		$catcontent .= "<div id=\"urutancontent\">";
		$catcontent .= createurutan("gallerycat", $cat_parent_id, $cat_id);
		$catcontent .= "</div>";
    $catcontent .= '</div>
                        </div>';
    $catcontent .= '<div class="control-group">
                                <div class="controls">';

        $catcontent .= "<input type=\"hidden\" name=\"action\" value=\"updatecat\">";
        $catcontent .= "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">";
        $catcontent .= "<input type=\"submit\" name=\"submit\" class=\"buton\" value=\"" . _SAVE . "\">";

    //$catcontent .= "<a class=\"buton\" href=\"?p=gallery\">" . _GOTOMAIN . " $judulmodul</a>";
    $catcontent .= '</div>
                        </div>';
    $catcontent .= "</form>";
}



// ----------------------------------------------
// DELETE CATEGORY CONFIRMED
// ----------------------------------------------
if ($action == "delete_cat_confirmed") {
    $sql = "SELECT * FROM gallerycat WHERE id='$cat_id'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
//        pesan(_ERROR, _NOCAT);
        $action = createmessage(_NOCAT, _INFO, "info", "");
    } else {
        list($cid, $cparent, $cnama, $cdescription, $curutan, $cat_filename) = $mysql->fetch_row($result);
        if ($cparent == "0") {
            $sql = "SELECT * FROM gallerycat WHERE parent='0' ";
            $result = $mysql->query($sql);
            if ($mysql->num_rows($result) <= 1) {
                pesan(_ERROR, _1CAT, "?p=gallery&r=$random");
                $action = createmessage(_1CAT, _ERROR, "error", "");
            }
        }
		

		if (file_exists("$cfg_cat_path/$cat_filename")) @unlink("$cfg_cat_path/$cat_filename");
		
        $sql = "SELECT id, filename FROM gallerydata WHERE cat_id='$cat_id'";
        $getfilename = $mysql->query($sql);

        while (list($photo_id, $photo_filename) = $mysql->fetch_row($getfilename)) {
            $sql1 = "DELETE FROM gallerydata WHERE id='$photo_id'";
            if (!$mysql->query($sql1)) {
                $catcontent .= _DBERROR . " (ID:$photo_id)<br />\n";
            }

            $fullsize = $cfg_fullsizepics_path . '/' . $photo_filename;
            $thumb = $cfg_thumb_path . '/' . $photo_filename;

            if (file_exists($fullsize)) {
                if (unlink($fullsize)) {
                    $catcontent .= _FILEERROR5 . " (Full Size, ID:$photo_id)<br />\n";
                }
            }

            if (file_exists($thumb)) {
                if (unlink($thumb)) {
                    $catcontent .= _FILEERROR5 . " (Thumbnail, ID:$photo_id)<br />\n";
                }
            }
        }

        $sql = "DELETE FROM gallerydata WHERE cat_id='$cat_id'";
        if (!$mysql->query($sql)) {
            $action = createmessage(_DBERROR, _ERROR, "error", "");
        }

        $sql = "DELETE FROM gallerycat WHERE id='$cat_id'";
        if ($mysql->query($sql)) {
            urutkansetelahhapus("gallerycat", "parent='" . $cparent . "' ");
            $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
        } else {
            $action = createmessage(_DBERROR, _ERROR, "error", "");
        }
    }
}

if ($action == "uploadzip") {
    $admintitle .= _UPLOADZIP;
    $catcontent .= "<form class=\"form-horizontal\" action=\"$thisfile\" name=\"mainform\" method=\"POST\" enctype=\"multipart/form-data\">\r\n";

    $catcontent .= "<div class=\"control-group\"><div class=\"controls\"><select name=\"cat_id\">\r\n";
    $catcontent .= leastcatoption($cat_id);
    $catcontent .= "</select></div></div>\r\n";

    $catcontent .= "
		<div class=\"control-group\">
			<div class=\"controls\">
				<div data-provides=\"fileupload\" class=\"fileupload fileupload-new\"><input type=\"hidden\"  name=\"filename\" size=\"35\" />
					<div class=\"input-append\">
						<div class=\"uneditable-input span2\">
							<i class=\"icon-file fileupload-exists\"></i><span class=\"fileupload-preview\"></span>
						</div>
						<span class=\"btn btn-file\"><span class=\"fileupload-new\">Cari File</span><span class=\"fileupload-exists\">Change</span>
						<input type=\"file\" name=\"filename\" size=\"35\" />
						</span><a data-dismiss=\"fileupload\" class=\"btn fileupload-exists\" href=\"#\">Remove</a>
					</div>
				</div>
			</div>
		</div>\r\n";
    $catcontent .= "<input type=\"hidden\" name=\"action\" value=\"unzip\">\r\n";
    $catcontent .= "<div class=\"control-group\">"
            . "<div class=\"controls\">"
            . "<input class=\"buton\" type=\"submit\" value=\"" . _UPLOAD . "\">"
            . "</div>"
            . "</div>\r\n";
    $catcontent .= "</form>\r\n";
}

if ($action == "unzip") {
    //del semua file sampah
    if ($handle = opendir($cfg_temp_unzip_path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".svn") {
                unlink("$cfg_temp_unzip_path/$file");
            }
        }
        closedir($handle);
    }

    //upload file zip
    $hasilupload = fiestoupload('filename', $cfg_temp_unzip_path, '', 10000000, $allowedtypes = "zip");
    if ($hasilupload != _SUCCESS) {
        $action = createmessage($hasilupload, _ERROR, "error", "");
    }

    //extract hanya file gambar yang di root saja (abaikan folder dan file2 di dalamnya)
    $zip = new ZipArchive;
    if ($zip->open($cfg_temp_unzip_path . '/' . $_FILES['filename']['name']) === TRUE) {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $zippedfile = $zip->statIndex($i);
            $pos = strpos($zippedfile['name'], '/');
            if ($pos === false) {
                $temp = explode('.', $zippedfile['name']);
                $extension = strtolower($temp[count($temp) - 1]);
                if ($extension == "gif" || $extension == "jpg" || $extension == "jpeg" || $extension == "png")
                    $zip->extractTo($cfg_temp_unzip_path, $zippedfile['name']);
            }
        }
        $zip->close();
    }

    //del file zip yang sudah di-extract
    unlink($cfg_temp_unzip_path . '/' . $_FILES['filename']['name']);

    //baca semua file di folder $cfg_temp_unzip_path. jika file gambar, masukkan database dan folder yang benar. jika bukan file gambar, del.
    if ($handle = opendir($cfg_temp_unzip_path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $size = getimagesize("$cfg_temp_unzip_path/$file");
                if ($size == FALSE) {
                    unlink("$cfg_temp_unzip_path/$file");
                } else {

                    $temp = explode('.', $file);
                    $extension = strtolower($temp[count($temp) - 1]);
                    $basename = '';
                    for ($j = 0; $j < count($temp) - 1; $j++) {
                        $basename .= $temp[$j];
                    }

                    $sql = "INSERT INTO gallerydata (cat_id, date, title, publish, ishot) VALUES ('$cat_id', NOW(), '$basename', '1', '0')";
                    if ($mysql->query($sql)) {
                        $newid = $mysql->insert_id();
                        $modifiedfilename = "$basename-$newid.$extension";
                        $sql = "UPDATE gallerydata SET filename='$modifiedfilename' WHERE id='$newid'";
                        if (!$mysql->query($sql)) {
                            $action = createmessage(_DBERROR, _ERROR, "error", "");
                        }
                    } else {
//                        pesan(_ERROR, _DBERROR);
                        $action = createmessage(_DBERROR, _ERROR, "error", "");
                    }

                    //create thumbnail
                    $hasilresize = fiestoresize("$cfg_temp_unzip_path/$file", "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
                    if ($hasilresize != _SUCCESS) {
                        $action = createmessage($hasilresize, _ERROR, "error", "");
                    }

                    if ($size[0] > $cfg_max_width) {
                        //rename sambil resize gambar asli sesuai yang diizinkan
                        $hasilresize = fiestoresize("$cfg_temp_unzip_path/$file", "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
                        if ($hasilresize != _SUCCESS) {
                            $action = createmessage($hasilresize, _ERROR, "error", "");
                        }

                        //del gambar asli
                        unlink("$cfg_temp_unzip_path/$file");
                    } else {
                        rename("$cfg_temp_unzip_path/$file", "$cfg_fullsizepics_path/$modifiedfilename");
                    }

                    $i++;
                }
            }
        }
        closedir($handle);
    }
    $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
}

if ($action == "bulkedit") {
    $admintitle .= _BULKEDIT;
    if ($_POST['submit'] == _SAVE) {
        for ($i = 0; $i < count($_POST['bulkid']); $i++) {
            $published = ($_POST['bulkpublished'][$i] == 'on') ? 1 : 0;
            if (!$mysql->query("UPDATE gallerydata SET title='" . addslashes($_POST['bulktitle'][$i]) . "', publish='$published' WHERE id='" . $_POST['bulkid'][$i] . "'"))
                $action = createmessage(_DBERROR, _ERROR, "error", "");
        }
        $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
    } else {
        $catcontent .= "<form class=\"form-horizontal\" method=\"GET\">\r\n";
        $catcontent .= '
		<script language="JavaScript">
		<!--
		function MM_jumpMenu(targ,selObj,restore) { //v3.0
			if (selObj.options[selObj.selectedIndex].value=="") {
				eval(targ+".location=\'?p=gallery&action=bulkedit\'");
			} else {
				eval(targ+".location=\'?p=gallery&action=bulkedit&cat_id="+selObj.options[selObj.selectedIndex].value+"\'");
			}
			if (restore) selObj.selectedIndex=0;
		}
		//-->
		</script>
		';
        $catcontent .= '<div class="control-group">
                                <div class="controls">';
        $catcontent .= "<select name=\"cat_id\" onChange=\"MM_jumpMenu('parent',this,0)\">\r\n";
        $catcontent .= leastcatoption($cat_id);
        $catcontent .= "</select>\r\n";
        $catcontent .= '</div></div>';
        if ($cat_id == "") {
            $catcontent .= '<div class="control-group">
                                <div class="controls">';
            //$catcontent .= "<a class=\"buton\" href=\"?p=gallery\">" . _GOTOMAIN . " $judulmodul</a>";
            $catcontent .= '</div>
                        </div>';
        }
        $catcontent .= "</form><br />\r\n";

        if ($cat_id != '') {
            $sql = "SELECT id, title, publish FROM gallerydata WHERE cat_id='$cat_id' ORDER BY title";
            $result = $mysql->query($sql);
            $catcontent .= "<form class=\"form-horizontal\" action=\"?p=gallery&action=bulkedit\" method=\"POST\">\r\n";
            $catcontent .= "<table class=\"list\" cellpadding=\"6\" border=\"1\">\r\n";
            $catcontent .= "<tr><th>ID</th><th>" . _TITLE . "</th><th>" . _PUBLISHED . "</th></tr>";
            $i = 0;
            while (list($id, $title, $publish) = $mysql->fetch_row($result)) {
                $catcontent .= "<tr>\r\n";
                $catcontent .= "<td><input type=\"hidden\" name=\"bulkid[$i]\" value=\"$id\" />$id</td>\r\n";
                $catcontent .= "<td><input type=\"text\" name=\"bulktitle[$i]\" value=\"$title\" /></td>\r\n";
                $checked = ($publish) ? 'checked' : '';
                $catcontent .= "<td align=\"center\"><input type=\"checkbox\" name=\"bulkpublished[$i]\" value=\"on\" $checked /></td>\r\n";
                $catcontent .= "</tr>\r\n";
                $i++;
            }
            $catcontent .= "</table>\r\n";
            $catcontent .= '<div class="control-group">
                                <div class="controls">';
            $catcontent .= "<input class=\"buton\" type=\"submit\" name=\"submit\" value=\"" . _SAVE . "\" />";
            //$catcontent .= "<a class=\"buton\" href=\"?p=gallery\">" . _GOTOMAIN . " $judulmodul</a>";
            $catcontent .= '</div>
                        </div>';
            $catcontent .= "</form>\r\n";
        }
    }
}
// if ($action == "depan") {
    // $catcontent .= "
		// <ul>
			// <li><a href=\"?p=gallery&action=main\">" . _CATEGORY . "</a></li>
			// <li><a href=\"?p=gallery&action=uploadzip\">" . _UPLOADZIP . "</a></li>
			// <li><a href=\"?p=gallery&action=bulkedit\">" . _BULKEDIT . "</a></li>
		// </ul>
	// ";
    // $catcontent .= "<a class=\"buton\" href=\"?p=gallery&action=catnew&parent=0\">" . _ADDMAINCAT . "</a>";
// }

if ($action == "main" or $action == "home" or $action == ""  or $action == "add" or $action == "save" or $action == "edit" or $action == "update" or $action=="catnew" or $action=="savecat" or $action=="catedit" or $action=="updatecat") 
{
	$cat_id=$cat_id!=''?$cat_id:($_GET['cat_id']!=''?$_GET['cat_id']:0);
	$adminh1=cat_title($cat_id);
}
if ($action == "main" or $action == "home" or $action == "" ) 
{
	
	
	$sql="
	SELECT x.type,x.id,x.nama,x.parent,x.filename,x.total
	FROM
	(
	SELECT 0 type,id, nama, parent,filename,urutan,((select count(id) FROM gallerydata WHERE cat_id=g.id)+(select count(id) FROM gallerycat WHERE parent=g.id)) total FROM gallerycat g WHERE parent=$cat_id
	UNION ALL 
	SELECT 1 type,id,title nama,0 parent,filename,id urutan,0 total FROM gallerydata WHERE cat_id=$cat_id
	) x ORDER BY x.type,x.urutan
	";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    $mysql->free_result($result);

    
    if ($screen == '') {
        $screen = 0;
    }

    $start = $screen * $max_page_list;
    $sql .= " LIMIT $start, $max_page_list";

    $photo = $mysql->query($sql);
    $total_images = $mysql->num_rows($photo);

    if ($total_images == "0") {
        if ($total_subcat == 0) {
            //$catcontent .= "<p>" . _NOPHOTO . "</p>";
        }
    } else {
        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "cat_id=$cat_id&action=main");
    }
	}
	$catcontent.= catstructure($cat_id,true);
    
       //$catcontent .= "<div class='tombol_bawah'><a class=\"buton\" href=\"?p=gallery&action=catnew&cat_id=$cat_id\">" ._ADDCAT. "</a><a class=\"buton\" href=\"?p=gallery&action=add&cat_id=$cat_id\">" . _ADDPHOTO . "</a> </div>";
    
    
}

// ----------------------------------------------
// SEARCH
// ----------------------------------------------

if ($action == "search") {

    $keyword = strip_tags($keyword);
    $tambahan = "";
    $num = count($searchedcf);
    for ($i = 0; $i < $num; $i++) {
        $tambahan .= " OR ($searchedcf[$i] LIKE '%$keyword%')";
    }

    $admintitle = _SEARCHRESULTS;

    $sql = "SELECT id FROM gallerydata WHERE (title LIKE '%$keyword%') $tambahan";
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);
    $mysql->free_result($result);

    if ($screen == '') {
        $screen = 0;
    }

    $start = $screen * $max_page_list;

    $sql = "SELECT id, cat_id, filename, title, publish, ishot FROM gallerydata WHERE (title LIKE '%$keyword%') $tambahan ORDER BY cat_id ASC, date DESC, title ASC LIMIT $start, $max_page_list";
    $photo = $mysql->query($sql);
    $total_images = $mysql->num_rows($photo);

    if ($total_images == "0") {
        $admintitle .= _ERROR;
        $catcontent .= _NOSEARCHRESULTS;
    } else {
        $keyword = urlencode($keyword);
        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "action=search&keyword=$keyword");
        }
        $catcontent .= searchimage(TRUE);
    } //end total_images
    
}





// if ($cat_id == "xx" && $action == "main") {
    // $catcontent .= "<a class=\"buton\" href=\"?p=gallery&action=catnew&parent=0\">" . _ADDMAINCAT . "</a>";
    // $catcontent .= "<a class=\"buton\" href=\"?p=gallery\">" . _GOTOMAIN . " $judulmodul</a>";
// }

$admincontent .= "<div id=\"catnav\">$catnav</div>";
$admincontent .= "<div id=\"catdesc\">$catdesc</div>";
$admincontent .= "<div id=\"catsubcat\">$catsubcat</div>";
$admincontent .= "<div id=\"catimg\">$catimg</div>";
$admincontent .= "<div id=\"catcontent\">$catcontent</div>";
?>
