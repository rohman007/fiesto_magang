<?php

if (!$isloadfromindex) {
	include ("../../kelola/urasi.php");
	include ("../../kelola/fungsi.php");
	include ("../../kelola/lang/$lang/definisi.php");
	pesan(_ERROR,_NORIGHT);
}

$pid=fiestolaundry($_REQUEST['pid'],11);
$action = fiestolaundry($_REQUEST['action'],20);
$judul = fiestolaundry($_POST['txtjudul'],20);
$url = fiestolaundry($_POST['txturl'],200);
$shorturl = fiestolaundry($_POST['txtshorturl'],50);

if($action == '')
{
	$sql = "SELECT id FROM to_video ";

	$result = mysql_query($sql);
	$total_records = mysql_num_rows($result);
	$pages = ceil($total_records/$max_page_list);
	if (mysql_num_rows($result) == "0") 
	{	$admincontent .= _NOto_video;
	} 
	else 
	{	$start = $screen * $max_page_list;
	
		$sql = "SELECT id, judul FROM to_video LIMIT $start, $max_page_list";
		$result = mysql_query($sql);
		if ($pages>1) $adminpagination = pagination($namamodul,$screen,"");
		$admincontent .= "<table class=\"list\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">\n";
		$admincontent .= "<tr>";
		$admincontent .= "<th>"._JUDUL."</th>";
		// $admincontent .= "<th>"._EDIT."</th><th>"._DEL."</th></tr>\n";
		$admincontent .= "<th>"._EDIT."</th></tr>\n";
		while (list($id, $judul) = mysql_fetch_row($result)) 
		{
			$admincontent .= "<tr valign=\"top\" >\n";
			$admincontent .= "<td>$judul</td>";
			$admincontent .= "<td align=\"center\"><a href=\"?p=template_option&action=modify&pid=$id\">";
			$admincontent .= "<img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a></td>\n";
			// $admincontent .= "<td align=\"center\"><a href=\"?p=template_option&action=remove&pid=$id\">";
			// $admincontent .= "<img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
			$admincontent .= "</tr>\n";
		}
		$admincontent .= "</table>";
	}
	
	// $specialadmin = "<a href=\"?p=template_option&action=add\">"._ADDto_video."</a>";
}
if($action == 'add')
{
	$admincontent .= "<form method=\"POST\" action=\"?p=template_option&action=save\" />";
	$admincontent .= "<table>";
	$admincontent .= "<tr>
						<td>"._JUDUL." </td><td>:</td>
						<td><input type=\"text\" name=\"txtjudul\" size=\"20\" maxlength=\"20\" /></td>
					  </tr>";
	$admincontent .= "<tr>
						<td align=\"right\">"._URL."</td><td>:</td>
						<td><input type=\"text\" name=\"txturl\"  /></td>
					  </tr>";					  
	$admincontent .= "</table>";
	$admincontent .= "<p><input type=\"submit\" name=\"submit\" value=\""._ADD."\" /></p>";
	$admincontent .= "</form>";
}
if($action == 'save')
{
		$judul = checkrequired($judul,_TITLE);
		$url = checkrequired($url,_URL);
		$temp = $url;
		$arrayurl = explode("/",$url);
		if (($arrayurl[0]=='http:' || $arrayurl[0]=='https:') && ($arrayurl[2]=='youtube.com' || $arrayurl[2]=='www.youtube.com'))
		{
			$tempurl = explode("?",$arrayurl[3]);
			$url = explode("&",$tempurl[1]);
			for($i=0;$i<count($url);$i++)
			{
				if(substr($url[$i],0,1) == "v" )
				{	list($nama,$val) = explode("=",$url[$i]);
				}
			}

		} else {
			pesan(_ERROR,_NOTto_video);
		}
		$sql = "INSERT INTO to_video (judul,url,shorturl) VALUES ('$judul','$temp','$val')";
		$result = mysql_query($sql);
		if($result)
		{
			pesan(_SUCCESS,_DBSUCCESS,"?p=template_option&r=$random");
		} else {
			pesan(_ERROR,_DBERROR);	
		}
}
if($action == 'modify')
{
	$admintitle .= _EDITto_video;
	$sql = "SELECT id,judul,url,shorturl FROM to_video WHERE id='$pid'";
	$result = mysql_query($sql);
	if(list($id,$judul,$url,$shorturl)=mysql_fetch_row($result))
	{
		$admincontent .= "<form method=\"POST\" action=\"?p=template_option&action=edit\" />";
		$admincontent .= "<input type=\"hidden\" name=\"pid\" value=\"".$pid."\" />";
		$admincontent .= "<table>";
		$admincontent .= "<tr>
							<td>"._JUDUL." :</td>
							<td><input type=\"text\" name=\"txtjudul\" size=\"20\" maxlength=\"20\" value=\"".$judul."\" /></td>
						  </tr>";
		$admincontent .= "<tr>
							<td>"._URL." :</td>
							<td><input type=\"hidden\" name=\"txturl\"  value=\"".$url."\"/><input type=\"text\" name=\"txtshorturl\" value=\"".$shorturl."\" /></td>
						  </tr>";					  
		$admincontent .= "</table>";
		$admincontent .= "<p><input type=\"submit\" name=\"submit\" value=\""._EDIT."\" /></p>";
		$admincontent .= "</form>";
	}else{
		$admincontent .= _NOto_video;
	}
}
if($action == 'edit')
{
	$pid = fiestolaundry($_POST['pid'],4);
	$judul = checkrequired($judul,_TITLE);
	$url = checkrequired($url,_URL);
	$shorturl = checkrequired($shorturl,_URL);
	$temp = $shorturl;
	$arrayurl = explode("/",$shorturl);
	if (($arrayurl[0]=='http:' || $arrayurl[0]=='https:') && ($arrayurl[2]=='youtube.com' || $arrayurl[2]=='www.youtube.com'))
	{
		$tempurl = explode("?",$arrayurl[3]);
		$url = explode("&",$tempurl[1]);
		for($i=0;$i<count($url);$i++)
		{
			if(substr($url[$i],0,1) == "v" )
			{	list($nama,$val) = explode("=",$url[$i]);
			}
		}

	} else {
		if ($url=='') {
			pesan(_ERROR,_NOTto_video);
		} else {
			$val = $temp;
			$temp = $url;
		}
	}
	$sql = "UPDATE to_video SET judul='$judul',url='$temp',shorturl='$val'  WHERE id='$pid'";
	$result = mysql_query($sql);
	if($result)
	{
		pesan(_SUCCESS,_DBSUCCESS,"?p=template_option&r=$random");
	}else{
		pesan(_ERROR,_DBERROR,"?p=template_option&r=$random");
	}
}
if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
	$sql = "SELECT id FROM to_video WHERE id = '$pid'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) == "0") {
		pesan(_ERROR,_NOLINK);
	} else {
		if ($action == "remove") {
			$admintitle = _DELto_video;
			$admincontent .= _PROMPTDEL;
			$admincontent .= "<a href=\"?p=template_option&action=delete&pid=$pid\">"._YES."</a> <a href=\"javascript:history.go(-1)\">"._NO."</a></p>";
		} else {
			list($id,$cat_id) = mysql_fetch_row($result);
			$sql = "DELETE FROM to_video WHERE id='$pid'";
			$result = mysql_query($sql);
			if ($result) {
				pesan(_SUCCESS,_DBSUCCESS,"?p=template_option&r=$random");
			} else {
				pesan(_ERROR,_DBERROR);
			}
		}
	}
}
?>