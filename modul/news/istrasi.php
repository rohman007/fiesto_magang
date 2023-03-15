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

$ishot = fiestolaundry($_POST['ishot'], 1);
$summary = fiestolaundry($_POST['summary'], 0, TRUE);
$dberita = fiestolaundry($_POST['dberita'], 2);
$mberita = fiestolaundry($_POST['mberita'], 2);
$yberita = fiestolaundry($_POST['yberita'], 4);
$jberita = fiestolaundry($_POST['jberita'], 200);
$iberita = fiestolaundry($_POST['iberita'], 0, TRUE);
// $jberitaedited = fiestolaundry($_POST['jberitaedited'], 200);
// $iberitaedited = fiestolaundry($_POST['iberitaedited'], 0, TRUE);
// $ishotedited = fiestolaundry($_POST['ishotedited'], 1);
$publish = fiestolaundry($_POST['publish'], 1);

$cat_id = fiestolaundry($_REQUEST['cat_id'], 11);
$action = fiestolaundry($_REQUEST['action'], 10);
$nama = fiestolaundry($_POST['nama'], 200);
$urutan = fiestolaundry($_POST['urutan'], 11);
$url = fiestolaundry($_POST['url'],255);

$modulename = $_GET['p'];
if (isset($_POST['back'])) {
    back($modulename);
}

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($jberita, _TITLE, false);
        $notificationbuilder .= validation($iberita, _NEWSCONTENT, false);
        $notificationbuilder .= validation($summary, _NEWSSUMMARY, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {
            if (!checkdate($mberita, $dberita, $yberita)) {
                $action = createmessage(_INVALIDDATE, _ERROR, "error", "add");
            }
            $sqlcat = "SELECT id FROM newscat WHERE id='$cat_id' ";
            $resultcat = $mysql->query($sqlcat);
            if ($mysql->num_rows($resultcat) == 0) {
                $action = createmessage(_CATEGORYERROR, _ERROR, "error", "add");
            }
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($jberita, 'newsdata', $pid) : seo_friendly_url($url, 'newsdata', $pid);
			}
            $tberita = "$yberita-$mberita-$dberita";
            $sql = "INSERT INTO newsdata (cat_id, judulberita, tglmuat, summary, isiberita, tglberita, ishot, publish, memberonly, url) VALUES ('$cat_id', '$jberita', NOW(), '$summary', '$iberita', '$tberita', '$ishot', '$publish', '$memberonly', '$url')";
            $result = $mysql->query($sql);
			$newid = $mysql->insert_id();
							
            if ($result) {
				//==========================================
				$sukses=true;
				$totalupload=count($_FILES['foto_add']['name']);
				if($totalupload>0)
				{
					$namathumbnail=array();
					
					$hasilupload = fiestouploadr('foto_add', $cfg_fullsizepics_path,'', $maxfilesize,$newid,$allowedtypes="gif,jpg,jpeg,png");
					if($hasilupload!=_SUCCESS){ $sukses=false;pesan(_ERROR,$hasilupload); }
					
					$thumb_namer=array();	
					$totalupload=count($_FILES['foto_add']['name']);
					for($x=0;$x<=$totalupload;$x++)
					{
						if($_FILES['foto_add']['name'][$x] != '') 
						{
							//ambil informasi basename dan extension
							$temp = explode(".",$_FILES['foto_add']['name'][$x]);
							$extension = strtolower($temp[count($temp)-1]);
							$basename = '';
							for ($i=0;$i<count($temp)-1;$i++) {
								$basename .= $temp[$i];
							}
							$thumb_name=$namathumbnail[$x];
							list($filewidth,$fileheight,$filetype,$fileattr) = getimagesize("$cfg_fullsizepics_path/".$thumb_name);
							
							//create thumbnail
							$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_thumb_path/$thumb_name",'l',$cfg_thumb_width);
							if ($hasilresize!=_SUCCESS){$sukses=false;pesan(_ERROR,$hasilresize);}
							
							if ($filewidth > $cfg_max_width)
							{
								//rename sambil resize gambar asli sesuai yang diizinkan
								
								$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_fullsizepics_path/$thumb_name",'w',$cfg_max_width);
								if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

								//del gambar asli
								//unlink("$cfg_fullsizepics_path/".$_FILES['filename']['name']);

							}
							
						}
					}
										
					if(count($namathumbnail)>0)
					{
						// $fotoutama=$namathumbnail[0];
						$jthumb_namer=join(":",$namathumbnail);
						$sql =	"UPDATE newsdata SET thumb='$jthumb_namer' WHERE id='$newid'";
						if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);
					}
					
					// if(count($namathumbnail)>0)
					// {
						// //ambil data thumb awal
						// $thumb_awalr=array();
						// // $s=$mysql->query("SELECT thumb from newsdata WHERE id='$newid' AND thumb<>''");
						// // if($s and $mysql->num_rows($s)>0)
						// // {
							// // list($thumb_awal)=$mysql->fetch_row($s);
							// // $thumb_awalr=explode(":",$thumb_awal);
						// // }
						// // //end ambil data thumb awal
						// $comb_thumb=$namathumbnail;
						// // if(count($thumb_awalr)>0)
						// // {
							// // $comb_thumb=array_merge($thumb_awalr, $namathumbnail);
						// // }
						// // $jthumb_namer=join(":",$comb_thumb);
						
						// $sql =	"UPDATE newsdata SET thumb='$jthumb_namer' WHERE id='$newid'";
						// echo $sql;
						// if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);
					// }					
					
				}				
				//==========================================			
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "home");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "add");
            }
        }
    } else {
        $action = "add";
    }
}
if ($action == "add") {
    $catselect = adminselectcategories('newscat');
    $admintitle = _ADDNEWS;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
	  <input type="hidden" name="action" value="save">';
	$admincontent .= '<div class="control-group">
	        <label class="control-label control-label-produk">' . _INSTALLATION_PICTURE . '</label>';
	$admincontent .= '	
	<div class="input_foto_tambahanx controls">
	<div data-provides="fileupload" class="fileupload fileupload-new">
		<div class="input-append">
			<div class="uneditable-input span2">
				<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
			</div>
			<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Ganti</span>
			<input type="file" id="files" name="foto_add[]" multiple accept="image/*" />
			</span>
		</div>
		</div>

	</div>';
	include('tambahfoto.php');
	$admincontent .= '</div>';
	$admincontent .= '<div class="control-group">
			<label class="control-label control-label-produk">' . _CATEGORY . '</label>
			<div class="controls">' . $catselect . '</div>
		  </div>
	      <div class="control-group">
	        <label class="control-label control-label-produk">' . _NEWSDATE . '</label>
	        <div class="controls">';

    $hariini = getdate();

    $admincontent .= "<select class=\"tanggal\" name=\"dberita\">";
    for ($i = 1; $i <= 31; $i++) {
        if ($hariini[mday] == $i) {
            $admincontent .= "<option value=\"$i\" selected>$i</option>\n";
        } else {
            $admincontent .= "<option value=\"$i\">$i</option>\n";
        }
    }
    $admincontent .= "</select>";

    $admincontent .= "<select class=\"bulan\" name=\"mberita\">";
    for ($i = 1; $i <= 12; $i++) {
        $j = $i - 1;
        if ($hariini[mon] == $i) {
            $admincontent .= "<option value=\"$i\" selected>$namabulan[$j]</option>\n";
        } else {
            $admincontent .= "<option value=\"$i\">$namabulan[$j]</option>\n";
        }
    }
    $admincontent .= "</select>";

    $admincontent .= "<select class=\"tahun\" name=\"yberita\">";
    for ($i = 2001; $i <= 2025; $i++) {
        if ($hariini[year] == $i) {
            $admincontent .= "<option value=\"$i\" selected>$i</option>\n";
        } else {
            $admincontent .= "<option value=\"$i\">$i</option>\n";
        }
    }
    $admincontent .= "</select>";

	if ($pakaislug) {
		$slugcontent = '<div class="control-group">';
		$slugcontent .= '<label class="control-label control-label-produk">'._SLUGURL.'</label>';
		$slugcontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$url."\"/></div>";
		$slugcontent .= '</div>';
	}
	
    $admincontent .= '
	        </div>
	      </div>
	      <div class="control-group">
	        <label class="control-label control-label-produk">' . _TITLE . '</label>
	        <div class="controls"><input type="text" name="jberita" size="40"> <input type="checkbox" name="ishot" value="1" checked>Hot</div>
	      </div>
		  '.$slugcontent.'
	      <div class="control-group">
	        <label class="control-label control-label-produk">' . _NEWSSUMMARY . '</label>
	        <div class="controls"><textarea name="summary" cols="60" rows="10" class="usetiny"></textarea></div>
	      </div>
	      <div class="control-group">
	        <label class="control-label control-label-produk">' . _NEWSCONTENT . '</label>
	        <div class="controls"><textarea name="iberita" cols="60" rows="20" class="usetiny"></textarea></div>
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
        $notificationbuilder .= validation($jberita, _TITLE, false);
        $notificationbuilder .= validation($iberita, _NEWSCONTENT, false);
        $notificationbuilder .= validation($summary, _NEWSSUMMARY, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
        } else {
            if (!checkdate($mberita, $dberita, $yberita)) {
                $action = createmessage(_INVALIDDATE, _ERROR, "error", "modify");
            }
            $sqlcat = "SELECT id FROM newscat WHERE id='$cat_id' ";
            $resultcat = $mysql->query($sqlcat);
            if ($mysql->num_rows($resultcat) == 0) {
                $action = createmessage(_CATEGORYERROR, _ERROR, "error", "modify");
            }
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($jberita, 'newsdata', $pid) : seo_friendly_url($url, 'newsdata', $pid);
			}
            $tglberita = "$yberita-$mberita-$dberita";
            $sql = "UPDATE newsdata SET cat_id='$cat_id', judulberita='$jberita', 
		tglberita='$tglberita', summary='$summary', isiberita='$iberita', ishot='$ishot', 
		publish='$publish', memberonly='$memberonly', url='$url' WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
				//==========================================
				$sukses=true;
				$totalupload=count($_FILES['foto_add']['name']);
				if($totalupload>0)
				{
					$namathumbnail=array();
					
					$hasilupload = fiestouploadr('foto_add', $cfg_fullsizepics_path,'', $maxfilesize,$pid,$allowedtypes="gif,jpg,jpeg,png");
					if($hasilupload!=_SUCCESS){ $sukses=false;pesan(_ERROR,$hasilupload); }
					
					$thumb_namer=array();	
					$totalupload=count($_FILES['foto_add']['name']);
					for($x=0;$x<=$totalupload;$x++)
					{
						if($_FILES['foto_add']['name'][$x] != '') 
						{
							//ambil informasi basename dan extension
							$temp = explode(".",$_FILES['foto_add']['name'][$x]);
							$extension = strtolower($temp[count($temp)-1]);
							$basename = '';
							for ($i=0;$i<count($temp)-1;$i++) {
								$basename .= $temp[$i];
							}
							$thumb_name=$namathumbnail[$x];
							list($filewidth,$fileheight,$filetype,$fileattr) = getimagesize("$cfg_fullsizepics_path/".$thumb_name);
							
							//create thumbnail
							$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_thumb_path/$thumb_name",'l',$cfg_thumb_width);
							if ($hasilresize!=_SUCCESS){$sukses=false;pesan(_ERROR,$hasilresize);}
							
							if ($filewidth > $cfg_max_width)
							{
								//rename sambil resize gambar asli sesuai yang diizinkan
								
								$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_fullsizepics_path/$thumb_name",'w',$cfg_max_width);
								if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

								//del gambar asli
								//unlink("$cfg_fullsizepics_path/".$_FILES['filename']['name']);

							}
							
						}
					}
										
					/* if(count($namathumbnail)>0)
					{
						$fotoutama=$namathumbnail[0];
						$jthumb_namer=join(":",$namathumbnail);
						$sql =	"UPDATE page SET thumb='$jthumb_namer' WHERE id='$idpage'";
						if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);
					} */
					if(count($namathumbnail)>0)
					{
						//ambil data thumb awal
						$thumb_awalr=array();
						$s=$mysql->query("SELECT thumb from newsdata WHERE id='$pid' AND thumb<>''");
						if($s and $mysql->num_rows($s)>0)
						{
							list($thumb_awal)=$s->fetch_row();
							$thumb_awalr=explode(":",$thumb_awal);
						}
						//end ambil data thumb awal
						$comb_thumb=$namathumbnail;
						if(count($thumb_awalr)>0)
						{
							$comb_thumb=array_merge($thumb_awalr, $namathumbnail);
						}
						$jthumb_namer=join(":",$comb_thumb);
						
						$sql =	"UPDATE newsdata SET thumb='$jthumb_namer' WHERE id='$pid'";
						if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);
					}					
					
				}				
				//==========================================					
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "home");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    } else {
        $action = "modify";
    }
}
if ($action == "modify") {
    $admintitle = _EDITNEWS;
    $sql = "SELECT id, cat_id, tglmuat, judulberita, summary, isiberita, tglberita, ishot, publish, memberonly, thumb, url FROM newsdata WHERE id='$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NONEWS, _INFO, "info", "");
    } else {
        list($id, $cat_id, $tglmuat, $judulberita, $summary, $isiberita, $tglberita, $ishot, $publish, $memberonly, $thumb, $url) = $mysql->fetch_row($result);
        $publishstatus = ($publish == 1) ? 'checked' : '';
        $memberonlystatus = ($memberonly == 1) ? 'checked' : '';
        $catselect = adminselectcategories('newscat', $cat_id);
        $admincontent .= '
			<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
			  <input type="hidden" name="action" value="update">
			  <input type="hidden" name="pid" value="' . $pid . '">';
				$admincontent .= '
					<div class="control-group">';
							if($action=='modify')
		{
			$admincontent .= "<div id=\"product_foto_group\" style=\"line-height:30px;\">";
			if($thumb!='')
			{
				$thumbr=explode(":",$thumb);
				
				foreach($thumbr as $i => $v)
				{
					// $checked="";
					// if($hasil['filename']==$v)
					// {
						// $checked="checked='checked'";
					// }
					$admincontent .= "
					<div class='catalog_foto_add' id='catalog_foto$i'>
					<img src='".$cfg_thumb_url."/".$v."' ".$imagesize." alt='".$hasil['title']."'>
					<a class='catalog_foto_del' data-img='$v' data-id='$pid' data-div='catalog_foto$i' ><img alt=\""._DEL."\" border=\"0\" src=\"../images/deletepic.png\"></a>
											
					</div>";
				}
			}			
			$admincontent .=  "</div>";
		}
		else
		{
			$admincontent .=  "<div id=\"product_foto_group\" style=\"line-height:30px;\"></div>";
		}
				$admincontent .= '</div>
				';			
				$admincontent .= '
					<div class="control-group">
						<label class="control-label control-label-produk">' . _INSTALLATION_PICTURE . '</label>
						<div class="input_foto_tambahanx controls">
							<div data-provides="fileupload" class="fileupload fileupload-new">
								<div class="input-append">
									<div class="uneditable-input span2">
										<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
									</div>
									<span class="btn btn-file"><span class="fileupload-new">Cari File</span><span class="fileupload-exists">Ganti</span>
									<input type="file" id="files" name="foto_add[]" multiple accept="image/*" />
									</span>
								</div>
							</div>
						</div>';
				include('tambahfoto.php');
				$admincontent .= '</div>
				';				
				$admincontent .= '<div class="control-group">
					<label class="control-label control-label-produk">' . _CATEGORY . '</label>
					<div class="controls">' . $catselect . '</div>
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _NEWSDATE . '</label>
					<div class="controls">';

        $tberita = explode("-", $tglberita);

        $admincontent .= "<select class=\"tanggal\" name=\"dberita\">";
        for ($i = 1; $i <= 31; $i++) {
            if ($tberita[2] == $i) {
                $admincontent .= "<option value=\"$i\" selected>$i</option>\n";
            } else {
                $admincontent .= "<option value=\"$i\">$i</option>\n";
            }
        }
        $admincontent .= "</select>";

        $admincontent .= "<select class=\"bulan\" name=\"mberita\">";
        for ($i = 1; $i <= 12; $i++) {
            $j = $i - 1;
            if ($tberita[1] == $i) {
                $admincontent .= "<option value=\"$i\" selected>$namabulan[$j]</option>\n";
            } else {
                $admincontent .= "<option value=\"$i\">$namabulan[$j]</option>\n";
            }
        }
        $admincontent .= "</select>";

        $admincontent .= "<select class=\"tahun\" name=\"yberita\">";
        for ($i = 2001; $i <= 2025; $i++) {
            if ($tberita[0] == $i) {
                $admincontent .= "<option value=\"$i\" selected>$i</option>\n";
            } else {
                $admincontent .= "<option value=\"$i\">$i</option>\n";
            }
        }
        $admincontent .= "</select>";

		if ($pakaislug) {
			$slugcontent = '<div class="control-group">';
			$slugcontent .= '<label class="control-label control-label-produk">'._SLUGURL.'</label>';
			$slugcontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$url."\"/></div>";
			$slugcontent .= '</div>';
		}		
        $admincontent .= '
					</div>
				  </div>
				  <div class="control-group">
					<label class="control-label control-label-produk">' . _TITLE . '</label>
					<div class="controls"><input type="text" name="jberita" size="40" value="' . $judulberita . '"> ';

        if ($ishot == '1') {
            $admincontent .= '<input type="checkbox" name="ishot" value="1" checked>Hot</div>';
        } else {
            $admincontent .= '<input type="checkbox" name="ishot" value="1">Hot</div>';
        }

        $admincontent .= '
				  </div>
				  '.$slugcontent.'
				  <div class="control-group">
					<label class="control-label control-label-produk">' . _NEWSSUMMARY . '</label>
					<div class="controls"><textarea name="summary" cols="60" rows="10" class="usetiny">' . $summary . '</textarea></div>
				  </div>
				  <div class="control-group">
					<label class="control-label control-label-produk">' . _NEWSCONTENT . '</label>
					<div class="controls"><textarea name="iberita" cols="60" rows="20" class="usetiny">' . $isiberita . '</textarea></div>
				  </div>
				  <div class="control-group">
					<label class="control-label">' . _PUBLISHED . '</label>
					<div class="controls"><input type="checkbox" name="publish" value="1" ' . $publishstatus . ' /></div>
				  </div>
				  <div class="control-group">
					<div class="controls">
                                            <input type="submit" name="submit" class="buton" value="' . _EDIT . '">
                                            <input type="submit" name="back" class="buton" value="' . _BACK . '">   
                                        </div>
				  </div>
			</form>';
    }
}

// DELETE NEWS
if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $sql = "SELECT id, cat_id, tglmuat, judulberita, summary, isiberita, tglberita, ishot, publish, thumb FROM newsdata WHERE id = '$pid'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createnotification(_NONEWS, _INFO, "info");
    } else {
        list($id, $cat_id, $tglmuat, $judulberita, $summary, $isiberita, $tglberita, $ishot, $publish, $thumb) = $mysql->fetch_row($result);
        if ($action == "remove") {
            $admintitle = _DELNEWS;
            $admincontent = "<h5>" . _PROMPTDEL . "</h5>";
            $admincontent .= "<a class=\"buton\" href=\"?p=news&action=delete&pid=$pid\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a>";
        } else {
			if($thumb!='')
			{
				$dthumb=explode(":",$thumb);
				if(count($dthumb)>0)
				{
					foreach($dthumb as $i => $v)
					{
						if(file_exists("$cfg_fullsizepics_path/$v")) @unlink("$cfg_fullsizepics_path/$v");
						if(file_exists("$cfg_thumb_path/$v")) @unlink("$cfg_thumb_path/$v");
					}
				}
			}		
            $sql = "DELETE FROM newsdata WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "home");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "home");
            }
        }
    }
}

// CLEAN NEWS
if ($action == "clean" || $action == "doclean") {

    if ($action == "clean") {
		$sql = "SELECT date_format(tglberita,'%Y') Tahun,count(id) Jumlah FROM newsdata group by date_format(tglberita,'%Y')";
		$result = $mysql->query($sql);
		if ($result and $mysql->num_rows($result) > 0) {
			$admintitle .="<ul class=\"delberita\">";
			while ($d = $mysql->fetch_assoc($result)) {

				$admintitle .= "<li>" . preg_replace(array("/#tahun/", "/#jumlah/"), array($d['Tahun'], $d['Jumlah']), _NUMEXISTINGNEWS) . "</li>";
			}
			$admintitle .= "</ul>";
		}	
        if ($_POST['confirm']) {
            $tgl = fiestolaundry($_POST['tgl'], 2);
            $bln = fiestolaundry($_POST['bln'], 2);
            $thn = fiestolaundry($_POST['thn'], 4);
            if (!checkdate($bln, $tgl, $thn)) {
                $action = createmessage(_ERRORDATE, _ERROR, "error", "");
            } else {
                $admintitle .= "<h5>" . _PROMPTDEL . "</h5>";
                $tglberita.="$thn-$bln-$tgl";
                $admincontent .= "<a class=\"buton\" href=\"?p=news&action=doclean&tglberita=$tglberita\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a>";
            }
        } else {
            $combo_tgl = "<select name='tgl' class=\"tanggal\">";
            for ($i = 1; $i <= 31; $i++) {
                $selected = $i == intval(date('d')) ? "selected=selected " : "";
                $combo_tgl.="<option value='$i' $selected>$i</option>";
            }
            $combo_tgl.="</select>";
            $combo_bln = "<select name='bln' class=\"bulan\">";
            for ($i = 1; $i <= 12; $i++) {
                $selected = $i == intval(date('m')) ? "selected=selected " : "";
                $combo_bln.="<option value='$i' $selected>$i</option>";
            }
            $combo_bln.="</select>";


            $combo_thn = "<select name='thn' class=\"tahun\">";
            $sql_tahun = "SELECT min(DATE_FORMAT(tglberita,'%Y')) Tahun FROM newsdata  ";
            $res_tahun = $mysql->query($sql_tahun);
            $tahun_min = "";
            if ($res_tahun and $mysql->num_rows($res_tahun)) {
                $d_tahun = $res_tahun->fetch_row();
                $tahun_min = $d_tahun[0];
            }
            if (strlen($tahun_min) == 0) {
                $tahun_min = date("Y");
            }
            $tahun_now = date("Y");
            for ($i = $tahun_min; $i <= $tahun_now; $i++) {
                $combo_thn.="<option value='$i'>$i</option>";
            }
            $combo_thn.="</select>";

            $admincontent .="<form class='form-horizontal' method='post' target='?p=news&action=doclean'>
			<div class=\"control-group\"><div class=\"control-label\" style=\"font-weight:bold;font-size:12pt;\">" . _NEWSDATE . " </div>
			<div class=\"controls\">$combo_tgl $combo_bln $combo_thn</div></div>
			<input type='hidden' name='action' value='clean'/>
			<input type='hidden' name='confirm' value='yes'/>
			";
             $admincontent .='
	      <div class="control-group">
	       <div class="controls"><input type="submit" name="submit" class="buton"  value="' . _DELNEWS . '"></div>
	      </div>
	</form>
    ';
        }
    } else {
        $tglberita = fiestolaundry($_GET['tglberita'], 10);
        if (strlen($tglberita) > 0) {
            $sql = "DELETE FROM newsdata WHERE tglberita<'$tglberita'";
            $result = $mysql->query($sql);
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "home");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "");
            }
        } else {
            $action = createmessage(_DBERROR, _ERROR, "error", "");
        }
    }
}

// VIEW CATEGORY
if ($action == "home" || $action == "") {
	$idcat = fiestolaundry($_GET['searchbycat'],11);
	$searchbycat="<select name=\"searchbycat\" id=\"searchbycat\" onchange=\"document.form_product_cat.submit()\">\n";
	
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama FROM newscat ORDER BY urutan ';
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_assoc($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>0,'level'=>0);
	}
	$cats->get_cats($mycats);
	$searchbycat.="<option value=\"\" >"._ALLCATEGORY."</option>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		$cat_name = $cats->cats[$i]['nama'];
		$searchbycat.= '<option value="'.$cats->cats[$i]['id'].'"';
		$searchbycat.= ($cats->cats[$i]['id']==$idcat) ? " selected='selected'>$topcatnamecombo" : ">$topcatnamecombo";
		for ($a=0; $a<count($cats->cat_map); $a++) {
			$cat_parent_id = $cats->cat_map[$a]['id'];
			$cat_parent_name = $cats->cat_map[$a]['nama'];
			$searchbycat.= " / $cat_parent_name";
		}
		$searchbycat.=" / $cat_name</option>";
	}
	$searchbycat.="</select>\r\n";

	$optionmenu.="	<form name=\"form_product_cat\" action=\"$thisfile\" method=\"GET\" class=\"form-vertical\">
	<input type=\"hidden\" value=\"news\" name=\"p\">".param_input().""._BROWSEBYCATEGORY." $searchbycat</div>
	</form>";

    // $sql = "SELECT id FROM newsdata WHERE cat_id='$cat_id'";
    $sql = "SELECT id FROM newsdata";
	if ($idcat!='') $sql .= " WHERE cat_id='$idcat' ";
	
    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);
	
    if ($total_records == "0") {
        $admincontent .= createmessage(_NONEWS, _INFO, "info", "");
    } else {
        //menampilkan record di halaman yang sesuai
        $start = $screen * $max_page_list;
        // $sql = "SELECT id, tglberita, judulberita, clickctr, ishot FROM newsdata WHERE cat_id='$cat_id' ORDER BY tglberita DESC LIMIT $start, $max_page_list";
        $sql = "SELECT id, tglberita, judulberita, clickctr, ishot FROM newsdata ";
		if ($idcat != '') {
			$sql .= " WHERE cat_id='$idcat' ";
		}
		$sql .= "ORDER BY tglberita DESC LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

		$admincontent .= "<p><a class=\"buton\" href=\"?p=news&action=add\">" . _ADDNEWS . "</a></p>";	
		
		// $cat_id = ($idcat!='') ? $idcat : $cat_id;
        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen, "action=home&cat_id=$cat_id&searchbycat=$idcat");
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
        $admincontent .= "<tr><th>" . _PUBLISHEDDATE . "</th><th>" . _TITLE . "</th><th>" . _READ . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($id, $tglberita, $judulberita, $clickctr, $ishot) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr class=\"newshotis$ishot\">\n";
            $admincontent .= "<td>" . tglformat($tglberita) . "</td><td>$judulberita</td><td align=\"right\">$clickctr</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"?p=$modulename&action=modify&pid=$id\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$id\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    }
    $admincontent .= "<a class=\"buton\" href=\"?p=news&action=add\">" . _ADDNEWS . "</a>";
}

// ADD/EDIT/DEL CATEGORY
// action add--> savecat
// action edit --> updatecat
// action del --> deletecat
if ($action == "savecat") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _CATNAME, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "catnew");
        } else {
            if (!preg_match('/[0-9]*/', $urutan))
                $urutan = 1;
            $mx = getMaxNumber('newscat', 'urutan') + 2;
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($nama, 'newscat', $pid) : seo_friendly_url($url, 'newscat', $pid);
			}
            $sql = "INSERT newscat (nama, urutan, url) values ('$nama','$mx', '$url')";
            $result = $mysql->query($sql);
            $mid = $mysql->insert_id();
            $result = urutkan('newscat', $urutan, "", $mid, "");
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
				$action = 'main';
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "catnew");
            }
        }
    } else {
        // $action = "catnew";
        $action = "main";
    }
}
if ($action == "updatecat") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nama, _CATNAME, false);
        $notificationbuilder .= validation($cat_id, _CATID, false);
//        $nama = checkrequired($cat_name, _CATNAME);
//        $cat_id = checkrequired($cat_id, _CATID);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "catedit");
        } else {
            if (!preg_match('/[0-9]*/', $urutan))
                $urutan = 1;

            $kondisiprev = "";
            $kondisi = "";
			
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($nama, 'newscat', $pid) : seo_friendly_url($url, 'newscat', $pid);
			}
            $sql = "UPDATE newscat set nama='$nama', url='$url' WHERE id='$cat_id'";
            $result = $mysql->query($sql);
            $result = urutkan('newscat', $urutan, $kondisi, $cat_id, $kondisiprev);
            if ($kondisi != $kondisiprev) {
                urutkansetelahhapus('newscat', $kondisiprev);
            }
            if ($result) {
                $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "catedit");
            }
        }
    } else {
        $action = "catedit";
    }
}

if ($action == "catnew" || $action == "catedit") {
//    $admincontent .= "<form method=\"POST\" action=\"$thisfile\">";
    $admincontent .= '
		  <form class="form-horizontal" method="POST" action="' . $thisfile . '">';
    if ($action == "catnew") {
        $admintitle .= _ADDCAT;
        $admincontent .= '<input type="hidden" name="action" value="savecat">';
    }
    if ($action == "catedit") {
        if (empty($cat_id)) {
            $action = createmessage(_NOCAT, _INFO, "info", "");
        }
        $admintitle .= _EDITCAT;
        $sql = "SELECT nama,urutan,url FROM newscat WHERE id=$cat_id ORDER BY urutan";
        $result = $mysql->query($sql);
        if ($mysql->num_rows($result) == 0) {
            $action = createmessage(_NOCAT, _INFO, "info", "");
        }
        $admincontent .= '<input type="hidden" name="action" value="updatecat">';
        $admincontent .= '<input type="hidden" name="cat_id" value="' . $cat_id . '">';
        list($cat_name, $cat_order, $url) = $result->fetch_row();
    }
    $admincontent .=' <div class="control-group">
                                <label class="control-label">' . _CATNAME . '</label>
                                <div class="controls">
                                    <input type="text" name="nama" value="' . $cat_name . '" placeholder="' . _CATNAME . '" size="40">
                                </div>
                        </div>';
	if ($pakaislug) {
		$admincontent .= '<div class="control-group">';
		$admincontent .= '<label class="control-label">'._SLUGURL.'</label>';
		$admincontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$url."\"/></div>";
		$admincontent .= '</div>';
	}
    $admincontent .=' <div class="control-group">
                                <label class="control-label">' . _ORDER . '</label>
                                <div class="controls">';

    $admincontent .= '<div id="urutancontent">';
    $admincontent .= createurutan("newscat", "", $cat_id);
    $admincontent .= '</div>';
    $admincontent .= '</div></div>';
    $admincontent .= '    <div class="control-group">
                           <div class="controls">
                               <input type="submit" name="submit" class="buton" value="' . _SAVE . '">
                               <input type="submit" name="back" class="buton" value="' . _BACK . '">
                            </div>
                        </div>';
    $admincontent .= '</form>';
}

if ($action == "catdel") {
    if (empty($cat_id)) {
        $action = createmessage(_NOCAT, _INFO, "info", "");
    }
    $admintitle .= _DELCAT;
    $sql = "SELECT nama,urutan FROM newscat WHERE id=$cat_id ORDER BY urutan";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == 0) {
        $action = createmessage(_NOCAT, _INFO, "info", "");
    }
    $admincontent = "<h5>" . _PROMPTDEL . "</h5>";
    $admincontent .= "<a class=\"buton\" href=\"?p=news&action=deletecat&cat_id=$cat_id\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a>";
}

if ($action == "deletecat") {
    $cat_id = checkrequired($cat_id, _CATID);

    $sql = "SELECT * FROM newscat ";
    $result = $mysql->query($sql);

    if ($mysql->num_rows($result) <= 1) {
        $action = createmessage(_1CAT, _ERROR, "error", "");
    }

    $sql = "DELETE FROM newscat WHERE id='$cat_id'";
    if (!$mysql->query($sql)) {
        $action = createmessage(_DBERROR, _ERROR, "error", "");
    } else {
        urutkansetelahhapus("newscat", "");
    }

    $sql = "DELETE FROM newsdata WHERE cat_id='$cat_id'";
    if ($mysql->query($sql)) {
        $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "main");
    } else {
        $action = createmessage(_DBERROR, _ERROR, "error", "");
    }
}
// if ($action == "") {
    // $admintitle = "";
    // $admincontent = adminlistcategories('newscat', 'news');

    // $sqlcat = "SELECT id FROM newscat ";
    // $resultcat = $mysql->query($sqlcat);

    // $admincontent .= "<a class=\"buton\" href=\"?p=news&action=main\">" . _ADDCAT . "</a> ";
    // if ($mysql->num_rows($resultcat) > 0) {
        // $admincontent .= "<a class=\"buton\" href=\"?p=news&action=add\">" . _ADDNEWS . "</a> <a class=\"buton\" href=\"?p=news&action=clean\">" . _CLEANNEWS . "</a>";
    // } else {
        // createnotification(_NONEWS, _INFO, "info");
    // }
// }
if ($action == "search") {
    $keyword = strip_tags($keyword);
    $r_keyword = preg_split("/[\s,]+/", $keyword);
    $sql = "SELECT id FROM newsdata WHERE publish='1' AND (";

    //	$sql  = "SELECT id FROM catalogdata WHERE (";
    foreach ($r_keyword as $j => $splitkeyword) {
        $sql .= ($j == 0) ? "" : " AND ";
        $sql .= "judulberita LIKE '%$splitkeyword%'";
    }
    $sql .= ")";

    foreach ($searchedcf as $fieldygdisearch) {
        $sql .= " OR (";
        foreach ($r_keyword as $j => $splitkeyword) {
            $sql .= ($j == 0) ? "" : " AND ";
            $sql .= "$fieldygdisearch LIKE '%$splitkeyword%'";
        }
        $sql .= ")";
    }

    $result = $mysql->query($sql);
    $total_records = $mysql->num_rows($result);
    $pages = ceil($total_records / $max_page_list);

    if ($total_records == 0) {
        createnotification(_NONEWS, _INFO, "info");
    } else {
        //menampilkan record di halaman yang sesuai
        $start = $screen * $max_page_list;
        $sql = "SELECT id, tglberita, judulberita, clickctr, ishot FROM newsdata WHERE (judulberita LIKE '%$keyword%' OR summary LIKE '%$keyword%' OR isiberita LIKE '%$keyword%') AND publish='1' ORDER BY tglberita DESC LIMIT $start, $max_page_list";
        $result = $mysql->query($sql);

        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen, "action=home&cat_id=$cat_id");
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\">\n";
        $admincontent .= "<tr><th>" . _PUBLISHEDDATE . "</th><th>" . _TITLE . "</th><th>" . _READ . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($id, $tglberita, $judulberita, $clickctr, $ishot) = $mysql->fetch_row($result)) {
            $admincontent .= "<tr class=\"newshotis$ishot\">\n";
            $admincontent .= "<td>" . tglformat($tglberita) . "</td><td>$judulberita</td><td align=\"right\">$clickctr</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"?p=$modulename&action=modify&pid=$id\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"?p=$modulename&action=remove&pid=$id\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";

            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    }
    $admincontent .= "<a class=\"buton\" href=\"?p=news&action=add\">" . _ADDNEWS . "</a> <a class=\"buton\"href=\"?p=news\">" . _BACK . "</a>";
}

if ($action == 'main') {
	$catcontent .="<div class=\"content-widgets gray boxappear1\">";
	$admintitle = _KATEGORI;
	$catcontent .="<form class=\"form-horizontal\" method=\"POST\" action=\"index.php?p=news&action=savecat\">";
	$catcontent .="<div class=\"widget-head bondi-blue grayhead\"><h3>Tambah Kategori</h3></div>";
	$catcontent .="<div class=\"control-group\"><label class=\"control-label\">"._CATNAME."</label>";
	$catcontent .="<div class=\"controls\"><input type=\"text\" name=\"nama\" id=\"nama\" /></div></div>";
	if ($pakaislug) {
		$catcontent .= '<div class="control-group">';
		$catcontent .= '<label class="control-label">'._SLUGURL.'</label>';
		$catcontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$url."\"/></div>";
		$catcontent .= '</div>';
	}
	$catcontent .= "<div class=\"control-group\"><div class=\"controls\"><input class=\"buton\" type=\"submit\" name=\"submit\" value=\""._SAVE."\"></div></div>";
	$catcontent .="</div>";
	$catcontent .="</form>";	
	$catcontent .="</div>";	
	
	$catcontent .="<div id='kategori'>";
	$catcontent .= catstructure('SELECT id, nama FROM newscat order by urutan');
	$catcontent .="</div>";
}

$admincontent .= "<div id=\"catnav\"></div>";
$admincontent .= "<div id=\"catcontent\">$catcontent</div>";

?>
