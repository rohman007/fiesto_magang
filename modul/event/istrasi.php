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

$tahun = fiestolaundry($_GET['tahun'], 4);
$bulan = fiestolaundry($_GET['bulan'], 2);
$dbegindate = fiestolaundry($_POST['dbegindate'], 2);
$mbegindate = fiestolaundry($_POST['mbegindate'], 2);
$ybegindate = fiestolaundry($_POST['ybegindate'], 4);
$denddate = fiestolaundry($_POST['denddate'], 2);
$menddate = fiestolaundry($_POST['menddate'], 2);
$yenddate = fiestolaundry($_POST['yenddate'], 4);
$eventtitle = fiestolaundry($_POST['eventtitle'], 50);
$eventkota = fiestolaundry($_POST['eventkota'], 50);
$eventlokasi = fiestolaundry($_POST['eventlokasi'], 250);
$eventcontent = fiestolaundry($_POST['eventcontent'],0, TRUE);
$action = fiestolaundry($_REQUEST['action'], 20);
$url = fiestolaundry($_POST['url'],255);

$modulename = $_GET['p'];
if ($action == "save") {
	
	if (isset($_POST['submit'])) {
		$notificationbuilder = "";
		$notificationbuilder.= validation($eventtitle, _EVENTTITLE, false);
		$notificationbuilder.= validation($eventlokasi, _LOCATION, false);
		$notificationbuilder.= validation($eventkota, _CITY, false);
		//$notificationbuilder.= validation($eventcontent,_EVENTCONTENT, false);
		if (checkdate($mbegindate, $dbegindate, $ybegindate)) {
			$tbegindate = "$ybegindate-$mbegindate-$dbegindate";
		} else {
			$notificationbuilder.= _STARTDATE . " " . _INVALIDDATE . "!<br/>";
		}
		if (checkdate($menddate, $denddate, $yenddate)) {
			$tenddate = "$yenddate-$menddate-$denddate";
		} else {
			$notificationbuilder.= _ENDDATE . " " . _INVALIDDATE . "!<br/>";
		}
		$checkdate=datevalidation($tbegindate, $tenddate, _STARTDATE, _ENDDATE);
		if($checkdate!=''){ $notificationbuilder[] =$checkdate;}

		if ($notificationbuilder!='') 
		{
			$action = createmessage($notificationbuilder, _ERROR, "error", "add");
		} else {
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($eventtitle, 'event', $pid) : seo_friendly_url($url, 'event', $pid);
			}
			
			if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
				//jika kolom file diisi
				//upload dulu sebelum insert record
				$hasilupload = fiestoupload('filename', $cfg_fullsizepics_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
				if ($hasilupload != _SUCCESS)
				$notificationbuilder = createmessage($hasilupload, _ERROR, "add");
				else {
					//ambil informasi basename dan extension
					$temp = explode(".", $_FILES['filename']['name']);
					$extension = $temp[count($temp) - 1];
					$basename = '';
					for ($i = 0; $i < count($temp) - 1; $i++) {
						$basename .= $temp[$i];
					}
					$sql = "INSERT INTO event (judul, deskripsi, tglmulai, tglselesai, kota, lokasi, url) VALUES ('$eventtitle', '$eventcontent', '$tbegindate', '$tenddate', '$eventkota', '$eventlokasi', '$url')";
					$result = $mysql->query($sql);
				}
				if ($result) {
					$newid = $mysql->insert_id();
					$modifiedfilename = "$basename-$newid.$extension";
					$sql = "UPDATE event SET filename='$modifiedfilename' WHERE id='$newid'";
					if (!$mysql->query($sql)) {
						$action = createmessage(_DBERROR, _ERROR, "add");
					}
					$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
				} else {
					$action = createmessage(_DBERROR, _ERROR, "error", "add");
				}
				//create thumbnail
				$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
				if ($hasilresize != _SUCCESS)
				$notificationbuilder = createmessage($hasilresize, _ERROR, "add");

				if ($filewidth > $cfg_max_width) { //rename sambil resize gambar asli sesuai yang diizinkan
					$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
					if ($hasilresize != _SUCCESS)
					$notificationbuilder = createmessage($hasilresize, _ERROR, "add");
					//del gambar asli
					unlink("$cfg_fullsizepics_path/" . $_FILES['filename']['name']);
				}
				else { //create thumbnail
					$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
					if ($hasilresize != _SUCCESS)
					$notificationbuilder = createmessage($hasilresize, _ERROR, "add");
					rename("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename");
				}
				$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
			} else {
				$sql = "INSERT INTO event (judul, deskripsi, tglmulai, tglselesai, kota, lokasi, url) VALUES ('$eventtitle', '$eventcontent', '$tbegindate', '$tenddate', '$eventkota', '$eventlokasi', '$url')";
				$result = $mysql->query($sql);
				if ($result) {
					$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
				} else {
					$action = createmessage($hasilresize, _ERROR, "add");
				}
			}
		}
	} else {
		$eventtitle = "";
		$eventcontent = "";
		$eventkota = "";
		$eventlokasi = "";
		$action = "add";
	}
}

if ($action == "add") {
	$admintitle = _ADDEVENT;
	$admincontent .= '
	<form class="form-horizontal" method="POST" action="'.$thisfile.'" enctype="multipart/form-data">
		<input type="hidden" name="action" value="save">';
	$admincontent .= "
		<div class=\"control-group\">
			<label class=\"control-label\">" . _IMAGE . "</label>
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
		</div>\n";

	$admincontent .= '	
	<div class="control-group">
		<label class="control-label">' . _EVENTTITLE . '</label>
		<div class="controls"><input type="text" name="eventtitle" size="30" /></div>
	</div>';

	if ($pakaislug) {
		$admincontent .= '	
		<div class="control-group">
			<label class="control-label">' . _SLUGURL . '</label>
			<div class="controls"><input type="text" name="url" size="30" /></div>
		</div>';
	}

	$admincontent .= '
	<div class="control-group">
		<label class="control-label">' . _STARTDATE . '</label>
		<div class="controls">';

	$hariini = getdate();

	$admincontent .= "<select class=\"tanggal\" name=\"dbegindate\">";
	for ($i = 1; $i <= 31; $i++) {
		if ($hariini[mday] == $i) {
			$admincontent .= "<option value=\"$i\" selected>$i</option>\n";
		} else {
			$admincontent .= "<option value=\"$i\">$i</option>\n";
		}
	}
	$admincontent .= "</select>";

	$admincontent .= "<select class=\"bulan\" name=\"mbegindate\">";
	for ($i = 1; $i <= 12; $i++) {
		$j = $i - 1;
		if ($hariini[mon] == $i) {
			$admincontent .= "<option value=\"$i\" selected>$namabulan[$j]</option>\n";
		} else {
			$admincontent .= "<option value=\"$i\">$namabulan[$j]</option>\n";
		}
	}
	$admincontent .= "</select>";

	$admincontent .= "<select class=\"tahun\" name=\"ybegindate\">";
	for ($i = 2001; $i <= 2025; $i++) {
		if ($hariini[year] == $i) {
			$admincontent .= "<option value=\"$i\" selected>$i</option>\n";
		} else {
			$admincontent .= "<option value=\"$i\">$i</option>\n";
		}
	}
	$admincontent .= "</select>";

	$admincontent .= '
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">' . _ENDDATE . '</label>
		<div class="controls">';

	$admincontent .= "<select class=\"tanggal\" name=\"denddate\">";
	for ($i = 1; $i <= 31; $i++) {
		if ($hariini[mday] == $i) {
			$admincontent .= "<option value=\"$i\" selected>$i</option>\n";
		} else {
			$admincontent .= "<option value=\"$i\">$i</option>\n";
		}
	}
	$admincontent .= "</select>";

	$admincontent .= "<select class=\"bulan\" name=\"menddate\">";
	for ($i = 1; $i <= 12; $i++) {
		$j = $i - 1;
		if ($hariini[mon] == $i) {
			$admincontent .= "<option value=\"$i\" selected>$namabulan[$j]</option>\n";
		} else {
			$admincontent .= "<option value=\"$i\">$namabulan[$j]</option>\n";
		}
	}
	$admincontent .= "</select>";

	$admincontent .= "<select class=\"tahun\" name=\"yenddate\">";
	for ($i = 2001; $i <= 2025; $i++) {
		if ($hariini[year] == $i) {
			$admincontent .= "<option value=\"$i\" selected>$i</option>\n";
		} else {
			$admincontent .= "<option value=\"$i\">$i</option>\n";
		}
	}
	$admincontent .= "</select>";

	$admincontent .= '
		</div>
	</div>';
	$admincontent .= '	
	<div class="control-group">
		<label class="control-label">' . _LOCATION . '</label>
		<div class="controls"><input type="text" name="eventlokasi" size="30" maxlength="250" /></div>
	</div>';
	$admincontent .= '	
	<div class="control-group">
		<label class="control-label">' . _CITY . '</label>
		<div class="controls"><input type="text" name="eventkota" size="30" maxlength="50" /></div>
	</div>';
	$admincontent .= '	
	<div class="control-group">
		<label class="control-label">' . _EVENTCONTENT . '</label>
		<div class="controls"><textarea name="eventcontent" cols="60" rows="20" class="usetiny"></textarea></div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="submit" class="buton" value="' . _SAVE . '" />
			<a href="'.param_url('?p=event').'" class="buton">' . _BACK . '</a>
		</div>
	</div>
</form>
';
}

if ($action == "update") {
	if (isset($_POST['submit'])) {
		$notificationbuilder = "";
		$notificationbuilder .= validation($eventtitle, _EVENTTITLE, false);
		$notificationbuilder .= validation($eventlokasi, _LOCATION, false);
		$notificationbuilder .= validation($eventkota, _CITY, false);
		//$notificationbuilder .= validation($eventcontent, _EVENTCONTENT, false);
		if (checkdate($mbegindate, $dbegindate, $ybegindate)) {
			$tbegindate = "$ybegindate-$mbegindate-$dbegindate";
		} else {
			$notificationbuilder = _STARTDATE . " " . _INVALIDDATE . "!<br/>";
		}
		if (checkdate($menddate, $denddate, $yenddate)) {
			$tenddate = "$yenddate-$menddate-$denddate";
		} else {
			$notificationbuilder = _ENDDATE . " " . _INVALIDDATE . "!<br/>";
		}
		$notificationbuilder .= datevalidation($tbegindate, $tenddate, _STARTDATE, _ENDDATE);

		if ($notificationbuilder != "") {
			$action = createmessage($notificationbuilder, _ERROR, "error", "modify");
		} else {
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($eventtitle, 'event', $pid) : seo_friendly_url($url, 'event', $pid);
			}
			if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
				//upload
				$hasilupload = fiestoupload('filename', $cfg_fullsizepics_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
				if ($hasilupload != _SUCCESS)
				$notificationbuilder = createmessage(hasilupload, _ERROR, "modify");

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
				if ($hasilresize != _SUCCESS)
				$notificationbuilder = createmessage($hasilresize, _ERROR, "modify");

				if ($filewidth > $cfg_max_width) {

					//rename sambil resize gambar asli sesuai yang diizinkan
					$hasilresize = fiestoresize("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename", 'w', $cfg_max_width);
					if ($hasilresize != _SUCCESS)
					$notificationbuilder = createmessage($hasilresize, _ERROR, "modify");

					//del gambar asli
					// unlink("$cfg_fullsizepics_path/" . $_FILES['filename']['name']);
				} else {
					rename("$cfg_fullsizepics_path/" . $_FILES['filename']['name'], "$cfg_fullsizepics_path/$modifiedfilename");
				}

				//del gambar yang dioverwrite (hanya jika filename beda)
				$sql = "SELECT filename FROM event WHERE id='$pid'";
				$result = $mysql->query($sql);
				list($oldfilename) = $mysql->fetch_row($result);
				if ($modifiedfilename != $oldfilename) {
					if ($oldfilename != '' && file_exists("$cfg_fullsizepics_path/$oldfilename")) unlink("$cfg_fullsizepics_path/$oldfilename");
					if ($oldfilename != '' && file_exists("$cfg_thumb_path/$oldfilename")) unlink("$cfg_thumb_path/$oldfilename");
				}

				$sql = "UPDATE event SET judul='$eventtitle', 
				deskripsi='$eventcontent', tglmulai='$tbegindate', 
				tglselesai='$tenddate', kota='$eventkota', 
				lokasi='$eventlokasi', filename='$modifiedfilename', url='$url' 
				WHERE id='$pid'";
			} else {
				$sql = "UPDATE event SET judul='$eventtitle', 
				deskripsi='$eventcontent', tglmulai='$tbegindate', 
				tglselesai='$tenddate', kota='$eventkota', 
				lokasi='$eventlokasi', url='$url' WHERE id='$pid'";
			}

			$result = $mysql->query($sql);
			if ($result) {
				// pesan(_SUCCESS,_DBSUCCESS,"?p=event&r=$random");
				$action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
			} else {
				// pesan(_ERROR,_DBERROR);
				$action = createmessage(_DBERROR, _ERROR, "error", "modify");
			}
		}
	} else {
		$action = "modify";
	}
}

// MODIFY EVENT
if ($action == "modify") {
	$admintitle = _EDITEVENT;
	$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi, url FROM event WHERE id='$pid'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) == "0") {
		pesan(_TITLENOEVENT, _NOEVENT);
	} else {
		list($id, $eventtitle, $eventcontent, $tglmulai, $tglselesai, $filename, $kota, $lokasi, $url) = $mysql->fetch_row($result);
		if ($filename != '')
		$admincontent .= '<img src="' . $cfg_fullsizepics_url . '/' . $filename . '" alt="' . $eventtitle . '">';
	
		if ($pakaislug) {
			$slugcontent = '	
			<div class="control-group">
				<label class="control-label">' . _SLUGURL . '</label>
				<div class="controls"><input type="text" name="url" size="30" value="'.$url.'"/></div>
			</div>';
		}
		$admincontent .= '
	<form class="form-horizontal" method="POST" action="'.$thisfile.'" enctype="multipart/form-data">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="pid" value="' . $pid . '">';
		$admincontent .= '
		<div class="control-group">
			<label class="control-label">' . _IMAGE . '</label>
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
			<div class="controls">
			<label >' . _FRMPICEDITNOTE . '</label>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">' . _EVENTTITLE . '</label>
			<div class="controls">
			<input type="text" name="eventtitle" size="30" value="' . $eventtitle . '" placeholder="' . _EVENTTITLE . '" >
			</div>
		</div>
		'.$slugcontent.'
		<div class="control-group">
			<label class="control-label">' . _STARTDATE . '</label>
			<div class="controls">
		';
		$begindate = explode('-', $tglmulai);
		$enddate = explode('-', $tglselesai);
		$admincontent .= '<select class="tanggal" name="dbegindate">';
		for ($i = 1; $i <= 31; $i++) {
			if ($begindate[2] == $i) {
				$admincontent .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$admincontent .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$admincontent .= '</select>';

		$admincontent .= '<select class="bulan" name="mbegindate">';
		for ($i = 1; $i <= 12; $i++) {
			$j = $i - 1;
			if ($begindate[1] == $i) {
				$admincontent .= '<option value="' . $i . '" selected>' . $namabulan[$j] . '</option>';
			} else {
				$admincontent .= '<option value="' . $i . '">' . $namabulan[$j] . '</option>';
			}
		}
		$admincontent .= '</select>';

		$admincontent .= '<select class="tahun" name="ybegindate">';
		for ($i = 2001; $i <= 2025; $i++) {
			if ($begindate[0] == $i) {
				$admincontent .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$admincontent .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$admincontent .= '</select>';

		$admincontent .= '
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">' . _ENDDATE . '</label>
			<div class="controls">';

		$admincontent .= '<select class="tanggal" name="denddate">';
		for ($i = 1; $i <= 31; $i++) {
			if ($enddate[2] == $i) {
				//                $admincontent .= "<option value=\"$i\" selected>$i</option>\n";
				$admincontent .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				//                $admincontent .= "<option value=\"$i\">$i</option>\n";
				$admincontent .= '<option value="' . $i . '" >' . $i . '</option>';
			}
		}
		$admincontent .= '</select>';

		$admincontent .= '<select class="bulan" name="menddate">';
		for ($i = 1; $i <= 12; $i++) {
			$j = $i - 1;
			if ($enddate[1] == $i) {
				//                $admincontent .= '<option value=\"$i\" selected>$namabulan[$j]</option>';
				$admincontent .= '<option value="' . $i . '" selected>' . $namabulan[$j] . '</option>';
			} else {
				//                $admincontent .= "<option value=\"$i\">$namabulan[$j]</option>\n";
				$admincontent .= '<option value="' . $i . '">' . $namabulan[$j] . '</option>';
			}
		}
		$admincontent .= '</select>';

		$admincontent .= '<select class="tahun" name="yenddate">';
		for ($i = 2001; $i <= 2025; $i++) {
			if ($enddate[0] == $i) {
				//                $admincontent .= "<option value=\"$i\" selected>$i</option>\n";
				$admincontent .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				//                $admincontent .= "<option value=\"$i\">$i</option>\n";
				$admincontent .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$admincontent .= '</select>';

		$admincontent .= '
			</div>
		</div>';
		$admincontent .= '	
		<div class="control-group">
			<label class="control-label">' . _LOCATION . '</label>
			<div class="controls">
			<input type="text" name="eventlokasi" size="30" value="' . $lokasi . '" placeholder="' . _LOCATION . '"  maxlength="250">
			</div>
	</div>
';
		$admincontent .= '	
		<div class="control-group">
			<label class="control-label">' . _CITY . '</label>
			<div class="controls">
			<input type="text" name="eventkota" size="30" value="' . $kota . '" placeholder="' . _CITY . '"  maxlength="50">
			</div>
	</div>
		';
		$admincontent .= '	
		<div class="control-group">
			<label class="control-label">' . _EVENTCONTENT . '</label>
			<div class="controls">
				<textarea name="eventcontent" cols="60" rows="20" class="usetiny">' . $eventcontent . '</textarea>
			</div>
	</div>

	<div class="control-group">
			<div class="controls">
				<input type="submit" name="submit" class="buton" value="' . _SAVE . '">
				<a href="'.param_url("?p=$modulename").'" class="buton">' . _BACK . '</a>	
			</div>
	</div>
</form>';
	}
}



// DELETE EVENT
if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
	$admintitle = _DELEVENT;
	$sql = "SELECT id, filename FROM event WHERE id='$pid'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) == "0") {
		// pesan(_ERROR,_NOEVENT);
		$buildnotification = createnotification(_NOEVENT, _ERROR);
	} else {
		list($id, $filename) = $mysql->fetch_row($result);

		if ($action == "remove") {
			$admincontent .= "<h5>" . _PROMPTDEL . "</h5> <a class=\"buton\" href=\"".param_url("?p=event&action=delete&pid=$pid")."\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
		} else {
			if ($filename != '' && file_exists("$cfg_fullsizepics_path/$filename"))
			@unlink("$cfg_fullsizepics_path/$filename");
			if ($filename != '' && file_exists("$cfg_thumb_path/$filename"))
			@unlink("$cfg_thumb_path/$filename");

			$sql = "DELETE FROM event WHERE id='$pid'";
			$result = $mysql->query($sql);
			if ($result) {
				// pesan(_SUCCESS,_DBSUCCESS,"?p=event&r=$random");
				$buildnotification = createnotification(_DELETESUCCESS, _SUCCESS, "success");
			} else {
				// pesan(_ERROR,_DBERROR);
				$buildnotification = createnotification(_DBERROR, _ERROR);
			}
			$action = "";
		}
	}
}


// CLEAN EVENT
if ($action == "clean" || $action == "doclean") {
	$admintitle = _CLEANEVENT;
	if ($action == "clean") {
		$admincontent .= "<a href=\"".param_url("?p=event&action=doclean")."\">" . _YES . "</a> <a href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
	} else {
		$sql = "SELECT id, filename FROM event WHERE tglselesai < CURDATE()";
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result) > "0") {
			while (list($id, $filename) = $mysql->fetch_row($result)) {
				if ($filename != '' && file_exists("$cfg_fullsizepics_path/$filename"))
				@unlink("$cfg_fullsizepics_path/$filename");
				if ($filename != '' && file_exists("$cfg_thumb_path/$filename"))
				@unlink("$cfg_thumb_path/$filename");
			}
		}

		$sql = "DELETE FROM event WHERE tglselesai < CURDATE()";
		$result = $mysql->query($sql);
		if ($result) {
			$buildnotification = createnotification(_DELETESUCCESS, _SUCCESS, "success");
		} else {
			$buildnotification = createnotification(_DBERROR, _ERROR);
		}
	}
}

if ($action == "") {
	if ($tahun == '' && $bulan == '') {
		$sql = "SELECT id FROM event WHERE tglselesai>=CURDATE()";
		$result = $mysql->query($sql);
		$total_records = $mysql->num_rows($result);
		$pages = ceil($total_records / $max_page_list);
		$start = $screen * $max_page_list;
		$admincontent = "<h3>" . _FUTUREEVENT . "</h3>";
		if ($mysql->num_rows($result) > 0) {
			$sql = "SELECT id, judul, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE tglselesai>=CURDATE() ORDER BY tglmulai DESC LIMIT $start, $max_page_list";
			$result = $mysql->query($sql);
			if ($pages > 1)
			$adminpagination = pagination($namamodul, $screen);
			$admincontent .= $buildnotification;
			$admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
			$admincontent .= "<tr><th>" . _EVENTTITLE . "</th><th>" . _STARTDATE . "</th><th>" . _ENDDATE . "</th>";
			$admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
			while (list($id, $judul, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
				$admincontent .= "<tr>\n";
				$admincontent .= "<td>$judul</td><td>" . tglformat($tglmulai, FALSE) . "</td><td>" . tglformat($tglselesai, FALSE) . "</td>";
				$admincontent .= "<td align=\"center\" class=\"action-ud\">";
				$admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
				$admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
				$admincontent .= "</tr>\n";
			}
			$admincontent .= "</table>";
		} else {
			$admincontent .= $buildnotification;
			$admincontent .= createnotificationincontent(_NOEVENT, _INFO, "info");
		}

		//Arsip Event
		$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE tglselesai<CURDATE() ORDER BY tglmulai DESC";
		$result = $mysql->query($sql);
		$admincontent .= "<h3>" . _PASTEVENT . "</h3>";
		if ($mysql->num_rows($result) > 0) {
			$admincontent .= "<ul>\r\n";
			while (list($id, $judul, $deskripsi, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
				if (($timestamp = strtotime($tglmulai)) !== -1) {
					$i = getdate($timestamp);
					$angkabulan = $i[mon] - 1;
					if ($namabulan[$angkabulan] != $bulanini) {
						$tahun = $i[year];
						$admincontent .= "<li><a href=\"?p=event&tahun=$tahun&bulan=$i[mon]\">$namabulan[$angkabulan] $tahun</a>\r\n";
						$bulanini = $namabulan[$angkabulan];
					}
				}
			}
			$admincontent .= "</ul>\r\n";
		} else {
			$admincontent .= $buildnotification;
			$admincontent .= createnotificationincontent(_NOEVENT, _INFO, "info");
		}
		
		$admincontent .=  "<a class=\"buton\" href=\"".param_url("?p=event&action=add")."\">" . _ADDEVENT . "</a> <a class=\"buton\" href=\"".param_url("?p=event&action=clean")."\">" . _CLEANEVENT . "</a>";
		
	} else {
		$bulandepan = $bulan + 1;
		$sql = "SELECT id FROM event WHERE tglmulai >= '$tahun-$bulan-01 00:00:00' AND tglmulai < '$tahun-$bulandepan-01 00:00:00' AND tglselesai<CURDATE()";
		$result = $mysql->query($sql);
		$total_records = $mysql->num_rows($result);
		$pages = ceil($total_records / $max_page_list);
		$start = $screen * $max_page_list;
		if ($mysql->num_rows($result) > 0) {
			$bulandepan = $bulan + 1;
			$sql = "SELECT id, judul, deskripsi, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE tglmulai >= '$tahun-$bulan-01 00:00:00' AND tglmulai < '$tahun-$bulandepan-01 00:00:00' AND tglselesai<CURDATE() ORDER BY tglmulai DESC LIMIT $start, $max_page_list";
			$result = $mysql->query($sql);
			$angkabulan = $bulan - 1;
			if ($pages > 1)
			$adminpagination = pagination($namamodul, $screen);
			$admintitle = _ARCHIVE . " - $namabulan[$angkabulan] $tahun\n";
			$admincontent .= $buildnotification;
			$admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
			$admincontent .= "<tr><th>" . _EVENTTITLE . "</th><th>" . _STARTDATE . "</th><th>" . _ENDDATE . "</th>";
			$admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
			while (list($id, $judul, $deskripsi, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
				$admincontent .= "<tr>\n";
				$admincontent .= "<td>$judul</td><td>" . tglformat($tglmulai, FALSE) . "</td><td>" . tglformat($tglselesai, FALSE) . "</td>";
				$admincontent .= "<td align=\"center\" class=\"action-ud\">";
				$admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
				$admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
				$admincontent .= "</tr>\n";
			}
			$admincontent .= "</table>";
		} else {
			$admincontent .= $buildnotification;
			createnotification(_NOEVENT, _INFO, "info");
		}
		$admincontent .=  "<a class=\"buton\" href=\"".param_url("?p=event&action=add")."\">" . _ADDEVENT . "</a> <a class=\"buton\" href=\"".param_url("?p=event&action=clean")."\">" . _CLEANEVENT . "</a>";
		$admincontent .= " <a href=\"".param_url("?p=event")."\" class=\"buton\">"._BACK ."</a>";
	}

}

if ($action == "search") 
{
	$sql = "SELECT id FROM event WHERE judul LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%'";
	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$pages = ceil($total_records / $max_page_list);
	$start = $screen * $max_page_list;
	$admintitle = _SEARCHRESULTS;
	if ($mysql->num_rows($result) > 0) {
		$sql = "SELECT id, judul, tglmulai, tglselesai, filename, kota, lokasi FROM event WHERE judul LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' ORDER BY tglmulai DESC LIMIT $start, $max_page_list";
		$result = $mysql->query($sql);
		if ($pages > 1)
		$adminpagination = pagination($namamodul, $screen, "action=search&keyword=$keyword");
		$admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
		$admincontent .= "<tr><th>" . _EVENTTITLE . "</th><th>" . _STARTDATE . "</th><th>" . _ENDDATE . "</th>";
		$admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
		while (list($id, $judul, $tglmulai, $tglselesai, $filename, $kota, $lokasi) = $mysql->fetch_row($result)) {
			$admincontent .= "<tr>\n";
			$admincontent .= "<td>$judul</td><td>" . tglformat($tglmulai, FALSE) . "</td><td>" . tglformat($tglselesai, FALSE) . "</td>";
			$admincontent .= "<td align=\"center\" class=\"action-ud\">";
			$admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
			$admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
			$admincontent .= "</tr>\n";
		}
		$admincontent .= "</table>";
		$admincontent .= " <a href=\"".param_url("?p=event")."\" class=\"buton\">"._BACK ."</a>";
		
	} else {
		createnotification(_NOSEARCHRESULTS, _INFO, "info");
	}
}
?>
