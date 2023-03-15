<?php
if (!$isloadfromindex) {
	include ("../../kelola/urasi.php");
	include ("../../kelola/fungsi.php");
	include ("../../kelola/lang/$lang/definisi.php");
	pesan(_ERROR,_NORIGHT);
}
$pid=fiestolaundry($_REQUEST['pid'],11);
$action = fiestolaundry($_REQUEST['action'],20);
$grouptype = fiestolaundry($_POST['grouptype'],20);
$type = fiestolaundry($_POST['type'],11);
$parent = fiestolaundry($_POST['parent'],11);
if($parent=='') $parent=0;
$judul = fiestolaundry($_POST['judul'],100);
$isi = fiestolaundry($_POST['isi'],0,TRUE);
$urutan = fiestolaundry($_POST['urutan'],11);
$urlpattern = fiestolaundry($_POST['urlpattern'],100);
$menuconfig = $_POST['menuconfig'];

$url = fiestolaundry($_POST['url'],255);

$success = 'success';
$notification = fiestolaundry($_GET['notification'],15);
$sqlinduk = "SELECT id FROM menutype WHERE modul='menu' AND jenis='parent' ";
$resultinduk = $mysql->query($sqlinduk);
list($idjenismenuinduk) = $mysql->fetch_row($resultinduk);


$modulename = $_GET['p'];

if ($action == "del") {	
	if (empty($pid)) pesan(_ERROR,_NOMENU);
	$admintitle .= _DELMENU;
	$sql =	"SELECT id FROM menu WHERE id=$pid";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		list ($id) = $mysql->fetch_row($result);
		$admincontent  = "<p>"._PROMPTDEL." <a class=\"buton\" href=\"?p=menu&action=hapus&pid=$pid\">"._YES."</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">"._NO."</a></p>";
	} else {
		pesan(_ERROR,_NOMENU);
	}
	// $action="";
}

if ($action == "baru") {
	$valid=true;
	if(!cekformsecret()){$valid=false;}
	$judul = checkrequired($judul, _MENUTITLE);
	$judul = strip_tags($judul);
	if($judul==''){$action = createmessage(_MENUTITLEERROR, _ERROR, "error", "");}
	
		if (!preg_match('/[0-9]*/',$urutan)) $urutan=1;
	
	if($grouptype!="others")
	{	$type = $grouptype;
	}
	
	if($valid)	
	{
		// $sql = "SELECT id, isconfigurable FROM menutype ORDER BY namatampilan";
		$sql = "SELECT t.id, t.isconfigurable FROM menutype t inner join module m on t.modul=m.nama  WHERE t.modul!='menu' ORDER BY t.namatampilan";	
		$result = $mysql->query($sql);
		$k=0;
		while (list($id, $isconfigurable)=$mysql->fetch_row($result)) 
		{
			if ($isconfigurable){
				if ($id==$type) $isi = $menuconfig[$k];
				$k++;
			} else {
				if ($id==$type) $isi = 0;
			}
		}
		$mx = getMaxNumber('menu', 'urutan')+2;
		
		if ($pakaislug) {
			$url = $url == '' ? seo_friendly_url($judul, 'menu', $pid) : seo_friendly_url($url, 'menu', $pid);
		}
		
		$sql = "INSERT INTO menu (type, parent, judul, isi, urutan, url) values ('$type','$parent','$judul','$isi','$mx', '$url')";
		$result = $mysql->query($sql);
		$mid = $mysql->insert_id();
		$result = urutkan('menu', $urutan, "parent='".$parent."'", $mid, "parent='".$parent."'");
		if ($result) 
		{	
			$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
		} else {
			// pesan(_ERROR,_DBERROR);
			$action = createmessage(_DBERROR, _ERROR, "success", "");
		}
	}
	else
	{
		$action="";
	}
	
}

if ($action == "ubah") {
	$valid=true;
	$judul = checkrequired($judul, _MENUTITLE);
	$judul = strip_tags($judul);
	if(!cekformsecret()){$valid=false;}
	if($judul==''){$action = createmessage(_MENUTITLEERROR, _ERROR, "error", "edit"); $valid=false;}
	
	if (!preg_match('/[0-9]*/',$urutan)) $urutan=1;
	
	if($grouptype!="others")
	{	$type = $grouptype;
	}
	if($type!=$idjenismenuinduk)
	{	$sqlchild = "SELECT id FROM menu WHERE parent='$pid' ";
		$resultchild = $mysql->query($sqlchild);
		$totalchild = $mysql->num_rows($resultchild);
		if($totalchild>0)
		{	pesan(_ERROR,_HAVECHILDINDUK);
		}
	}
	if($valid)
	{
		// $sql = "SELECT id, isconfigurable FROM menutype ORDER BY namatampilan";
		$sql = "SELECT t.id, t.isconfigurable FROM menutype t inner join module m on t.modul=m.nama  WHERE t.modul!='menu' ORDER BY t.namatampilan";
		$result = $mysql->query($sql);
		$k=0;
		while (list($id, $isconfigurable)=$mysql->fetch_row($result)) {
			if ($isconfigurable){
				if ($id==$type) $isi = $menuconfig[$k];
				$k++;
			} else {
				if ($id==$type) $isi = 0;
			}
		}
		
		$sqlcek = "SELECT * FROM menu WHERE id='$pid'";
		$resultcek = $mysql->query($sqlcek);
		$datacek = $mysql->fetch_array($resultcek);
		$kondisiprev = "parent='".$datacek["parent"]."' ";
		
		$kondisi = "parent='".$parent."'";
		if ($pakaislug) {
			$url = $url == '' ? seo_friendly_url($judul, 'menu', $pid) : seo_friendly_url($url, 'menu', $pid);
		}
		$sql = "UPDATE menu SET parent='$parent', type='$type', judul='$judul', isi='$isi', url='$url' WHERE id='$pid'";

		$result = $mysql->query($sql);
		
		$result = urutkan('menu', $urutan, $kondisi, $pid, $kondisiprev);
		if($kondisi!=$kondisiprev)
		{	urutkansetelahhapus('menu', $kondisiprev);
		}
		if ($result) {
			// pesan(_SUCCESS,_DBSUCCESS,"?p=menu&r=$random");
			$action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
		} else {
			// pesan(_ERROR,_DBERROR);
			
			$action = createmessage(_DBERROR, _ERROR, "error", "");
		}
	}
	else
	{
		$action="";
	}
	
}
if ($action == "add" || $action == "edit") {
	$admincontent .= "<form class=\"form-horizontal\" name=\"menu\" method=\"POST\" action=\"?p=menu\">\r\n";
	if ($action == "add") 	{
		$sql = "SELECT t.namatampilan FROM menutype t inner join module m on t.modul=m.nama WHERE t.id='$type'";
		$result = $mysql->query($sql);
		list($namatampilan) = $mysql->fetch_row($result);
		$admintitle .= _ADDMENU;
		$admincontent .= "<h3>$namatampilan</h3>\r\n";
		$admincontent .= "<input type=\"hidden\" name=\"action\" value=\"baru\" />\r\n";
	}
	if ($action == "edit") {
		if (empty($pid)) pesan(_ERROR,_NOWIDGET);
		$sql =	"SELECT m.id, m.parent, m.type, m.judul, m.isi, m.urutan, mt.namatampilan, m.url FROM menu m, menutype mt WHERE m.id='$pid' AND m.type=mt.id";
		$result = $mysql->query($sql);
		if ($mysql->num_rows($result)==0) pesan(_ERROR,_NOMENU);
		list($id, $parent, $type, $judul, $isi, $urutan, $namatampilan, $url) = $mysql->fetch_row($result);
		$admintitle .= _EDITMENU;
		$admincontent .= "<h3>$namatampilan</h3>\r\n";
		$admincontent .= "<input type=\"hidden\" name=\"action\" value=\"ubah\" />\r\n";
		$admincontent .= "<input type=\"hidden\" name=\"pid\" value=\"$pid\" />\r\n";
	}

	$sql = "SELECT id, modul, jenis, namatampilan, isconfigurable FROM menutype WHERE id='$type'";
	$result = $mysql->query($sql);
	list($id, $modul, $jenis, $namatampilan, $isconfigurable) = $mysql->fetch_row($result);
	$admincontent .=setformsecret();
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._MENUTITLE."</label>";
	$admincontent .="<div class=\"controls\"><input type=\"text\" name=\"judul\" value=\"$judul\"></div></div>";
	if ($pakaislug) {
		$admincontent .= "<div class=\"control-group\"><label class=\"control-label\">"._SLUGURL."</label>";
		$admincontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$url."\"/></div></div>";
	}
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._MENUTYPE."</label>";
	$admincontent .="<div class=\"controls\">";

	$sql1 = "SELECT t.id, t.modul, t.jenis, t.namatampilan, t.isconfigurable FROM menutype t inner join module m on t.modul=m.nama WHERE modul!='menu' ORDER BY namatampilan";
	$result1 = $mysql->query($sql1);
	$j=0;
	$k=0;
	
	$pilihan = "<select id=\"type\" name=\"type\" onclick=\"setCheckedValue(document.getElementById('grouptype'), 'others')\" onchange=\"showpilihan2(this);\" >\r\n";
	while (list($id, $modul, $jenis, $namatampilan, $isconfigurable)=$mysql->fetch_row($result1)) {
		$j++;
		$pilihan .= ($j==1) ? '' : '<br />';
		if ($id==$type) {
			$selected = 'selected';
			$display = '';
		} else {
			$selected = '';
			$display = 'none';
		}
		if ($isconfigurable) {
			$pilihan .= "<option value=\"$id\" onClick=\"showpilihan($k)\" $selected data-isconfig=\"$k\"/>$namatampilan</option>\r\n";
			include("../modul/$modul/lang/$lang/definisi.php");
			include("../modul/$modul/menuistrasi.php");
			$k++;
		} else {
			$pilihan .= "<option value=\"$id\" onClick=\"hideallpilihan()\" $selected />$namatampilan</option>\r\n";
		}
	}
	$pilihan .= "</select>\r\n";
	$admincontent .= '<script type="text/javascript">
	function showpilihan(terpilih) {
		for (k=0;k<'.$k.';k++) {
			if (k==terpilih) {
				document.getElementById("pilihan"+k).style.display="";
			} else {
				document.getElementById("pilihan"+k).style.display="none";
			}
		}
	}
	function showpilihan2(terpilih) {
		
		var terpilih = $("#type option:selected").attr("data-isconfig");
		
		for (k=0;k<'.$k.';k++) {
			if (k==terpilih) {
				document.getElementById("pilihan"+k).style.display="";
			} else {
				document.getElementById("pilihan"+k).style.display="none";
			}
		}
	}
	function hideallpilihan() {
		for (k=0;k<'.$k.';k++) {
			document.getElementById("pilihan"+k).style.display="none";
		}
	}
	</script>';
	$sql1 = "SELECT id, modul,jenis FROM menutype WHERE modul='menu' AND id='$type' ";
	$result1 = $mysql->query($sql1);
	
	$is_others = (($mysql->num_rows($result1)>0)?false:true);
	$admincontent .= "<input type=\"radio\" name=\"grouptype\" id=\"grouptype\" value=\"others\" ";
	if($is_others)
	{	$admincontent .= "checked ";
	}
	$admincontent .= "> $pilihan <br>";
	
	$sql1 = "SELECT id, modul, jenis, namatampilan, isconfigurable FROM menutype WHERE modul='menu' ";
	$result1 = $mysql->query($sql1);
	while (list($id, $modul, $jenis, $namatampilan, $isconfigurable)=$mysql->fetch_row($result1)) 
	{	$selected = "";
		if($type==$id)
		{	$selected = "checked ";
		}
		$admincontent .= "<input type=\"radio\" name=\"grouptype\" id=\"grouptype\" value=\"$id\" $selected > $namatampilan<br>";
	}
	$admincontent .= "</div></div>";
	$admincontent .= $modulurasi;
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._SUBMENUOF."</label>";
	$admincontent .="<div class=\"controls\">";
		$urlajax = $absolutadminurl."/ajax.php?p=menu&action=urutan";
	if($action == "edit")
	{	$urlajax .= "&pid=".$pid;
	}
	$eventurutan = "onchange=\"ajaxpage('".$urlajax."&parent='+this.value, 'urutancontent')\" ";
	$admincontent .= "<select name=\"parent\" $eventurutan >\r\n";

	$cats = new categories();
	$mycats = array();
	$sql = "SELECT id, judul, parent FROM menu WHERE type=(SELECT id FROM menutype WHERE modul='menu' AND jenis='parent' ) ";
	
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'judul'=>$row['judul'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);
	// mysql_free_result($result);
	$admincontent .= "<option value=\"0\">$topcatnamecombo /</option>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$isallowed=true;
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		$judul = $cats->cats[$i]['judul'];
		$admincontenttemp = '<option value="'.$cats->cats[$i]['id'].'"';
		$admincontenttemp .= ($cats->cats[$i]['id']==$parent) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
		for ($a=0; $a<count($cats->cat_map); $a++) {
			if ($cats->cat_map[$a]['id']!=$pid) {
				if ($isallowed) $admincontenttemp .= " / ".$cats->cat_map[$a]['judul'];
			} else {
				$admincontenttemp = "";
				$isallowed=false;
			}
		}
		if ($cats->cats[$i]['id']!=$pid) {
			if ($isallowed) $admincontenttemp .= " / $judul</option>";
		} else {
			$admincontenttemp = "";
			$isallowed=false;
		}
		$admincontent .= $admincontenttemp;
	}

	$admincontent .= "</select></div></div>\r\n";
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._ORDER."</label>";
	$admincontent .="<div class=\"controls\">";
	$admincontent .= "<div id=\"urutancontent\">";
	$admincontent .= createurutan("menu", ((strlen($parent)>0)?$parent:0), $pid);
	//$admincontent .= "<input type=\"text\" name=\"urutan\"  value=\"$urutan\" size=\"2\" />";
	$admincontent .= "</div>";
	$admincontent .= "</div></div>\r\n";
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\"></label>";
	$admincontent .="<div class=\"controls\"><input class=\"buton\" type=\"submit\" name=\"submit\" value=\""._SAVE."\"><a href=\"?p=menu\" class=\"buton\" type=\"submit\">"._CANCEL."</a></div></div>";
			
	$admincontent .= "</form>";
	// $action="";
}
if ($action == "hapus") {
	$pid = checkrequired($pid, _ID);
	$sql =	"SELECT id, type, parent FROM menu WHERE id=$pid";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result)>0) {
		list ($id, $type, $parent) = $result->fetch_row();
		
		$sqlchild = "SELECT id FROM menu WHERE parent='$pid' ";
		$resultchild = $mysql->query($sqlchild);
		$totalchild = $mysql->num_rows($resultchild);
		if($totalchild>0)
		{	pesan(_ERROR,_HAVECHILD);
		}
		
		$sql = "DELETE FROM menu WHERE id='$pid'";
		if ($mysql->query($sql)) {
			
			urutkansetelahhapus("menu", "parent='".$parent."'");
			// pesan(_SUCCESS,_DBSUCCESS,"?p=menu&r=$random");
			
			$action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
		} else {
			// pesan(_ERROR,_DBERROR);
			$action = createmessage(_DBERROR, _ERROR, "error", "");
		}
	} else {
		// pesan(_ERROR,_NOMENU);
		  $action = createmessage(_DBERROR, _ERROR, "error", "");
	}
	
}

if ($action=='') {
	//_MENUTYPE
	$admintitle=_ADDMENU;
	$admincontent .= "<form  class=\"form-horizontal\" name=\"menu\" method=\"POST\" action=\"?p=menu&action=baru\">\r\n";
	$admincontent .=setformsecret();
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._MENUTITLE."</label>";
	$admincontent .="<div class=\"controls\"><input type=\"text\" name=\"judul\" id=\"judul\" /></div></div>";
	
	if ($pakaislug) {
		$admincontent .= "<div class=\"control-group\"><label class=\"control-label\">"._SLUGURL."</label>";		
		$admincontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\"/></div></div>";
	}
	
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._MENUTYPE."</label>";

	$sql1 = "SELECT t.id, t.modul, t.jenis, t.namatampilan, t.isconfigurable FROM menutype t inner join module m on t.modul=m.nama WHERE modul!='menu' ORDER BY namatampilan";
	$result1 = $mysql->query($sql1);
	$j=0;
	$k=0;
	
	$pilihan = "<select id=\"type\" name=\"type\" onclick=\"setCheckedValue(document.getElementById('grouptype'), 'others')\" onchange=\"showpilihan2(this);\" >\r\n";
	while (list($id, $modul, $jenis, $namatampilan, $isconfigurable)=$mysql->fetch_row($result1)) {
		$j++;
		$pilihan .= ($j==1) ? '' : '<br />';
		if ($id==$type) {
			$selected = 'selected';
			$display = '';
		} else {
			$selected = '';
			$display = 'none';
		}
		if ($isconfigurable) {
			// echo "../modul/$modul/menuistrasi.php | showpilihan($k)<br>";
			$pilihan .= "<option value=\"$id\" onClick=\"showpilihan($k)\" $selected data-isconfig=\"$k\"/>$namatampilan</option>\r\n";
			include("../modul/$modul/lang/$lang/definisi.php");
			include("../modul/$modul/menuistrasi.php");
			$k++;
		} else {
			$pilihan .= "<option value=\"$id\" onClick=\"hideallpilihan()\" $selected />$namatampilan</option>\r\n";
		}
	}
	$pilihan .= "</select>\r\n";
	
	$admincontent .= '<script type="text/javascript">
	
	function showpilihan(terpilih) {
		for (k=0;k<'.$k.';k++) {
			if (k==terpilih) {
				document.getElementById("pilihan"+k).style.display="";
			} else {
				document.getElementById("pilihan"+k).style.display="none";
			}
		}
	}
	
	
	function showpilihan2(terpilih) {
		
		var terpilih = $("#type option:selected").attr("data-isconfig");
		
		for (k=0;k<'.$k.';k++) {
			if (k==terpilih) {
				document.getElementById("pilihan"+k).style.display="";
			} else {
				document.getElementById("pilihan"+k).style.display="none";
			}
		}
	}
	
	function hideallpilihan() {
		for (k=0;k<'.$k.';k++) {
			document.getElementById("pilihan"+k).style.display="none";
		}
	}
	</script>';
	$sql1 = "SELECT id, modul,jenis FROM menutype WHERE modul='menu' AND id='$type' ";
	$result1 = $mysql->query($sql1);
	
	$is_others = (($mysql->num_rows($result1)>0)?false:true);
	$admincontent .= "<div class=\"controls\"><input type=\"radio\" name=\"grouptype\" id=\"grouptype\" value=\"others\" ";
	if($is_others)
	{	$admincontent .= "checked ";
	}
	$admincontent .= "> $pilihan <br>";
	
	
	$sql1 = "SELECT id, modul, jenis, namatampilan, isconfigurable FROM menutype WHERE modul='menu' ";
	$result1 = $mysql->query($sql1);
	while (list($id, $modul, $jenis, $namatampilan, $isconfigurable)=$mysql->fetch_row($result1)) 
	{	$selected = "";
		if($type==$id)
		{	$selected = "checked ";
		}
		$admincontent .= "<input type=\"radio\" name=\"grouptype\" id=\"grouptype\" value=\"$id\" $selected > $namatampilan<br>";
	}
	$admincontent .= "</div></div>\r\n";
	$admincontent .= $modulurasi;
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._SUBMENUOF."</label>";
	$admincontent .="<div class=\"controls\">";
	
	$urlajax = $absolutadminurl."/ajax.php?p=menu&action=urutan";
	if($action == "edit")
	{	$urlajax .= "&pid=".$pid;
	}
	$eventurutan = "onchange=\"ajaxpage('".$urlajax."&parent='+this.value, 'urutancontent')\" ";
	$admincontent .= "<select name=\"parent\" $eventurutan >\r\n";

	$cats = new categories();
	$mycats = array();
	$sql = "SELECT id, judul, parent FROM menu WHERE type=(SELECT id FROM menutype WHERE modul='menu' AND jenis='parent' ) ";
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'judul'=>$row['judul'],'parent'=>$row['parent'],'level'=>0);
	}
	
	$pid=fiestolaundry($_GET['pid'],11);
	$cats->get_cats($mycats);
	// mysql_free_result($result);
	$admincontent .= "<option value=\"0\">$topcatnamecombo /</option>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		$isallowed=true;
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		$judul = $cats->cats[$i]['judul'];
		$admincontenttemp = '<option value="'.$cats->cats[$i]['id'].'"';
		$admincontenttemp .= ($cats->cats[$i]['id']==$parent) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
		for ($a=0; $a<count($cats->cat_map); $a++) {
			if ($cats->cat_map[$a]['id']!=$pid) {
				if ($isallowed) $admincontenttemp .= " / ".$cats->cat_map[$a]['judul'];
			} else {
				$admincontenttemp = "";
				$isallowed=false;
			}
		}
		if ($cats->cats[$i]['id']!=$pid) {
			if ($isallowed) $admincontenttemp .= " / $judul</option>";
		} else {
			$admincontenttemp = "";
			$isallowed=false;
		}
		$admincontent .= $admincontenttemp;
	}

	$admincontent .= "</select></div></div>\r\n";
	$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._ORDER."</label>";
	$admincontent .="<div class=\"controls\">";
	$admincontent .= "<div id=\"urutancontent\">";
	$admincontent .= createurutan("menu", ((strlen($parent)>0)?$parent:0), $pid);
	//$admincontent .= "<input type=\"text\" name=\"urutan\"  value=\"$urutan\" size=\"2\" />";
	$admincontent .= "</div>";
	$admincontent .= "</div></div>\r\n";
	
	
	///////////////////////////////////////////
	/*
$admincontent .="<div class=\"control-group\"><label class=\"control-label\">"._SUBPARENT."</label>";
$admincontent .="<div class=\"controls\">";
$admincontent .="<select name=\"parent\" id=\"parent\">\n";
		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
		
		$admincontent .="<option value=\"0\" >"._TOP."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
			
				$cats->cat_map($cats->cats[$i]['id'],$mycats);
				$cat_name = $cats->cats[$i]['nama'];
				$admincontent .='<option value="'.$cats->cats[$i]['id'].'"';
				$admincontent .=">$topcatnamecombo";
				
				for ($a=0; $a<count($cats->cat_map); $a++) {
					$cat_parent_id = $cats->cat_map[$a]['id'];
					$cat_parent_name = $cats->cat_map[$a]['nama'];
					$admincontent .=" . . . ";//$cat_parent_name
				}
				$admincontent .="$cat_name</option>";
			
			
		}
		$admincontent .="</select></div></div>\r\n";
*/		

$admincontent .= "<div class=\"control-group\"><div class=\"controls\"><input class=\"buton\" type=\"submit\" name=\"submit\" value=\""._SAVE."\"></div></div>";
$admincontent .="<div id='kategori'>";
$admincontent .= catstructure('SELECT id, judul, parent,type FROM menu ORDER BY urutan');
$admincontent .="</div>";
$admincontent .="</div>";
$admincontent .="</form>";	


}
?>