<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$action = fiestolaundry($_REQUEST['action'], 20);
//$sql = "SELECT name, value, namatampilan, modul, tipeform, maxlength FROM config where modul in (SELECT nama FROM module) ";
//if (!$pakaicart) {
//    $sql.=" AND modul<>'cart' ";
//}
//$sql.="  ORDER BY modul";
//$result = $mysql->query($sql);
$hiddenmodul="'webmember'";
$hiddenvar="'showtype','isfriendly','harusmember','prod_attribut','hidesimplestep','hidetips'";
//'paymentnote','thanksmessage','citynotfoundmsg'
$inclusion = "'titletag','metadescription','metakeyword'";
$config = fiestolaundry($_POST['config'], 9);

if ($action == "setsite") {

    $sql = "SELECT name, value, namatampilan, modul, tipeform, maxlength FROM config "
            . "where modul in (SELECT nama FROM module) ";
    //if (!$pakaicart) {
        //$sql.=" AND modul<>'cart' ";
		
    //}
    if ($config == 'dashboard') {
        $sql .= " AND name IN ($inclusion) ";
    } elseif ($config == 'site') {
		$sql .= " AND modul NOT IN ($hiddenmodul) ";
        $sql .= " AND name NOT IN ($inclusion) ";
    }
	$sql.=" AND name NOT IN($hiddenvar)";
    $sql.="  ORDER BY modul";
    
    $result = $mysql->query($sql);
    while ($urasi = $mysql->fetch_array($result)) {
        $tipeform = explode(';', $urasi['tipeform']);
        $ishtmlok = ($tipeform[0] == 'textarea' && $tipeform[3] == 'usetiny') ? 'TRUE' : 'FALSE';
        if ($urasi['name'] == 'favicon') {
            $oldextension = $urasi['value'];
        } elseif ($urasi['name'] != 'templatefolder') {
            if ($urasi['name'] == 'gacode') {
                $_POST[$urasi['name']] = addslashes($_POST[$urasi['name']]);
            } else {
                $_POST[$urasi['name']] = fiestolaundry($_POST[$urasi['name']], $urasi['maxlength'], $ishtmlok);
            }
            $sql1 = "UPDATE config SET value='" . $_POST[$urasi['name']] . "' WHERE name='" . $urasi['name'] . "'";
            // echo $sql1;
            if (!$mysql->query($sql1))
                $action = createmessage(_DBERROR, _ERROR, "error", "");
        }
    }
    if ($_FILES['favicon']['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
        $hasilupload = fiestoupload('favicon', "$cfg_file_path/file", 'favicon', 100000, 'ico,jpg,jpeg,gif,png');
        //ambil informasi extension
        $temp = explode(".", $_FILES['favicon']['name']);
        $extension = $temp[count($temp) - 1];
        if ($hasilupload == _SUCCESS) {
            //jika ekstension tidak sama dengan file terdahulu, del file lama
            if ($extension != $oldextension)
                unlink("$cfg_file_path/file/favicon.$oldextension");
            $sql1 = "UPDATE config SET value='$extension' WHERE name='favicon'";
            // echo $sql1;
            if (!$mysql->query($sql1))
                $action = createmessage(_DBERROR, _ERROR, "error", "");
        } else {
            $action = createmessage($hasilupload, _ERROR, "error", "");
        }
    }
    // die;
    $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");

    if ($config == 'dashboard')
        $action = 'dashboard';
    else
        $action = '';
    // $specialadmin = "<a href=\"?p=site\" class=\"buton\">" . _BACK. "</a>";
}

if ($action == "dashboard") {
    $currentmodule = '';

    $sql = "SELECT name, value, namatampilan, modul, tipeform, maxlength FROM config "
            . "where name in ($inclusion) ";
			
  //  if (!$pakaicart) {
        //$sql.=" AND modul<>'cart' ";
		//$sql .= " AND modul NOT IN ($hiddenmodul) ";
  //  }
  	$sql.=" AND name NOT IN($hiddenvar)";
    $sql.="  ORDER BY FIELD(name,$inclusion) ";
    $result = $mysql->query($sql);

    $index = 0;
    $admincontent .= "<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\" enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"action\" value=\"setsite\">
			<input type=\"hidden\" name=\"config\" value=\"dashboard\">";
    while ($urasi = $mysql->fetch_array($result)) {
        if ($currentmodule != $urasi['modul']) {
            $index++;
            if ($index == 1) {
                $admincontent .= "<div class=\"tab-pane active\" id=\"tab$index\">\r\n";
            } else {
                $admincontent .= "</div>\r\n";
                $admincontent .= "<div class=\"tab-pane\" id=\"tab$index\">\r\n";
            }
        }
        $tipeform = explode(';', $urasi['tipeform']);


        switch ($tipeform[0]) {
            case 'text':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <input type=\"text\" name=\"" . $urasi['name'] . "\" value=\"" . $urasi['value'] . "\" 
                                                size=\"" . $tipeform[1] . "\" max_length=\"" . $urasi['maxleength'] . "\"/>\r\n
                                    </div>\r\n
                            </div>\r\n";
                break;
            case 'textarea':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <textarea name=\"" . $urasi["name"] . "\" cols=\"" . $tipeform[1] . "\" rows=\"" . $tipeform[2] . "\" class=\"notiny span12 " . $tipeform[3] . "\">" . $urasi["value"] . "</textarea>
                                    </div>\r\n
                            </div>\r\n";
                break;
            case 'combo':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                    <select name=\"" . $urasi['name'] . "\">\r\n";
                $pilihanvalue = explode(',', $tipeform[1]);
                $pilihandisplay = explode(',', $tipeform[2]);
                for ($i = 0; $i < count($pilihanvalue); $i++) {
                    $selected = ($urasi['value'] == $pilihanvalue[$i]) ? 'selected' : '';
                    $admincontent .= '<option value="' . $pilihanvalue[$i] . '" ' . $selected . '>' . $pilihandisplay[$i] . '</option>' . "\r\n";
                }
                $admincontent .= "
                                    </select>\r\n
                                </div>\r\n
                            </div>\r\n";

                $admincontent .= '</select></td></tr>' . "\r\n";
                break;
            case 'favicon':
                $admincontent .= "<div class=\"control-group\">\r\n
									<label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
									<div class=\"controls\">\r\n
										<div data-provides=\"fileupload\" class=\"fileupload fileupload-new\"><input type=\"hidden\"  name=\"" . $urasi["name"] . "\" />
											<div class=\"input-append\">\r\n
												<div class=\"uneditable-input span2\">\r\n
													<i class=\"icon-file fileupload-exists\"></i><span class=\"fileupload-preview\"></span>\r\n
												</div>\r\n
												<span class=\"btn btn-file\"><span class=\"fileupload-new\">Cari File</span><span class=\"fileupload-exists\">Change</span>\r\n
												<img src=\"" . $cfg_img_url . "/favicon." . $urasi["value"] . "\" /><input type=\"file\" name=\"" . $urasi["name"] . "\" />
												</span><a data-dismiss=\"fileupload\" class=\"btn fileupload-exists\" href=\"#\">Remove</a>\r\n
											</div>\r\n
										</div>\r\n
									</div>\r\n
								</div>\r\n";
                break;
            case 'color':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <input type=\"text\" name=\"" . $urasi['name'] . "\" value=\"" . $urasi['value'] . "\" 
                                                size=\"7\" max_length=\"7\" class=\"colors\" />\r\n
                                    </div>\r\n
                            </div>\r\n";
                break;
            case 'none':
                break;
        }
        $currentmodule = $urasi['modul'];
    }
    $admincontent .= "<div class=\"control-group\">\r\n
                            <div class=\"controls\">\r\n
                                    <input class=\"btn btn-danger\" type=\"submit\" name=\"submit\" value=\"" . _SAVE . "\" />\r\n
                            </div>\r\n
                    </div>\r\n";
    $admincontent .= "</form>";
}

if ($action == "") {
    $config = 'site';
    $sql = "SELECT name, value, namatampilan, modul, tipeform, maxlength FROM config "
            . "where modul IN (SELECT nama FROM module) AND name NOT IN ($inclusion)";
   // if (!$pakaicart) {
        //$sql.=" AND modul<>'cart' ";
		$sql .= " AND modul NOT IN ($hiddenmodul) ";
    //}
	$sql.=" AND name NOT IN($hiddenvar)";
    $sql.="  ORDER BY modul";
    $result = $mysql->query($sql);
    $currentmodule = '';
    //start buat tab
    $action = fiestolaundry($_POST['action'], 20);

    $sql_config = "SELECT judul from module WHERE nama IN ( SELECT distinct modul FROM config ";
 //   if (!$pakaicart) {
        //$sql_config.=" where modul<>'cart' ";
		$sql_config .= " WHERE modul NOT IN ($hiddenmodul) ";
  //  }
    $sql_config.=")";
    $sql_config.=" GROUP BY judul ORDER BY nama ";

    $admincontent .="<ul class=\"nav nav-tabs\" id=\"myTab2\">\r\n";
    $res_config = $mysql->query($sql_config);
    $i = 0;
    while ($d_cfg = $mysql->fetch_array($res_config)) {
        $i++;
        if ($i == 1) {
            $active = "active";
        } else {
            $active = "";
        }
        $admincontent .="<li class=\"$active\"><a href=\"#tab$i\">" . $d_cfg['judul'] . "</a></li>\r\n";
    }
    $admincontent .= "</ul>\r\n";
    //end buat tab

    $index = 0;
    $admincontent .= "<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\" enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"action\" value=\"setsite\">
			<input type=\"hidden\" name=\"config\" value=\"site\">";
    //start tab content
    $admincontent .= "<div class=\"tab-content\">\r\n";
    while ($urasi = $mysql->fetch_array($result)) {
        if ($currentmodule != $urasi['modul']) {
            $index++;
            if ($index == 1) {
                $admincontent .= "<div class=\"tab-pane active\" id=\"tab$index\">\r\n";
            } else {
                $admincontent .= "</div>\r\n";
                $admincontent .= "<div class=\"tab-pane\" id=\"tab$index\">\r\n";
            }
        }
        $tipeform = explode(';', $urasi['tipeform']);


        switch ($tipeform[0]) {
            case 'text':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <input type=\"text\" name=\"" . $urasi['name'] . "\" value=\"" . $urasi['value'] . "\" 
                                                size=\"" . $tipeform[1] . "\" max_length=\"" . $urasi['maxleength'] . "\"/>\r\n
                                    </div>\r\n
                            </div>\r\n";
                break;
            case 'textarea':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <textarea name=\"" . $urasi["name"] . "\" cols=\"" . $tipeform[1] . "\" rows=\"" . $tipeform[2] . "\" class=\"notiny span12 " . $tipeform[3] . "\">" . $urasi["value"] . "</textarea>
                                    </div>\r\n
                            </div>\r\n";
                break;
            case 'combo':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                    <select name=\"" . $urasi['name'] . "\">\r\n";
                $pilihanvalue = explode(',', $tipeform[1]);
                $pilihandisplay = explode(',', $tipeform[2]);
                for ($i = 0; $i < count($pilihanvalue); $i++) {
                    $selected = ($urasi['value'] == $pilihanvalue[$i]) ? 'selected' : '';
                    $admincontent .= '<option value="' . $pilihanvalue[$i] . '" ' . $selected . '>' . $pilihandisplay[$i] . '</option>' . "\r\n";
                }
                $admincontent .= "
                                    </select>\r\n
                                </div>\r\n
                            </div>\r\n";

                $admincontent .= '</select></td></tr>' . "\r\n";
                break;
            case 'favicon':
                $admincontent .= "<div class=\"control-group\">\r\n
									<label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
									<div class=\"controls\">\r\n
										<div data-provides=\"fileupload\" class=\"fileupload fileupload-new\"><input type=\"hidden\"  name=\"" . $urasi["name"] . "\" />
											<img src=\"" . $cfg_img_url . "/favicon." . $urasi["value"] . "\" /><div class=\"input-append\">\r\n
												<div class=\"uneditable-input span2\">\r\n
													<i class=\"icon-file fileupload-exists\"></i><span class=\"fileupload-preview\"></span>\r\n
												</div>\r\n
												<span class=\"btn btn-file\"><span class=\"fileupload-new\">Cari File</span><span class=\"fileupload-exists\">Change</span>\r\n
												<input type=\"file\" name=\"" . $urasi["name"] . "\" />
												</span><a data-dismiss=\"fileupload\" class=\"btn fileupload-exists\" href=\"#\">Remove</a>\r\n
											</div>\r\n
										</div>\r\n
									</div>\r\n
								</div>\r\n";
                break;
            case 'color':
                $admincontent .= "<div class=\"control-group\">\r\n
                                    <label class=\"control-label\">" . $urasi['namatampilan'] . "</label>\r\n
                                    <div class=\"controls\">\r\n
                                            <input type=\"text\" name=\"" . $urasi['name'] . "\" value=\"" . $urasi['value'] . "\" 
                                                size=\"7\" max_length=\"7\" class=\"colors\" />\r\n
                                    </div>\r\n
                            </div>\r\n";
                break;
            case 'none':
                break;
        }
        $currentmodule = $urasi['modul'];
    }
    $admincontent .= "</div>\r\n";
    //end tab-content
    $admincontent .= "<div class=\"control-group\">\r\n
                            <div class=\"controls\">\r\n
                                    <input class=\"btn btn-danger\" type=\"submit\" name=\"submit\" value=\"" . _SAVE . "\" />\r\n
                            </div>\r\n
                    </div>\r\n";
    $admincontent .= "</form>";
}

if ($action == "dolaunch") 
{
	//hapus foto 

	if($_POST['deldummy']==1)
	{
	$query_dummy=$mysql->query("select filename,thumb FROM catalogdata WHERE dummy=1");
	if($query_dummy and $mysql->num_rows($query_dummy))
	{
		while (list($filename,$r_thumb) = $mysql->fetch_row($query_dummy)) 
		{
			$thumbutama=$cfg_thumb_path."/$filename";
			$fullutama=$cfg_thumb_path."/$filename";
			if(file_exists($thumbutama)){@unlink($thumbutama);}
			if(file_exists($fullutama)){@unlink($fullutama);}
			////////////hapus gambar pilihan yang lain
			$r_thumb=explode(":",$r_thumb);
			if(count($r_thumb)>0)
			{
				foreach($r_thumb as  $v)
				{
					$thumbpilihan=$cfg_thumb_path."/$v";
					$fullpilihan=$cfg_thumb_path."/$v";
					if(file_exists($thumbpilihan)){@unlink($thumbpilihan);}
					if(file_exists($fullpilihan)){@unlink($fullpilihan);}
				}
			}
			
		}
	}
	//hapus catalog
	$q=$mysql->query("DELETE FROM catalogdata WHERE dummy=1");
	
	//hapus catalog_cat yang tidak punya anak selain dummy
	$sql="SELECT id from catalogcat WHERE parent=0";
	$query_cat=$mysql->query($sql);
	if($query_cat and $mysql->num_rows($query_cat))
	{
		while (list($id) = $mysql->fetch_row($query_cat)) 
		{
			$r_parent=array();
			$r_parent[]=$id;
			loop_child($id);
			if(count($r_parent)>0)
			{
				$join_cat=join(",",$r_parent);
				$q=$mysql->query("DELETE FROM catalogcat WHERE id IN ($join_cat) AND id not in (SELECT cat_id FROM catalogdata WHERE dummy<>1) ");
				
			}
			
		}
	}
	}
	
	if($_POST['hidesimplestep']==1)
	{
	
	$q=$mysql->query("UPDATE config set value=1 WHERE name='hidesimplestep' ");
	}
	else
	{
	$q=$mysql->query("UPDATE config set value=0 WHERE name='hidesimplestep' ");
	}
	
	header("location:$cfg_wolawola_url/?p=xwd&action=upgrade&id=$domainid");
	
}
if ($action == "launch") 
{
//> checkbox del semua dummy data (ati2 kategori dummmy diisi data baru), checkbox hide langkah, lalu upgrade
$adminh1=_LAUNCH;
$admincontent .= "<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\" enctype=\"multipart/form-data\">";
$admincontent .= "<input type=\"hidden\" value=\"dolaunch\" name=\"action\" />\r\n";
$admincontent .= "<div class=\"control-group\">\r\n
					<label class=\"control-label\">" ._DELETEDUMMY."</label>\r\n
					<div class=\"controls\">\r\n
							<input type=\"checkbox\" checked=\"checked\" value=\"1\" name=\"deldummy\" />\r\n
					</div>\r\n
			</div>\r\n";
$admincontent .= "<div class=\"control-group\">\r\n
					<label class=\"control-label\">" ._HIDESTEPNAVIGATION."</label>\r\n
					<div class=\"controls\">\r\n
							<input type=\"checkbox\" name=\"hidesimplestep\" value=\"1\" />\r\n
					</div>\r\n
			</div>\r\n";
			
$admincontent .= "<div class=\"control-group\">\r\n
                            <div class=\"controls\">\r\n
                                    <input class=\"btn btn-danger\" type=\"submit\" name=\"submit\" value=\"" . _UPGRADE . "\" />\r\n
                            </div>\r\n
                    </div>\r\n";							
$admincontent .="</form>";
}
?>
