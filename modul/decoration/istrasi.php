<?php

if (!$isloadfromindex) {
    include ("../../kelola/urasi.php");
    include ("../../kelola/fungsi.php");
    include ("../../kelola/lang/$lang/definisi.php");
    pesan(_ERROR, _NORIGHT);
}

$action = fiestolaundry($_POST['action'], 20);
$headertype = fiestolaundry($_POST['headertype'], 1);

if ($action == "setheader") {
	$valid=true;
    $action = "";
    switch ($headertype) {
        case 0:
            $sql = "SELECT basename, extension, maxdimension, maxfilesize, url, value FROM decoration WHERE type='defaultheader' OR type='strengtpoint'ORDER BY id";
            $result = $mysql->query($sql);
            while (list($basename, $oldextension, $maxdimension, $maxfilesize, $url, $value) = $mysql->fetch_row($result)) {
				$url_basename = 'url_'.$basename;
				$value_basename = 'value_'.$basename;
				
                if ($_FILES[$basename]['error'] != UPLOAD_ERR_NO_FILE) {
					//jika kolom file diisi
				    $hasilupload = fiestoupload($basename, "$cfg_decoration_path", $basename, $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png,ico,svg");
					
                    if ($hasilupload !=_SUCCESS) {
                        $action = createmessage($hasilupload, _ERROR, "error", "");
						$valid=false;
                    } else {
					
						//ambil informasi extension
						$temp = explode(".", $_FILES[$basename]['name']);
						$extension = $temp[count($temp) - 1];
						$extension = strtolower($extension);
						//resize gambar asli sesuai yang diizinkan
						list($aturan, $maxwidth, $maxheight) = explode(';', $maxdimension);
						switch ($aturan) {
							case 'fixed':
								if(file_exists("$cfg_decoration_path/$basename.$extension")) {
									$hasilresize = fiestoresize("$cfg_decoration_path/$basename.$extension", "$cfg_decoration_path/$basename.$extension", 'f', $maxwidth, $maxheight);
									if ($hasilresize != _SUCCESS) {
										$action = createmessage($hasilresize, _ERROR, "error", "");
										$valid=false;
									}
								}
								break;
							case 'max':
							if(file_exists("$cfg_decoration_path/$basename.$extension")) {
								list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_decoration_path/$basename.$extension");
									if ($filewidth > $maxwidth || $fileheight > $maxheight) {
									$hasilresize = fiestoresize("$cfg_decoration_path/$basename.$extension", "$cfg_decoration_path/$basename.$extension", 'b', $maxwidth, $maxheight);
									if ($hasilresize != _SUCCESS) {
										$action = createmessage($hasilresize, _ERROR, "error", "");
										$valid=false;
									}
								}
							}	
							break;
							//case 'noresize':	sekedar pengingat bahwa kalau tidak perlu resize kasih tanda ini
							//	break
						}

						//jika ekstension tidak sama dengan file terdahulu, del file lama
						if ($extension != $oldextension) {
							@unlink("$cfg_decoration_path/$basename.$oldextension");
						}

						
						// echo "UPDATE decoration SET url='{$_POST[$url_basename]}', value='{$_POST[$value_basename]}' WHERE basename='$basename'<br />";
						// if ($_POST[$basename] != $url) {
							if (!$mysql->query("UPDATE decoration SET url='{$_POST[$url_basename]}', value='{$_POST[$value_basename]}' WHERE basename='$basename'")) {
								$action = createmessage(_DBERROR, _ERROR, "error", "");
								$valid=false;
							}
						// }
					
						if (!$mysql->query("UPDATE decoration SET extension='$extension' WHERE basename='$basename'")) {
							$action = createmessage(_DBERROR, _ERROR, "error", "");
							$valid=false;
						}
					}
                } else {
					// if ($_POST[$url_basename] != $url) {
						// echo "UPDATE decoration SET url='{$_POST[$url_basename]}', value='{$_POST[$value_basename]}' WHERE basename='$basename'<br />";
						if (!$mysql->query("UPDATE decoration SET url='{$_POST[$url_basename]}', value='{$_POST[$value_basename]}' WHERE basename='$basename'")) {
							$action = createmessage(_DBERROR, _ERROR, "error", "");
							$valid=false;
						}
					// }
				}
            }
            break;
        case 1:
            if ($_FILES['customheader']['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
                $hasilupload = fiestoupload('customheader', "$cfg_decoration_path", 'customheader', 1000000, 'swf');
                if ($hasilupload != _SUCCESS) {
                    $action = createmessage($hasilupload, _ERROR, "error", "");
                }
            }
            break;
    }
	
	if($valid) {
		$sql = "SELECT basename, extension, maxdimension, maxfilesize, url FROM decoration WHERE type!='defaultheader' AND type!='strengtpoint' ORDER BY type, id";
		$result = $mysql->query($sql);
		while (list($basename, $oldextension, $maxdimension, $maxfilesize, $url) = $mysql->fetch_row($result)) {
			if ($_FILES[$basename]['error'] != UPLOAD_ERR_NO_FILE) { //jika kolom file diisi
				$hasilupload = fiestoupload($basename, "$cfg_decoration_path", $basename, $maxfilesize, $allowedtypes = "gif,jpg,jpeg,png,ico,svg");
				if ($hasilupload != _SUCCESS) {
					$action = createmessage($hasilupload, _ERROR, "error", "");
				}
				//ambil informasi extension
				$temp = explode(".", $_FILES[$basename]['name']);
				$extension = $temp[count($temp) - 1];
				//resize gambar asli sesuai yang diizinkan
				list($aturan, $maxwidth, $maxheight) = explode(';', $maxdimension);
				switch ($aturan) {
					case 'fixed':
						$hasilresize = fiestoresize("$cfg_decoration_path/$basename.$extension", "$cfg_decoration_path/$basename.$extension", 'f', $maxwidth, $maxheight);
						if ($hasilresize != _SUCCESS) {
							$action = createmessage($hasilresize, _ERROR, "error", "");
							$valid=false;
						}
						break;
					case 'max':
						list($filewidth, $fileheight, $filetype, $fileattr) = getimagesize("$cfg_decoration_path/$basename.$extension");
						if ($filewidth > $maxwidth || $fileheight > $maxheight) {
							$hasilresize = fiestoresize("$cfg_decoration_path/$basename.$extension", "$cfg_decoration_path/$basename.$extension", 'b', $maxwidth, $maxheight);
							if ($hasilresize != _SUCCESS) {
								$action = createmessage($hasilresize, _ERROR, "error", "");
								$valid=false;
							}
						}
						break;
					//case 'noresize':	sekedar pengingat bahwa kalau tidak perlu resize kasih tanda ini
					//	break
				}

				//jika ekstension tidak sama dengan file terdahulu, del file lama
				if ($extension != $oldextension) {
					unlink("$cfg_decoration_path/$basename.$oldextension");
				}

				if (!$mysql->query("UPDATE decoration SET extension='$extension' WHERE basename='$basename'")) {
					$action = createmessage(_DBERROR, _ERROR, "error", "");
					$valid=false;
				}
			}
		}
    
	}
	
	if ($valid) {
        $action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
    }
}

if ($action == "") {
    $admincontent = "<div class='kelola_dekorasi row-fluid'>";
    switch ($config_site_headertype) {
        case 0:
            $sql = "SELECT basename, extension, namatampilan, maxdimension, maxfilesize, url, value FROM decoration WHERE type='defaultheader' OR type='strengtpoint'ORDER BY id";
            $result = $mysql->query($sql);
            $admincontent .= "<form name=\"formheader\" method=\"POST\" action=\"?p=decoration\" enctype=\"multipart/form-data\">\r\n";
            $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"setheader\" />\r\n";
            $admincontent .= "<input type=\"hidden\" name=\"headertype\" value=\"0\" />\r\n";
            while (list($basename, $extension, $namatampilan, $maxdimension, $maxfilesize, $url, $textvalue) = $mysql->fetch_row($result)) {
                list($max, $mwidth, $mheight) = explode(";", $maxdimension);
                $dimension = "{$mwidth}px x {$mheight}px";
                $admincontent .= "<div class='span5'>
				<div class='content-widgets orange'>
				<div class='pricing-head bondi-blue'>
				<h3>$namatampilan</h3>
				</div>
				<div class=\"pack-details dekorasi1\">
				<img src=\"$cfg_decoration_url/$basename.$extension?r=$random\" />
				<p>$dimension</p>\r\n";
				
				if ($basename != 'headerlogo') {
					$linkurl = "<input type=\"text\" name=\"url_$basename\" class=\"span10\" value=\"$url\" Placeholder=\""._URL."\">";
					$value = "<input type=\"text\" name=\"value_$basename\" class=\"span10\" value=\"$textvalue\" Placeholder=\""._TEXT."\">";
				} else {
					$linkurl = "";
					$value = "";
				}
				
                $admincontent .= "<div class=\"control-group\">
                                        <div class=\"controls\">
											<div data-provides=\"fileupload\" class=\"fileupload fileupload-new\"><input type=\"hidden\" name=\"$basename\" MAX_FILE_SIZE=\"$maxfilesize\" />
												<div class=\"input-append\">
													<div class=\"uneditable-input span2\">
														<i class=\"icon-file fileupload-exists\"></i><span class=\"fileupload-preview\"></span>
													</div>
													<span class=\"btn btn-file\"><span class=\"fileupload-new\">Cari File</span><span class=\"fileupload-exists\">Change</span>
													<input type=\"file\" name=\"$basename\" MAX_FILE_SIZE=\"$maxfilesize\" />
													</span><a data-dismiss=\"fileupload\" class=\"btn fileupload-exists\" href=\"#\">Remove</a>
												</div>
											</div>
                                        </div>
										<input type=\"hidden\" name=\"$basename\">
										$linkurl
										$value
										$inputlinkx
                                </div>
				</div>
				</div>
				</div>\r\n";
            }
            break;
        case 1:
            $admincontent = "<form name=\"formheader\" method=\"POST\" action=\"?p=decoration\" enctype=\"multipart/form-data\">\r\n";
            $admincontent .= "<input type=\"hidden\" name=\"action\" value=\"setheader\" />\r\n";
            $admincontent .= "<input type=\"hidden\" name=\"headertype\" value=\"1\" />\r\n";
            $admincontent .= "<p>" . _UPLOADSWF . ": <input type=\"file\" name=\"customheader\" MAX_FILE_SIZE=\"1000000\" /></p>\r\n";
            break;
    }
    $sql = "SELECT basename, extension, namatampilan, maxdimension, maxfilesize FROM decoration WHERE type!='defaultheader' AND type!='strengtpoint' ORDER BY type, id";
    $result = $mysql->query($sql);
    while (list($basename, $extension, $namatampilan, $maxdimension, $maxfilesize) = $mysql->fetch_row($result)) {
        list($max, $mwidth, $mheight) = explode(";", $maxdimension);
        $dimension = "size {$mwidth}px X {$mheight}px";
        $admincontent .= "<p><img src=\"$cfg_decoration_url/$basename.$extension?r=$random\" /><br />\r\n";
        $admincontent .= "$namatampilan ($dimension): <input type=\"file\" name=\"$basename\" MAX_FILE_SIZE=\"$maxfilesize\" /></p>\r\n";
    }
    $admincontent .= "<p class=\"simpan\"><input type=\"submit\" class=\"buton\" value=\"" . _SAVE . "\" /></p>\r\n";
    $admincontent .= "</form>\r\n";
    $admincontent .= "</div>\r\n";
}
?>