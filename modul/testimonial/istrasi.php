<?php
if (!$isloadfromindex) {
	include ("../../kelola/urasi.php");
	include ("../../kelola/fungsi.php");
	include ("../../kelola/lang/$lang/definisi.php");
	pesan(_ERROR,_NORIGHT);
}

$action = fiestolaundry($_REQUEST['action'],10);
$status = fiestolaundry($_REQUEST['status'],10);
if($status=='')
{	$status = 0;
}
$screen=fiestolaundry($_GET['screen'],11);

if($action=='modify')
{	$daftar_id = $_POST["cekComment"];
	$daftar_id = checkrequired($daftar_id,_NOCHECK);
	$list_id = implode(",",$daftar_id);
	$sql = "UPDATE testimonial SET status='1' WHERE id IN ($list_id)";
	if (!$mysql->query($sql)) $action = createmessage(_DBERROR, _ERROR, "error", ""); /* pesan(_ERROR,_DBERROR); */
	// pesan(_SUCCESS,_DBSUCCESS,"?p=$namamodul&r=$random");
	$action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
	
}
if($action=='remove')
{	$daftar_id = $_POST["cekComment"];
	$daftar_id = checkrequired($daftar_id,_NOCHECK);
	$admincontent .= _PROMPTDEL;
	$admincontent .= "<form name=\"myform\" id=\"myform\" method=\"POST\" action=\"$thisfile\" >";
	$admincontent .= "<input type=\"hidden\" name=\"action\" value=\"hapus\">";
	foreach ($daftar_id as $key => $value)
	{	$admincontent .= "<input type=\"hidden\" name='cekComment[]' id='cekComment[]'  value='".$value."'>";

	}
	$admincontent .= "<h5><a class=\"buton\" href=\"javascript:document.myform.submit();\">"._YES."</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">"._NO."</a></h5>";
	$admincontent .= "</form>";
}

if($action=='hapus')
{	$daftar_id = $_POST["cekComment"];
	$daftar_id = checkrequired($daftar_id,_NOCHECK);
	$list_id = implode(",",$daftar_id);
	foreach ($daftar_id as $key => $value)
	{	
		//$sql = "SELECT foto FROM testimonial WHERE id='$value' ";
		// $result = $mysql->query($sql);
		// list($foto) = $mysql->fetch_row($result);
		// $pathfoto = "$cfg_comment_path/$foto";
		$sql = "DELETE FROM testimonial WHERE id='$value' ";
		$result = $mysql->query($sql);
		if($result)
		{	if(file_exists($pathfoto))
			{	@unlink($pathfoto);
			}
		}
		else
		{	break;
		}
	}
	if ($result) 
	{	
		// pesan(_SUCCESS,_DBSUCCESS,"?p=$namamodul&action=&r=$random");
		$action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
	} 
	else 
	{	
		// pesan(_ERROR,_DBERROR);
		$action = createmessage(_DBERROR, _ERROR, "error", "");
	}
	
}


if($action=='')
{		
	$judulstatus = (($status==1)?_ACTIVE:_PENDING);
	$admintitle .= $judulstatus;
	$urlstatus = "index.php?p=$namamodul&action=$action&status=";
	$admincontent .= _STATUS." : ";
	$admincontent .= "<select name=\"status\" id=\"status\" onchange=\"window.location='$urlstatus'+this.value;\">";
	$admincontent .= "<option value=\"0\" ".(($status==0)?"selected=\"selected\" ":"")." >"._PENDING."</option>";
	$admincontent .= "<option value=\"1\" ".(($status==1)?"selected=\"selected\" ":"")." >"._ACTIVE."</option>";
	$admincontent .= "</select>";
	$admincontent .= "<br/><br/>";
	
	$sql = "SELECT * FROM testimonial WHERE status='$status'";
	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$pages = ceil($total_records/$max_page_list);
	if ($pages>1) $adminpagination = pagination($namamodul,$screen,"&status=$status");
	
	if ($mysql->num_rows($result) == "0") 
	{	$admincontent .= "<p>"._NOCOMMENT."</p>";
	} 
	else 
	{	//menampilkan record di halaman yang sesuai
		$start = $screen * $max_page_list;
		$sql = "SELECT id, nama, komentar FROM testimonial WHERE status='$status' ORDER BY tanggal DESC LIMIT $start, $max_page_list";
		$result = $mysql->query($sql);
		
		$admincontent .= "<form name=\"myform\" id=\"myform\" method=\"POST\" action=\"$thisfile\" class=\"form-horizontal\">";
		$admincontent .= "<input type=\"hidden\" name=\"action\" value=\"modify\">";
		$admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
		$admincontent .= "<tr><th>&nbsp;</th><th>" . _NAME . "</th><th>"._COMMENT."</th>\r\n";
		while(list($id, $nama, $komentar)=$mysql->fetch_row($result))
		{	$admincontent .= "<tr>\r\n";
			$admincontent .= "<td valign=\"top\">\r\n";
			$admincontent .= "<input type=\"checkbox\" name='cekComment[]' id='cekComment[]'  value='".$id."'>\r\n";
			$admincontent .= "</td>\r\n";
			$admincontent .= "<td valign=\"top\">$nama</td>\r\n";
			$admincontent .= "<td valign=\"top\">".$komentar."</td>\r\n";
			$admincontent .= "</tr>\r\n";
		}
		$admincontent .= "</table>\r\n";
		
		if($status==0)
		{	$event = "document.myform.elements['action'].value='modify'; document.getElementById(myform).submit(); ";
			$admincontent .= "<input type=\"image\" alt=\"Check\" border=\"0\" src=\"../images/check.gif\" onclick=\"$event\" />\r\n";
			$admincontent .= "&nbsp;&nbsp;";
		}
		$event = "document.myform.elements['action'].value='remove'; document.getElementById(myform).submit(); ";
		$admincontent .= "<input type=\"image\" alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\" onclick=\"$event\" />\r\n";
		$admincontent .= "</form>\r\n";
	}
}

?>