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

$bank = fiestolaundry($_POST['bank'], 50);
$cabang = fiestolaundry($_POST['cabang'],100);
$norekening = fiestolaundry($_POST['norekening'],50);
$atasnama = fiestolaundry($_POST['atasnama'],100);

$action = fiestolaundry($_REQUEST['action'], 20);

$modulename = $_GET['p'];

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($bank, _BANK, false);
        $notificationbuilder .= validation($norekening,_NOREKENING,false);
        $notificationbuilder .= validation($atasnama,_ATASNAMA,false);
        //upload dulu sebelum insert record
        if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
            $hasilupload = fiestoupload('filename', $cfg_thumb_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
            if ($hasilupload != _SUCCESS) {
                $notificationbuilder .=_GAGALUPLOADLOGO."!<br/>";
            }
            //ambil informasi basename dan extension
            $temp = explode(".", $_FILES['filename']['name']);
            $extension = $temp[count($temp) - 1];
            $basename = '';
            for ($i = 0; $i < count($temp) - 1; $i++) {
                $basename .= $temp[$i];
            }
        }
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            /* add */
            $sql = "INSERT INTO sc_payment (bank,cabang,norekening,atasnama) VALUES ('$bank','$cabang','$norekening','$atasnama')";
            $result = $mysql->query($sql);
            if ($result) {
                if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
                    $newid = $mysql->insert_id();
                    $modifiedfilename = "$basename-$newid.$extension";
                    $sql = "UPDATE sc_payment SET logo='$modifiedfilename' WHERE id='$newid'";
                    if (!$mysql->query($sql)) {
                        $action = createmessage(_DBERROR, _ERROR, "error", "add");
                    }
                }
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "add");
            }
            $namafile = $_FILES['filename']['name'];
            //create thumbnail
            if ($result && $_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) {
                $hasilresize = fiestoresize("$cfg_thumb_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l', $cfg_thumb_width);
                if ($hasilresize != _SUCCESS) {
                    $action = createmessage(_DBERROR, _ERROR, "error", "add");
                }
                //del gambar asli
                unlink("$cfg_thumb_path/" . $_FILES['filename']['name']);
            }
            if ($result) {
                $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
            }
            /* add */
        }
    } else {
        /* default value untuk form */
        $name = "";
        $keterangan = "";
        $action = "add";
    }
}

if ($action == "add") {
    $admintitle = _ADDPAYMENT;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
            <div class="control-group">
                <label class="control-label">' . _LOGO . '</label>
                <div class="controls">
                    <div data-provides="fileupload" class="fileupload fileupload-new">
                        <div class="input-append">
                            <div class="uneditable-input span2">
                                    <i class="icon-file fileupload-exists"></i>
                                    <span class="fileupload-preview"></span>
                            </div>
                            <span class="btn btn-file">
                                <span class="fileupload-new">Cari File</span>
                                <span class="fileupload-exists">Change</span>
                                <input type="file" name="filename" size="35">
                            </span>
                            <a data-dismiss="fileupload" class="btn fileupload-exists" href="#">
                            Remove
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _BANK . '</label>
                    <div class="controls">
                            <input type="text" name="bank" placeholder="' ._BANK . '" class="span12" value="' . $bank . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _CABANG . '</label>
                    <div class="controls">
                            <input type="text" name="cabang" placeholder="' ._CABANG . '" class="span12" value="' . $cabang . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _NOREKENING . '</label>
                    <div class="controls">
                            <input type="text" name="norekening" placeholder="' ._NOREKENING . '" class="span12" value="' . $norekening . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _ATASNAMA . '</label>
                    <div class="controls">
                            <input type="text" name="atasnama" placeholder="' ._ATASNAMA . '" class="span12" value="' . $atasnama . '">
                    </div>
            </div>
            <div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="btn btn-danger" value="' . _SAVE . '">
                            <a href="'.param_url('?p=sc_payment').'" class="buton">' . _BACK . '</a>
                    </div>
            </div>
	</form>
    ';
}


if ($action == "update") {
    if (isset($_POST['submit'])) {
         $notificationbuilder = "";
        $notificationbuilder .= validation($bank, _BANK, false);
        $notificationbuilder .= validation($norekening,_NOREKENING,false);
        $notificationbuilder .= validation($atasnama,_ATASNAMA,false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
        } else {
            if ($_FILES['filename']['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
                //upload
                $hasilupload = fiestoupload('filename', $cfg_thumb_path, '', $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png");
                if ($hasilupload != _SUCCESS) {
                    $action = createmessage(_DBERROR, _ERROR, "error", "modify");
                }
                //ambil informasi basename dan extension
                $temp = explode(".", $_FILES['filename']['name']);
                $extension = $temp[count($temp) - 1];
                $basename = '';
                for ($i = 0; $i < count($temp) - 1; $i++) {
                    $basename .= $temp[$i];
                }
                $modifiedfilename = "$basename-$pid.$extension";

                //create thumbnail
                $hasilresize = fiestoresize("$cfg_thumb_path/" . $_FILES['filename']['name'], "$cfg_thumb_path/$modifiedfilename", 'l',$maxwidthlogo);
                if ($hasilresize != _SUCCESS) {
                    $action = createmessage(_DBERROR, _ERROR, "error", "modify");
                }
                unlink("$cfg_thumb_path/" . $_FILES['filename']['name']);

                //del gambar yang dioverwrite (hanya jika filename beda)
                $sql = "SELECT logo FROM sc_payment WHERE id='$pid'";
                $result = $mysql->query($sql);
                list($oldfilename) = $mysql->fetch_row($result);
                if ($modifiedfilename != $oldfilename && $oldfilename != '') {
                    unlink("$cfg_thumb_path/$oldfilename");
                }

                $sql = "UPDATE sc_payment SET logo='$modifiedfilename', bank='$bank', 
			cabang='$cabang',norekening='$norekening', 
			atasnama='$atasnama' WHERE id='$pid'";
            } else {
                $sql = "UPDATE sc_payment SET bank='$bank', 
			cabang='$cabang',norekening='$norekening', 
			atasnama='$atasnama' WHERE id='$pid'";
            }
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    } else {
        /* default value untuk form */
        $name = "";
        $keterangan = "";
        $action = "modify";
    }
}

if ($action == "modify") {
    $sql = "SELECT  id,logo,bank,cabang,norekening,atasnama FROM sc_payment WHERE id='$pid'";
    $result = $mysql->query($sql);

    $admintitle = _EDITPAYMENT;
    if ($mysql->num_rows($result) > 0) {
        list($id, $logo, $bank, $cabang, $norekening, $atasnama) = $mysql->fetch_row($result);

        if ($logo != '') {
            $admincontent .= '<img src="' . $cfg_thumb_url . '/' . $logo . '" alt="' . $title . '">';
        }
        $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="pid" value="' . $pid . '">
            <div class="control-group">
                <label class="control-label">' . _LOGO . '</label>
                <div class="controls">
                    <div data-provides="fileupload" class="fileupload fileupload-new">
                        <div class="input-append">
                            <div class="uneditable-input span2">
                                    <i class="icon-file fileupload-exists"></i>
                                    <span class="fileupload-preview"></span>
                            </div>
                            <span class="btn btn-file">
                                <span class="fileupload-new">Cari File</span>
                                <span class="fileupload-exists">Change</span>
                                <input type="file" name="filename" size="35">
                            </span>
                            <a data-dismiss="fileupload" class="btn fileupload-exists" href="#">
                            Remove
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' . _BANK . '</label>
                    <div class="controls">
                            <input type="text" name="bank" placeholder="' . _BANK . '" class="span12" value="' . $bank . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' ._CABANG. '</label>
                    <div class="controls">
                            <input type="text" name="cabang" placeholder="' . _CABANG . '" class="span12" value="' . $cabang . '">
                    </div>
            </div>
          
            <div class="control-group">
                    <label class="control-label">' ._NOREKENING. '</label>
                    <div class="controls">
                            <input type="text" name="norekening" placeholder="' . _NOREKENING . '" class="span12" value="' . $norekening . '">
                    </div>
            </div>
            <div class="control-group">
                    <label class="control-label">' ._ATASNAMA. '</label>
                    <div class="controls">
                            <input type="text" name="atasnama" placeholder="' ._ATASNAMA. '" class="span12" value="' . $atasnama . '">
                    </div>
            </div>
            <div class="control-group">
                    <div class="controls">
                            <input type="submit" name="submit" class="btn btn-danger" value="' . _SAVE . '">
                             <a href="'.param_url('?p=sc_payment').'" class="buton">' . _BACK . '</a>
                    </div>
            </div>
	</form>
    ';
    } else {
        $action = createmessage(_NOPOSTAGE, _INFO, "info", "");
    }
}

if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $admintitle = _DELPAYMENT;
    $sql = "SELECT id, logo FROM sc_payment WHERE id = '$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOPAYMENT, _INFO, "info", "");
    } else {
        list($id, $filename) = $mysql->fetch_row($result);
        if ($action == "remove") {
            $admincontent .= "<h5>" . _PROMPTDEL . "</h5>";
            $admincontent .= '<div class="control-group">
                                <div class="controls">
                                        <a class="btn btn-danger" href="'.param_url("?p=sc_payment&action=delete&pid=$id").'">' . _YES . '</a>
                                        <a class="btn" href="javascript:history.go(-1)">' . _NO . '</a></p>
                                </div>
                        </div>';
        } else {
            if ($filename != '' && file_exists("$cfg_thumb_path/$filename")) {
                unlink("$cfg_thumb_path/$filename");
            }
            $sql = "DELETE FROM sc_payment WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    }
}
if ($action == "" or $action == 'search') {
    $admintitle="";
	if( $action == 'search') 
	{
	 $admintitle = _SEARCHRESULTS;
	  $sql = "SELECT  id, bank,cabang,norekening,atasnama FROM sc_payment  WHERE bank LIKE '%$keyword%' OR cabang LIKE '%$keyword%' OR norekening LIKE '%$keyword%' OR atasnama LIKE '%$keyword%' ";
	}
	else
	{
	$sql = "SELECT  id, bank,cabang,norekening,atasnama  FROM sc_payment  ";
	}
    
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($mysql->num_rows($result) > 0) {
        $start = $screen * $max_page_list;

        $sql .= " ORDER BY $sort LIMIT $start, $max_page_list";
		
        $result = $mysql->query($sql);

        if ($pages > 1) {
            $adminpagination = pagination($namamodul, $screen, "");
        }
        $admincontent .= "<table class=\"stat-table\">\n";
        $admincontent .= "<tr>";
        $admincontent .= "<th>" . _BANK . "</th>";
        $admincontent .= "<th>" . _CABANG . "</th>";
        $admincontent .= "<th>" . _NOREKENING . "</th>";
        $admincontent .= "<th>" . _ATASNAMA . "</th>";
        $admincontent .= "<th align=\"center\">" . _EDIT . "</th></tr>\n";
        while (list($id, $bank,$cabang,$norekening,$atasnama) = $mysql->fetch_row($result)) {
            $admincontent .= "<td>$bank</td>";
            $admincontent .= "<td>$cabang</td>";
            $admincontent .= "<td>$norekening</td>";
            $admincontent .= "<td>$atasnama</td>";
            $admincontent .= "<td class=\"action-ud center\"><a href=\"".param_url("?p=sc_payment&action=modify&pid=$id")."\">";
            $admincontent .= "<img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a>\n";
            $admincontent .= "<a href=\"".param_url("?p=sc_payment&action=remove&pid=$id")."\">";
            $admincontent .= "<img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        createnotification(_NOPAYMENT, _INFO, "info");
    }
    $admincontent .= "<a href=\"".param_url("?p=sc_payment&action=add")."\" class=\"btn btn-danger\">" . _ADDPAYMENT . "</a>";
}

?>
