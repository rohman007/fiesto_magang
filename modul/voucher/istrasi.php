<?php
if (!$isloadfromindex) {
	include ("../../kelola/urasi.php");
	include ("../../kelola/fungsi.php");
	include ("../../kelola/lang/$lang/definisi.php");
	pesan(_ERROR,_NORIGHT);
}

$keyword=fiestolaundry($_GET['keyword'],100);
$screen=fiestolaundry($_GET['screen'],11);
$pid=fiestolaundry($_REQUEST['pid'],11);

$dtglmulai = fiestolaundry($_POST['dtglmulai'],2);
$mtglmulai = fiestolaundry($_POST['mtglmulai'],2);
$ytglmulai = fiestolaundry($_POST['ytglmulai'],4);
$dtglsampai = fiestolaundry($_POST['dtglsampai'],2);
$mtglsampai = fiestolaundry($_POST['mtglsampai'],2);
$ytglsampai = fiestolaundry($_POST['ytglsampai'],4);
$kodevoucher = fiestolaundry($_POST['kodevoucher'],50);
$tipe_diskon = fiestolaundry($_POST['tipe_diskon'],11);
$nominaldiskon = fiestolaundry($_POST['nominaldiskon'],13);
$nominalbelanja = fiestolaundry($_POST['nominalbelanja'],13);
$hadiah = fiestolaundry($_POST['hadiah'],0,TRUE);
$action = fiestolaundry($_REQUEST['action'],10);
$hariini = getdate();
$jumlahpakai = fiestolaundry($_POST['jumlahpakai'], 11);
$user_id = fiestolaundry($_POST['user_id'], 11);
$is_onetime = fiestolaundry($_POST['is_onetime'], 1);

$modulename = $_GET['p'];
if ($_POST['back']) {
	header("Location: ?p=$modulename");
}

if ($action=='search') {
    $admintitle = _SEARCHRESULTS;
	$sql = "SELECT id FROM voucher WHERE status=1 AND kodevoucher LIKE '%$keyword%' OR nominaldiskon LIKE '%$keyword%' OR hadiah LIKE '%$keyword%'";
	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$pages = ceil($total_records/$max_page_list);

	if ($mysql->num_rows($result) > 0) {
		$start = $screen * $max_page_list;
		$sql = "SELECT id,kodevoucher,tipe,nominaldiskon,nominalbelanja FROM voucher WHERE status=1 AND kodevoucher LIKE '%$keyword%' OR nominaldiskon LIKE '%$keyword%' OR hadiah LIKE '%$keyword%' LIMIT $start, $max_page_list";
		$result = $mysql->query($sql);

		if ($pages>1) $adminpagination = pagination($namamodul,$screen);
		$admincontent .= "<table class=\"list\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">\n";
		$admincontent .= "<tr><th>"._VOUCERCODE."</th><th>"._TIPEVOUCHER."</th><th>"._TGLMULAI."</th><th>"._TGLSELESAI."</th><th>"._NOMINAL."</th><th>"._NOMINALBELANJA."</th>";
		$admincontent .= "<th>"._EDIT."</th><th>"._DEL."</th></tr>\n";
		while (list($id,$tglstart,$tglend,$kodevoucher, $tipe, $nominaldiskon, $nominalbelanja) = $mysql->fetch_row($result)) {
			if ($tipe == 1) {
				$tipe_nominal = number_format($nominaldiskon, 0, ',', '.');
			} else if ($tipe == 2) {
				$tipe_nominal = $nominaldiskon . '%';
			}
			$admincontent .= "<tr>\n";
			$admincontent .= "<td>$kodevoucher</td>";
			$admincontent .= "<td>".$discount_type[$tipe]."</td>";
			$admincontent .= "<td>".tglformat($tglstart)."</td>";
			$admincontent .= "<td>".tglformat($tglend)."</td>";
			$admincontent .= "<td align=\"right\">".number_format($nominalbelanja, 0, ',', '.')."</td>";
			$admincontent .= "<td align=\"right\">$tipe_nominal</td>";			
			$admincontent .= "<td align=\"center\"><a href=\"?p=voucher&action=modify&pid=$id\">";
			$admincontent .= "<img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a></td>\n";
			$admincontent .= "<td align=\"center\"><a href=\"?p=voucher&action=remove&pid=$id\">";
			$admincontent .= "<img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
			$admincontent .= "</tr>\n";
		}
		$admincontent .= "</table>";
	} else {
		$admincontent .= _NOSEARCHRESULTS;
	}
}

if ($action == "tambahdata") {
	$sukses = true;
	$tipe_diskon = checkrequired($tipe_diskon, _TIPEDISKON);
	$kodevoucher = checkrequired($kodevoucher, _NOMINALCAPAI);
	$nominaldiskon = checkrequired($nominaldiskon, _NOMINAL);
	// $nominalbelanja = checkrequired($nominalbelanja, _NOMINALBELANJA);
	
	if ($tipe_diskon == 0) {
		// pesan(_ERROR,_INVALIDTIPE);
		$action = createmessage(_INVALIDTIPE, _SUCCESS, "error", "add");
		$sukses=false;	
	}
	if (!(bool)$is_onetime) {
		if (!checkdate ($mtglmulai,$dtglmulai,$ytglmulai) || !checkdate ($mtglsampai,$dtglsampai,$ytglsampai)) {
			// pesan(_ERROR,_INVALIDDATE);
			$action = createmessage(_INVALIDDATE, _SUCCESS, "error", "add");
			$sukses=false;
		}
		
		$tglmulai = "$ytglmulai-$mtglmulai-$dtglmulai";
		$tglsampai = "$ytglsampai-$mtglsampai-$dtglsampai";
		if ($ytgldipakai == 0 || $mtgldipakai == 0 || $dtgldipakai == 0) {
			$tgldipakai = "0000-00-00";
		} else {
			$tgldipakai = "$ytgldipakai-$mtgldipakai-$dtgldipakai";
		}
	}
	
	// $sqlrange = "SELECT * FROM voucher WHERE ((tglstart BETWEEN '$tglmulai' AND '$tglsampai')  or (tglend BETWEEN '$tglmulai' AND '$tglsampai'))";

	// $resultrange = $mysql->query($sqlrange);
	// $periodetabrakan=$mysql->num_rows($resultrange);
	// // echo "$sqlrange<br />$tcode != $kodekupon<br />$jenis";
	
	// if($periodetabrakan)
	// {
		// pesan(_ERROR,_DATEINRANGE,"javascript:history.back()");
		// $sukses=false;
	// }
	
	$sqlv = $mysql->query("SELECT id FROM voucher WHERE kodevoucher='$kodevoucher'");
	if ($mysql->num_rows($sqlv) > 0) {
		// pesan(_ERROR,_VOUCHEREXISTS,"javascript:history.back()");
		$action = createmessage(_VOUCHEREXISTS, _SUCCESS, "error", "add");
		$sukses=false;
	}

	if($sukses) {	
		if ((bool)$is_onetime) {
			$sql = "INSERT INTO voucher (kodevoucher, tipe, nominaldiskon, nominalbelanja, is_onetime, status) VALUES ('$kodevoucher', '$tipe_diskon', '$nominaldiskon', '$nominalbelanja', '$is_onetime', 1)";
		} else {
			$sql = "INSERT INTO voucher (kodevoucher, tipe, nominaldiskon, tglstart, tglend, nominalbelanja, is_onetime, status) VALUES ('$kodevoucher', '$tipe_diskon', '$nominaldiskon', '$tglmulai', '$tglsampai', '$nominalbelanja', '$is_onetime', 1)";
		}
		$result = $mysql->query($sql);
		if ($result) {
			// pesan(_SUCCESS,_DBSUCCESS,"?p=voucher&r=$random");
			$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
		} else {
			// pesan(_ERROR,_DBERROR);
			$action = createmessage(_DBERROR, _SUCCESS, "error", "add");
		}
	}
}

if ($action == "add") {
    $admintitle = _ADDDISKON;
	$admincontent .= imgPopup();
	
	$admincontent .= '
	<form class="form-horizontal" method="POST" action="'.$thisfile.'">
		<input type="hidden" name="action" value="tambahdata">
		<input type="hidden" name="pid" value="'.$pid.'">';
		
		$admincontent .= '
			<div class="control-group">
				<label class="control-label">' . _TIPEDISKON . '</label>
				<div class="controls">
					<select name="tipe_diskon" id="tipe_diskon">';
					$admincontent .= "<option value=\"0\">-- "._PILIH." --</option>";
					for($i=1;$i<=count($discount_type);$i++) {
						$selected = ($tipe == $i) ? "selected='selected'" : "";
						$admincontent .= "<option value=\"$i\" $selected>$discount_type[$i]</option>";
					}
		$admincontent .= '</select>';
		$admincontent .= '
				</div>
			</div>';
			
		$admincontent .= '
		<div class="control-group">
			<label class="control-label">' . _NOMINALCAPAI . '</label>
			<div class="controls"><input type="text" name="kodevoucher" value="' . $kodevoucher . '" size="40"></div>
		</div>';
		$admincontent .= '
			<div id="start-date" class="control-group">
				<label class="control-label">' . _TGLMULAI . '</label>
				<div class="controls">
					<select name="dtglmulai">';
					$admincontent .= "<option value=\"0\">-- "._PILIH." --</option>";
					// $ttglmulai = explode("-",$tglmulai);
					// $ttglsampai = explode("-",$tglsampai);
					// $ttgldipakai = explode("-",$tgldipakai);
					$ymulai = $hariini[year] - 1;
					$yakhir = $hariini[year] + 3;
					for($i=1;$i<=31;$i++) {
						$selected = ($i==$hariini[mday]) ? 'selected' : '';
						$admincontent .= "<option value=\"$i\" $selected>$i</option>";
					}
					$admincontent .= '</select>';
					$admincontent .= '<select name="mtglmulai">';
					for($i=1;$i<=12;$i++) {
						$j=$i-1;
						$selected = ($i==$hariini[mon]) ? 'selected' : '';
						$admincontent .= "<option value=\"$i\" $selected>$namabulan[$j]</option>";
					}
					$admincontent .= '</select>';
					$admincontent .= '<select name="ytglmulai">';
					for($i=$ymulai;$i<=$yakhir;$i++) {
						$selected = ($i==$hariini[year]) ? 'selected' : '';
						$admincontent .= "<option value=\"$i\" $selected>$i</option>";
					}
					$admincontent .= '</select>';
		$admincontent .= '
				</div>
			</div>';
		$admincontent .= '
			<div id="end-date" class="control-group">
				<label class="control-label">' . _TGLSELESAI . '</label>
				<div class="controls">
					<select name="dtglsampai">';
					$admincontent .= "<option value=\"0\">-- "._PILIH." --</option>";
					for($i=1;$i<=31;$i++) {
						$selected = ($i==$hariini[mday]) ? 'selected' : '';
						$admincontent .= "<option value=\"$i\" $selected>$i</option>";
					}
					$admincontent .= '</select>';
					$admincontent .= '<select name="mtglsampai">';
					for($i=1;$i<=12;$i++) {
						$j=$i-1;
						$selected = ($i==$hariini[mon]) ? 'selected' : '';
						$admincontent .= "<option value=\"$i\" $selected>$namabulan[$j]</option>";
					}
					$admincontent .= '</select>';
					$admincontent .= '<select name="ytglsampai">';
					for($i=$ymulai;$i<=$yakhir;$i++) {
						$selected = ($i==$hariini[year]) ? 'selected' : '';
						$admincontent .= "<option value=\"$i\" $selected>$i</option>";
					}
					$admincontent .= '</select>';
		$admincontent .= '
				</div>
			</div>';
		$admincontent .= '
		<div class="control-group">
			<label class="control-label">' . _NOMINAL . '</label>
			<div class="controls"><input type="text" name="nominaldiskon" value="' . $nominaldiskon . '" size="40"></div>
		</div>';
		$admincontent .= '
		<div class="control-group">
			<label class="control-label">' . _VOUCHERSTATUS . '</label>
			<div class="controls"><input type="checkbox" name="is_onetime" value="1"></div>
		</div>';

		$admincontent .='	
		<div class="control-group">
			<div class="controls">
				<input type="submit" name="submit" class="buton"  value="' . _SAVE . '">
				<input type="submit" name="back" class="buton" onclick="javascript:history.go(-1)" value="' . _BACK . '">
			</div>
		</div>';
		
	$admincontent .= '</form>';
		
}

if ($action == "ubahdata") {
	$sukses=true;
	if ($tipe_diskon == 0) {
		// pesan(_ERROR,_INVALIDTIPE);
		$action = createmessage(_INVALIDTIPE, _ERROR, "error", "modify");
		$sukses=false;	
	}
	if (!(bool)$is_onetime) {
		if (!checkdate ($mtglmulai,$dtglmulai,$ytglmulai) || !checkdate($mtglsampai,$dtglsampai,$ytglsampai)) {
			// pesan(_ERROR,_INVALIDDATE);
			$action = createmessage(_INVALIDDATE, _ERROR, "error", "modify");
			$sukses=false;
		}
		$tglmulai = "$ytglmulai-$mtglmulai-$dtglmulai";
		$tglsampai = "$ytglsampai-$mtglsampai-$dtglsampai";
		if(strtotime($tglmulai)>strtotime($tglsampai))
		{
			// pesan(_ERROR,_INVALIDDATE);
			$action = createmessage(_INVALIDDATE, _ERROR, "error", "modify");
			$sukses=false;
		}
		
		if ($ytgldipakai == 0 || $mtgldipakai == 0 || $dtgldipakai == 0) {
			$tgldipakai = "0000-00-00";
		} else {
			$tgldipakai = "$ytgldipakai-$mtgldipakai-$dtgldipakai";
		}
	}
	// $sqlrange = "SELECT * FROM voucher WHERE ((tglstart BETWEEN '$tglmulai' AND '$tglsampai')  or (tglend BETWEEN '$tglmulai' AND '$tglsampai')) AND id<>'$pid'";

	// $resultrange = $mysql->query($sqlrange);
	// $periodetabrakan=$mysql->num_rows($resultrange);
	
	// if($periodetabrakan)
	// {
		// pesan(_ERROR,_DATEINRANGE,"javascript:history.back()");
		// $sukses=false;
	// }
	$kodevoucher = checkrequired($kodevoucher, _NOMINALCAPAI);
	$nominaldiskon = checkrequired($nominaldiskon, _NOMINAL);
	// $nominalbelanja = checkrequired($nominalbelanja, _NOMINALBELANJA);
	
	if ($sukses) {
		
		if ((bool)$is_onetime) {
			$sql = "UPDATE voucher SET kodevoucher='$kodevoucher', tipe='$tipe_diskon', nominaldiskon='$nominaldiskon', nominalbelanja='$nominalbelanja', is_onetime='$is_onetime' WHERE id='$pid'";
		} else {
			$sql = "UPDATE voucher SET kodevoucher='$kodevoucher', tipe='$tipe_diskon', nominaldiskon='$nominaldiskon', tglstart='$tglmulai', tglend='$tglsampai', nominalbelanja='$nominalbelanja', is_onetime='$is_onetime' WHERE id='$pid'";
		}
		/* if ($tipe_diskon == 1) {
			$sql = "UPDATE voucher SET kodevoucher='$kodevoucher', tipe='$tipe_diskon', nominaldiskon='$nominaldiskon', hadiah='', tglstart='$tglmulai', tglend='$tglsampai', jumlahpakai='$jumlahpakai' WHERE id='$pid'";
		} else {
			$sql = "UPDATE voucher SET kodevoucher='$kodevoucher', tipe='$tipe_diskon', nominaldiskon='', hadiah='$hadiah', tglstart='$tglmulai', tglend='$tglsampai', jumlahpakai='$jumlahpakai' WHERE id='$pid'";
		} */
		
		$result = $mysql->query($sql);
		if ($result) {
			$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
		} else {
			$action = createmessage(_DBERROR, _ERROR, "error", "modify");
		}
	}
}

// MODIFY PAGE
if ($action == "modify") {
    $admintitle = _EDITDISKON;
	$sql = "SELECT id, kodevoucher, tipe, nominaldiskon, hadiah, tglstart, tglend, nominalbelanja, is_onetime FROM voucher WHERE id='$pid'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) == "0") {
		pesan(_ERROR,_NODISKON);
	} else {
		list($id, $kodevoucher, $tipe, $nominaldiskon, $hadiah, $tglmulai, $tglsampai, $nominalbelanja, $is_onetime) = $mysql->fetch_row($result);
		$publishstatus = ((bool)$is_onetime) ? 'checked' : '';
		//$catselect = adminselectcategories('linkcat',$cat_id);
		$admincontent .= imgPopup();
		$admincontent .= '
		<form class="form-horizontal" method="POST" action="'.$thisfile.'">
			<input type="hidden" name="action" value="ubahdata">
			<input type="hidden" name="pid" value="'.$pid.'">';
			
			$admincontent .= '
				<div class="control-group">
					<label class="control-label">' . _TIPEDISKON . '</label>
					<div class="controls">
						<select name="tipe_diskon" id="tipe_diskon">';
						$admincontent .= "<option value=\"0\">-- "._PILIH." --</option>";
						for($i=1;$i<=count($discount_type);$i++) {
							$selected = ($tipe == $i) ? "selected='selected'" : "";
							$admincontent .= "<option value=\"$i\" $selected>$discount_type[$i]</option>";
						}
			$admincontent .= '</select>';
			$admincontent .= '
					</div>
				</div>';
				
			$admincontent .= '
			<div class="control-group">
				<label class="control-label">' . _NOMINALCAPAI . '</label>
				<div class="controls"><input type="text" name="kodevoucher" value="' . $kodevoucher . '" size="40"></div>
			</div>';
			if (!(bool)$is_onetime) {
				$admincontent .= '
					<div id="start-date" class="control-group">
						<label class="control-label">' . _TGLMULAI . '</label>
						<div class="controls">
							<select name="dtglmulai">';
							$admincontent .= "<option value=\"0\">-- "._PILIH." --</option>";
							$ttglmulai = explode("-",$tglmulai);
							$ttglsampai = explode("-",$tglsampai);
							$ttgldipakai = explode("-",$tgldipakai);
							$ymulai = $hariini[year] - 1;
							$yakhir = $hariini[year] + 3;
							for($i=1;$i<=31;$i++) {
								$selected = ($i==$ttglmulai[2]) ? 'selected' : '';
								$admincontent .= "<option value=\"$i\" $selected>$i</option>";
							}
							$admincontent .= '</select>';
							$admincontent .= '<select name="mtglmulai">';
							for($i=1;$i<=12;$i++) {
								$j=$i-1;
								$selected = ($i==$ttglmulai[1]) ? 'selected' : '';
								$admincontent .= "<option value=\"$i\" $selected>$namabulan[$j]</option>";
							}
							$admincontent .= '</select>';
							$admincontent .= '<select name="ytglmulai">';
							for($i=$ymulai;$i<=$yakhir;$i++) {
								$selected = ($i==$ttglmulai[0]) ? 'selected' : '';
								$admincontent .= "<option value=\"$i\" $selected>$i</option>";
							}
							$admincontent .= '</select>';
				$admincontent .= '
						</div>
					</div>';
				$admincontent .= '
					<div id="end-date" class="control-group">
						<label class="control-label">' . _TGLSELESAI . '</label>
						<div class="controls">
							<select name="dtglsampai">';
							$admincontent .= "<option value=\"0\">-- "._PILIH." --</option>";
							for($i=1;$i<=31;$i++) {
								$selected = ($i==$ttglsampai[2]) ? 'selected' : '';
								$admincontent .= "<option value=\"$i\" $selected>$i</option>";
							}
							$admincontent .= '</select>';
							$admincontent .= '<select name="mtglsampai">';
							for($i=1;$i<=12;$i++) {
								$j=$i-1;
								$selected = ($i==$ttglsampai[1]) ? 'selected' : '';
								$admincontent .= "<option value=\"$i\" $selected>$namabulan[$j]</option>";
							}
							$admincontent .= '</select>';
							$admincontent .= '<select name="ytglsampai">';
							for($i=$ymulai;$i<=$yakhir;$i++) {
								$selected = ($i==$ttglsampai[0]) ? 'selected' : '';
								$admincontent .= "<option value=\"$i\" $selected>$i</option>";
							}
							$admincontent .= '</select>';
				$admincontent .= '
						</div>
					</div>';
			}
			$admincontent .= '
			<div class="control-group">
				<label class="control-label">' . _NOMINAL . '</label>
				<div class="controls"><input type="text" name="nominaldiskon" value="' . $nominaldiskon . '" size="40"></div>
			</div>';
			$admincontent .= '
			<div class="control-group">
				<label class="control-label">' . _VOUCHERSTATUS . '</label>
				<div class="controls"><input type="checkbox" name="is_onetime" value="1" '.$publishstatus.'></div>
			</div>';

			$admincontent .='	
			<div class="control-group">
				<div class="controls">
					<input type="submit" name="submit" class="buton"  value="' . _SAVE . '">
					<input type="submit" name="back" class="buton" onclick="javascript:history.go(-1)" value="' . _BACK . '">
				</div>
			</div>';
			
		$admincontent .= '</form>';
	}
}

// DELETE LINK
if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
	$sql = "SELECT id FROM voucher WHERE id='$pid'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) == "0") {
		pesan(_ERROR,_NODISKON);
	} else {
		if ($action == "remove") {
			$admintitle = _DELDISKON;
			$admincontent = _PROMPTDEL;
			$admincontent .= "<a class=\"buton\" href=\"?p=voucher&action=delete&pid=$pid\">"._YES."</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">"._NO."</a></p>";
		} else {
			$sql = "DELETE FROM voucher WHERE id='$pid'";
			$result = $mysql->query($sql);
			if ($result) {
				pesan(_SUCCESS,_DBSUCCESS,"?p=voucher&r=$random");
			} else {
				pesan(_ERROR,_DBERROR);
			}
		}
	}
}

if ($action=='') {
	$sql = "SELECT id FROM voucher WHERE status=1";

	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$pages = ceil($total_records/$max_page_list);

	if ($mysql->num_rows($result) > 0) {
		$start = $screen * $max_page_list;
		$sql = "SELECT id,tglstart,tglend,kodevoucher,tipe,nominaldiskon,nominalbelanja, is_onetime FROM voucher
				WHERE status=1
				ORDER BY id DESC
				LIMIT $start, $max_page_list";
		$result = $mysql->query($sql);

		if ($pages>1) $adminpagination = pagination($namamodul,$screen);
		$admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
		$admincontent .= "<tr><th>"._VOUCERCODE."</th><th>"._TIPEVOUCHER."</th><th>"._TGLMULAI."</th><th>"._TGLSELESAI."</th><th>"._NOMINAL."</th>";
		$admincontent .= "<th align=\"center\">"._ACTION."</th></tr>\n";
		while (list($id,$tglstart,$tglend,$kodevoucher, $tipe, $nominaldiskon, $nominalbelanja, $is_onetime) = $mysql->fetch_row($result)) {
			if ($tipe == 1) {
				$tipe_nominal = number_format($nominaldiskon, 0, ',', '.');
			} else if ($tipe == 2) {
				$tipe_nominal = $nominaldiskon . '%';
			}
			
			$is_disposable_voucher = ((bool)$is_onetime) ? "<span>("._VOUCHERSTATUS.")</span>" : "";
			$admincontent .= "<tr>\n";
			$admincontent .= "<td>$kodevoucher</td>";
			$admincontent .= "<td>".$discount_type[$tipe]." $is_disposable_voucher</td>";
			if (!(bool)($is_onetime)) {
				$admincontent .= "<td>".tglformat($tglstart)."</td>";
				$admincontent .= "<td>".tglformat($tglend)."</td>";
			} else {
				$admincontent .= "<td>&nbsp;</td>";
				$admincontent .= "<td>&nbsp;</td>";
			}
			$admincontent .= "<td align=\"right\">$tipe_nominal</td>";			
			$admincontent .= "<td align=\"center\" class=\"action-ud\">";
			$admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
			$admincontent .= "</tr>\n";
		}
		$admincontent .= "</table>";
		
	} else {
		$admincontent = "<p>"._NODISKON."</p>";
		
	}
	$specialadmin .= "<a class=\"buton\" href=\"?p=voucher&action=add\">"._ADDDISKON."</a>";
}

// if ($specialadmin=='') $specialadmin = "<a href=\"?p=voucher\">"._BACK."</a>";

?>
