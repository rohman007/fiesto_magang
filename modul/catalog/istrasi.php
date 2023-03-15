<?php
/**
* Modified by Nyamenk
* 2018-03-05
*
* Applied to Coding Standard
* 
*/

if (!$isloadfromindex) {
	include ("../../kelola/urasi.php");
	include ("../../kelola/fungsi.php");
	include ("../../kelola/lang/$lang/definisi.php");
	pesan(_ERROR,_NORIGHT);
	
} else {

	$keyword = fiestolaundry($_GET['keyword'],100);
	$screen = fiestolaundry($_GET['screen'],11);
	$pid = fiestolaundry($_REQUEST['pid'],11);

	$cat_id = fiestolaundry($_REQUEST['cat_id'],11);
	$parent = fiestolaundry($_REQUEST['parent'],11);
	if($parent=='') $parent=0;
	$action = fiestolaundry($_REQUEST['action'],20);

	$title = fiestolaundry($_POST['title'],255);
	
	//post special case
	foreach($sc as $label =>$tname) {
		list($name) = explode(":",$tname);
		$$name = fiestolaundry($_POST[$name],1);
		$sc_field[] = $name;
		$sc_value[] = strlen($$name)>0?$$name:0;
		$sc_update[] = "$name='".(strlen($$name)>0?$$name:0)."'";
	}
	
	//untuk add
	$sc_field = ",".join(",",$sc_field);
	$sc_value = ",'".join("','",$sc_value)."'";
	//untuk update
	$sc_update = ",".join(",",$sc_update);
	$merek = fiestolaundry($_POST['merek'],11);

	$ketsingkat = fiestolaundry($_POST['ketsingkat'],0,TRUE);
	$keterangan = fiestolaundry($_POST['keterangan'],0,TRUE);
	$harganormal = fiestolaundry($_POST['harganormal'],13);
	$diskon = fiestolaundry($_POST['diskon'],17);
	$sku = fiestolaundry($_POST['sku'],40);

	$nama = fiestolaundry($_POST['nama'],200);
	$cat_desc = fiestolaundry($_POST['cat_desc'],0,TRUE);
	$urutan = fiestolaundry($_POST['urutan'],11);
	$url = fiestolaundry($_POST['url'],255);

	$additional = count($customfield);
	for ($i=0;$i<$additional;$i++) {
		switch ($typecustom[$i]) {
			case 'TINYINT':
				$maxlength=4;
				break;
			case 'SMALLINT':
				$maxlength=6;
				break;
			case 'MEDIUMINT':
				$maxlength=8;
				break;
			case 'INT':
				$maxlength=11;
				break;
			case 'DECIMAL': 
				$splitparameter = explode(",",$paracustom[$i]);
				$maxlength = $splitparameter[0]+1;	//akomodasi tanda minus
				break;
			case 'VARCHAR': 
				$maxlength=$paracustom[$i];
				break;
			case 'DOLLAR': 
				$maxlength=9;
				break;
			case 'RUPIAH': 
				$maxlength=13;
				break;
			case 'TEXT':
				$maxlength=65535;
				break;
			case 'ENUM': 
				$splitparameter = explode("/",$paracustom[$i]);
				$maxlength=0;
				foreach ($splitparameter as $pilihan) {
					if (strlen($pilihan)>$maxlength) $maxlength=strlen($pilihan);
				}
				break;
			case 'DATE':
				$maxlength=10;
				break;
			case 'DATETIME':
				$maxlength=19;
				break;
		}

		$$customfield[$i] = fiestolaundry($_POST[$customfield[$i]],$maxlength,TRUE);
	}

// ----------------------------------------------
// ADD / EDIT CATEGORY CONFIRMED
// ----------------------------------------------

if ($action == "baru") {
	$totalupload = count($_FILES['foto_add']['name']);
	if($totalupload>0) {
		$namathumbnail=array();
		$hasilupload = fiestouploadr('foto_add', $cfg_fullsizepics_path,'', $maxfilesize,$pid,$allowedtypes="gif,jpg,jpeg,png");
		if($hasilupload != _SUCCESS) pesan(_ERROR,$hasilupload);
		
		$totalupload=count($_FILES['foto_add']['name']);
		for($x=0;$x<=$totalupload;$x++) {
			if($_FILES['foto_add']['name'][$x] != '') {
				//ambil informasi basename dan extension
				$temp = explode(".",$_FILES['foto_add']['name'][$x]);
				$extension = $temp[count($temp)-1];
				$basename = '';
				for ($i=0;$i<count($temp)-1;$i++) {
					$basename .= $temp[$i];
				}
			
				$thumb_name=$namathumbnail[$x];
				
				list($filewidth,$fileheight,$filetype,$fileattr) = getimagesize("$cfg_fullsizepics_path/".$thumb_name);

				//create thumbnail
				$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_thumb_path/$thumb_name",'l',$cfg_thumb_width);
				if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

				if ($filewidth > $cfg_max_width) {

					//rename sambil resize gambar asli sesuai yang diizinkan
					$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_fullsizepics_path/$thumb_name",'w',$cfg_max_width);
					if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

					//del gambar asli
					//unlink("$cfg_fullsizepics_path/".$_FILES['filename']['name']);

				}
				$nama_file = $thumb_name;
			}
		}	
	}
	
	$nama = strip_tags($nama);
	//$cat_desc = strip_tags($cat_desc);
	$nama = checkrequired($nama, _CATNAME);
	if($nama!='') {
		if (!preg_match('/[0-9]*/',$urutan)) $urutan=1;
		$mx = getMaxNumber('catalogcat', 'urutan',"parent='".$parent."' ")+2;
		if ($pakaislug) {
			$url = $url == '' ? seo_friendly_url($nama, 'catalogcat', $pid) : seo_friendly_url($url, 'catalogcat', $pid);
		}
		$sql = "INSERT catalogcat (nama, parent, description,urutan,filename,url) values ('$nama','$parent','$cat_desc','$mx','$nama_file','$url')";
		$result = $mysql->query($sql);
		$mid = $mysql->insert_id();
		$result = urutkan('catalogcat', $urutan, "parent='".$parent."' ", $mid, "parent='".$parent."' ");
		if ($result) {
			$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");
		} else {
			$action = createmessage(_DBERROR, _ERROR, "error", "main");
		}
	}

}

if ($action == "ubah") {
	$nama = strip_tags($nama);
	//$cat_desc = strip_tags($cat_desc);
	$nama = checkrequired($nama, _CATNAME);
	if($nama!='') {
		$totalupload=count($_FILES['foto_add']['name']);
		if($totalupload>0) {
			$namathumbnail=array();
			$hasilupload = fiestouploadr('foto_add', $cfg_fullsizepics_path,'', $maxfilesize,$pid,$allowedtypes="gif,jpg,jpeg,png");
			if($hasilupload!=_SUCCESS) pesan(_ERROR,$hasilupload);
			
			$totalupload=count($_FILES['foto_add']['name']);
			for($x=0;$x<=$totalupload;$x++) {
				if($_FILES['foto_add']['name'][$x] != '') {
					//ambil informasi basename dan extension
					$temp = explode(".",$_FILES['foto_add']['name'][$x]);
					$extension = $temp[count($temp)-1];
					$basename = '';
					for ($i=0;$i<count($temp)-1;$i++) {
						$basename .= $temp[$i];
					}
				
					$thumb_name=$namathumbnail[$x];
					
					list($filewidth,$fileheight,$filetype,$fileattr) = getimagesize("$cfg_fullsizepics_path/".$thumb_name);

					//create thumbnail
					$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_thumb_path/$thumb_name",'l',$cfg_thumb_width);
					if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

					if ($filewidth > $cfg_max_width) {

						//rename sambil resize gambar asli sesuai yang diizinkan
						$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_fullsizepics_path/$thumb_name",'w',$cfg_max_width);
						if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

						//del gambar asli
						//unlink("$cfg_fullsizepics_path/".$_FILES['filename']['name']);

					}

					$sql = "UPDATE catalogcat SET filename='".$thumb_name."' WHERE id='".$cat_id."'";
					$result = $mysql->query($sql);
					
					if ($result)  {
						$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");
					} else {
						$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "catedit");
					}
				}
			}	
		}
		
		if (empty($cat_id)) {
			$action = createmessage(_NOCAT, _ERROR, "error", "catedit");	
		} else {
			if (!preg_match('/[0-9]*/',$urutan)) $urutan=1;
			
			$sqlcek = "SELECT * FROM catalogcat WHERE id='$cat_id'";
			$resultcek = $mysql->query($sqlcek);
			$datacek = $mysql->fetch_array($resultcek);
			$kondisiprev = "parent='".$datacek["parent"]."' ";

			$kondisi = "parent='".$parent."'";
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($nama, 'catalogcat', $pid) : seo_friendly_url($url, 'catalogcat', $pid);
			}
			$sql = "UPDATE catalogcat SET nama='$nama', parent='$parent', description='$cat_desc', url='$url' WHERE id='$cat_id'";
			$result = $mysql->query($sql);
			$result = urutkan('catalogcat', $urutan, $kondisi, $cat_id, $kondisiprev);	
			if($kondisi!=$kondisiprev) {	
				urutkansetelahhapus('catalogcat', $kondisiprev);
			}
		}

		if ($result) {
			$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");
		} else {
			$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "catedit");
		}
	} else {
		$action="catedit";
	}
}

// ----------------------------------------------
// DELETE CATEGORY
// ----------------------------------------------
if ($action == "catdel") {
	if (empty($cat_id)) {
		pesan(_ERROR,_NOCAT);
	} else {

		// determine if the category is empty and notify the user if the category is not empty
		$sql =	"SELECT id FROM catalogdata WHERE cat_id='$cat_id'";
		$cat = $mysql->query($sql);
		$number=$mysql->num_rows($cat);
		if ($number > 0) {
			$catcontent .= _PROMPTDELCAT;
			$catcontent .= "<a class=\"buton\" href=\"?p=catalog&action=delete_cat_confirmed&cat_id=$cat_id\">"._YES."</a> &nbsp;<a class=\"buton\" href=\"?p=catalog\">"._NO."</a></p>";
		} else {
			$sql =	"SELECT id FROM catalogcat where parent='$cat_id' ORDER BY urutan ";
			$result = $mysql->query($sql);
			$catsubcatnumber=$mysql->num_rows($result);
			if ($catsubcatnumber > 0) {
				pesan(_ERROR,_HAVECHILD);
			} else {
				$catcontent .= _PROMPTDELCAT;
				$catcontent .= "<a class=\"buton\" href=\"?p=catalog&action=delete_cat_confirmed&cat_id=$cat_id\">"._YES."</a>&nbsp;<a class=\"buton\" href=\"?p=catalog&action=main\">"._NO."</a></p>";
			}
		}
	}
}

// ----------------------------------------------
// DELETE CATEGORY CONFIRMED
// ----------------------------------------------
if ($action == "delete_cat_confirmed") {
	$sql = "SELECT * FROM catalogcat WHERE id='$cat_id'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) == "0") {
		$action = createmessage(_NOCAT, _ERROR, "success", "main");			
	} else {	
		list($cid,$cparent,$cnama,$cdescription,$curutan) = $mysql->fetch_row($result);
		if ($cparent == "0") {	
			$sql = "SELECT * FROM catalogcat WHERE parent='0' ";
			$result = $mysql->query($sql);
			if ($mysql->num_rows($result) <= 1) {	
				$action = createmessage(_1CAT, _ERROR, "error", "main");		
			}
		}	
		
		$sql = "SELECT id, filename FROM catalogdata WHERE cat_id='$cat_id'";
		$getfilename = $mysql->query($sql); 	 
		
		while (list($photo_id, $photo_filename) = $mysql->fetch_row($getfilename)) {	
			$sql1 =	"DELETE FROM catalogdata WHERE id='$photo_id'";
			if (!$mysql->query($sql1)) {	
				$action = createmessage(_DBERROR." ID:$photo_id)", _ERROR, "error", "main");			
			}

			$fullsize = $cfg_fullsizepics_path.'/'.$photo_filename;
			$thumb = $cfg_thumb_path.'/'.$photo_filename;

			if (file_exists($fullsize)) {
				if (@unlink($fullsize)) {	
					$error= _FILEERROR5." (Full Size, ID:$photo_id)<br />\n";
					$action = createmessage($error, _ERROR, "error", "main");		
				}
			}

			if (file_exists($thumb)) {
				if (@unlink($thumb)) {	
					$error = _FILEERROR5." (Thumbnail, ID:$photo_id)<br />\n";
					$action = createmessage($error, _ERROR, "error", "main");	
				}
			}
		}
		
		$sql =	"DELETE FROM catalogdata WHERE cat_id='$cat_id'";
		if (!$mysql->query($sql)) {
			$action = createmessage(_DBERROR, _ERROR, "error", "main");	
		} else {
			$sql = "DELETE FROM catalogcat WHERE id='$cat_id'";
			if ($mysql->query($sql)) {	
				urutkansetelahhapus("catalogcat", "parent='".$cparent."' ");
				//pesan(_SUCCESS,_DBSUCCESS,"?p=catalog&action=main&r=$random");
				$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "main");	
			} 
			else {	
				$action = createmessage(_DBERROR, _ERROR, "error", "main");	
			}
		}
	}
	$action="main";
}

if ($action == 'cathome') {
	$admincontent .= "
		<ul>
			<li><a href=\"?p=catalog&action=main\">"._CATEGORY."</a></li>
			<li><a href=\"?p=catalog&action=uploadzip\">"._UPLOADZIP."</a></li>
			<li><a href=\"?p=catalog&action=bulkedit\">"._BULKEDIT."</a></li>
		</ul>
	";
}

if ($action=='main') {
	$admintitle=_KATEGORI;
	$catcontent .="<div class=\"content-widgets gray boxappear1\">";
	$catcontent .="<form id=\"fileupload\" class=\"form-horizontal\" name=\"mainform\" method=\"POST\" enctype=\"multipart/form-data\" action=\"index.php?p=catalog&action=baru\">";
	$catcontent .="<div class=\"widget-head bondi-blue grayhead\"><h3>"._ADDCATEGORY."</h3></div>";
	$catcontent .="<div class=\"control-group\"><label class=\"control-label\">"._CATNAME."</label>";
	$catcontent .="<div class=\"controls\"><input type=\"text\" name=\"nama\" id=\"nama\" /></div></div>";
	if ($pakaislug) {
		$catcontent .="<div class=\"control-group\"><label class=\"control-label\">"._SLUGURL."</label>";
		$catcontent .="<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" /></div></div>";
	}
	$catcontent .="<div class=\"control-group\"><label class=\"control-label\">"._SUBPARENT."</label>";
	$catcontent .="<div class=\"controls\">";
	$catcontent .="<select name=\"parent\" id=\"parent\">\n";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);
	
	$catcontent .="<option value=\"0\" >"._TOP."</option>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		$cat_name = $cats->cats[$i]['nama'];
		$catcontent .='<option value="'.$cats->cats[$i]['id'].'"';
		$catcontent .=">$topcatnamecombo";
		
		for ($a=0; $a<count($cats->cat_map); $a++) {
			$cat_parent_id = $cats->cat_map[$a]['id'];
			$cat_parent_name = $cats->cat_map[$a]['nama'];
			$catcontent .=" . . . ";//$cat_parent_name
		}
		$catcontent .="$cat_name</option>";
		
		
	}
	$catcontent .="</select></div></div>\r\n";
	$catcontent .= "<div class=\"control-group\"><label class=\"control-label\">"._THUMBNAIL."</label>";
	
	//upload foto
	ob_start();
	include("tambahfoto.php");					
	$catcontent.=ob_get_clean();
	//end of upload foto
	$catcontent .= "</div>";

	$catcontent .= "<div class=\"control-group\"><div class=\"controls\"><input class=\"buton\" type=\"submit\" name=\"submit\" value=\""._SAVE."\"></div></div>";
	$catcontent .="</div>";
	$catcontent .="</form>";	
	$catcontent .="</div>";	
	$catcontent .="<div id='kategori'>";
	$catcontent .= catstructure('SELECT id, nama, parent FROM catalogcat order by urutan');
	$catcontent .="</div>";
}

// ----------------------------------------------
// PRINT IMAGE UPLOAD FORM
// ----------------------------------------------
if (($action=="add") || ($action=="edit")) {
	ob_start();
	// if ($action=="add" && empty($cat_id)) {
	// pesan(_ERROR,_NOCAT);
	// }
	if ($action=="edit" && empty($pid)) {
		// pesan(_ERROR,_NOPROD);
		$action  =  createmessage(_DBERROR, _ERROR, "error", "");
	}
	$tambahan = '';
	for ($i=0;$i<$additional;$i++) {
		$tambahan .= ", $customfield[$i]";
	}
	
	// if we're editing, get the image details
	if ($action=="edit") {
		$admintitle .= _EDITPRODUCT;
		$sql = "SELECT id, filename, date, title,idmerek, ketsingkat, keterangan, harganormal, diskon,thumb, sku $tambahan $sc_field FROM catalogdata WHERE id='$pid'";

		$getpic = $mysql->query($sql);
		$hasil = $mysql->fetch_array($getpic);
	} else {
		$admintitle .= _ADDPRODUCT;
	}

	
	// if we're adding an image, print the browse box
	if ($action=="edit") {
		if($hasil['filename']!='') {
			$catimg .= '<img src="'.$cfg_thumb_url.'/'.$hasil['filename'].'" '.$imagesize.' alt="'.$hasil['title'].'">';
		} else {
			$catimg .= '<img src="'.$cfg_app_url.'/images/none.gif" border="0" />';
		}
	}
	echo "<div class='catalog_wrapper'>";		
	echo "<form id=\"fileupload\" action=\"$thisfile\" name=\"mainform\" method=\"POST\" enctype=\"multipart/form-data\">\n";
	echo '<input type="hidden" name="pid" value="'.$hasil['id'].'">';
	if ($action=="edit") {
		echo '<input type="hidden" name="action" value="ubahdata">';
	} else {
		echo '<input type="hidden" name="action" value="tambahdata">';
	}
	echo '<div class="catalog_label">'._TITLE.'</div><div><input type="text" name="title" value="'.$hasil['title'].'"></div>';
	if ($pakaislug) {
		echo '<div class="catalog_label">'._SLUGURL.'</div>';
		echo "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$hasil['url']."\"/></div>";
	}
	echo '<div class="catalog_label">'._SKU.'</div><div><input type="text" name="sku" value="'.$hasil['sku'].'"></div>';
	echo '<div class="catalog_label">'._KETERANGAN.'</div><div><textarea cols="60" rows="20" class="usetiny" name="keterangan">'.$hasil['keterangan'].'</textarea></div>';	
	echo '<div class="catalog_label">'._KETSINGKAT.'</div><div><textarea cols="60" rows="20" class="usetiny" name="ketsingkat">'.$hasil['ketsingkat'].'</textarea></div>';	

	echo '<div class="catalog_ishot">';
	foreach($sc as $label => $tname) {
		list($name,$default)=explode(":",$tname);
		if($action=='add') {
			$checked=$default==1?"checked='checked'":"";
		} else {
			$checked=$hasil[$name]==1?"checked='checked'":"";
		}
		echo "<div class=\"$name\" style=\"float:left;\">";
		echo "<label for=\"$name\"><input type=\"checkbox\" name=\"$name\" value=\"1\" $checked /> &nbsp;$label</label>";
		echo "</div>";
	}
	echo "</div>	<!-- /.catalog_ishot -->";	

	echo "<div class='catalog_partial'>";
	
	echo "<div class='catalog_kiri'>";
	
	/**
	* Category
	*/
	echo "<div class='catalog_label'>"._CATEGORY."</div>";
	echo "<div id='catalog_cat'>";
	echo "<select name=\"cat_id\" onchange='tambah_cat(this.value)'>\n";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);
	echo "<option value=\"\" >"._PILIHCATEGORY."</option>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		//cek apakah ada sub cat dalam cat
		$sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
		$query_cek = $mysql->query($sql_cek);
		if ($mysql->num_rows($query_cek)==0){
			$cats->cat_map($cats->cats[$i]['id'],$mycats);
			$cat_name = $cats->cats[$i]['nama'];
			echo '<option value="'.$cats->cats[$i]['id'].'"';
			echo ($cats->cats[$i]['id']==$cat_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
			for ($a=0; $a<count($cats->cat_map); $a++) {
				$cat_parent_id = $cats->cat_map[$a]['id'];
				$cat_parent_name = $cats->cat_map[$a]['nama'];
				echo " / $cat_parent_name";
			}
			echo " / $cat_name</option>";
		}
	}
	echo "<option  class=\"tambah_cat\" value='do_tambah_cat' >"._TAMBAHKATEGORI."</option>\r\n";
	echo "</select>";
	echo "</div>	<!-- /.catalog_cat -->\r\n";

	echo '<div class="catalog_label">'._BRAND.'</div>
	<div id="catalog_brand"><select name="merek" onchange="tambah_brand(this.value)">';
	$result = $mysql->query("SELECT id,nama FROM catalogmerek ORDER BY nama ");
	echo "<option value=\"\" >"._NOBRAND."</option>\r\n";
	while(list($idmerek,$namamerek) = $mysql->fetch_row($result)) {
		$selected = ($idmerek==$hasil['idmerek']) ? 'selected' : '' ;
		echo "<option value=\"$idmerek\" $selected>$namamerek</option>\r\n";
	}
	echo "<option value=\"do_tambah_brand\" class=\"tambah_brand\"  >"._TAMBAHMEREK."</option>\r\n";
	echo"</select></div>\r\n";
	
	echo '<div class="catalog_label">'._NORMALPRICE.'</div><div><input type="text" name="harganormal" value="'.$hasil['harganormal'].'" maxlength="13" size="13"></div>';
	echo '<div class="catalog_label">'._DISCOUNT.'</div><div><input type="text" name="diskon" value="'.$hasil['diskon'].'" maxlength="13" size="13"></div>'."\n";
	for ($i=0;$i<$additional;$i++) {
		switch ($typecustom[$i]) {
			case 'TINYINT':
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="4"></div>'."\n";
				break;
			case 'SMALLINT':
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="6"></div>'."\n";
				break;
			case 'MEDIUMINT':
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="8"></div>'."\n";
				break;
			case 'INT':
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="11"></div>'."\n";
				break;
			case 'DECIMAL': 
				$splitparameter = explode(",",$paracustom[$i]);
				$splitparameter[0] = $splitparameter[0]+1;	//akomodasi tanda minus
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="'.$splitparameter[0].'" size="'.$splitparameter[0].'"></div>'."\n";
				break;
			case 'VARCHAR': 
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="'.$paracustom[$i].'"></div>'."\n";
				break;
			case 'DOLLAR': 
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="9"></div>'."\n";
				break;
			case 'RUPIAH': 
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="13" ></div>'."\n";
				break;
			case 'TEXT':
				$splitparameter = explode(",",$paracustom[$i]);
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><textarea cols="'.$splitparameter[0].'" rows="'.$splitparameter[1].'" class="'.$splitparameter[2].'" name="'.$customfield[$i].'">'.$hasil[$customfield[$i]].'</textarea></div>'."\n";
				break;
			case 'ENUM': 
				$splitparameter = explode("/",$paracustom[$i]);
				$j=0;
				echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><select name="'.$customfield[$i].'">';
				foreach ($splitparameter as $pilihan) {
					echo "<option value=\"$pilihan\"";
					if ($pilihan==$hasil[$customfield[$i]]) echo " selected";
					echo ">$pilihan</option>\n";
				}
				echo "</select>";
				echo "</div>\n";
				break;
			case 'DATE':
				if ($ispickerused) {
					echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input id="'.$customfield[$i].'" type="text" name="'.$customfield[$i].' " value="'.$hasil[$customfield[$i]].'" maxlength="19" onblur="return outsmtr(this.name)" onfocus="return insmtr(this.name)">';
					echo "<a href=\"javascript:NewCal('".$customfield[$i]."','yyyymmdd',false,'24')\"><img height=\"16\" alt=\"Pick a date\" src=\"../images/calendar.gif\" width=\"16\" border=\"0\"></a>";
					echo "</div>\n";
					break;
				} else {
					echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="19"></div>'."\n";
					break;
				}
			case 'DATETIME':
				if ($ispickerused) {
					echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="19" onblur="return outsmtr(this.name)" onfocus="return insmtr(this.name)">';
					echo "<a href=\"javascript:NewCal('".$customfield[$i]."','yyyymmdd',true,'24')\"><img height=\"16\" alt=\"Pick a date\" src=\"../images/calendar.gif\" width=\"16\" border=\"0\"></a>";
					echo "</div>\n";
					break;
				} else {
					echo '<div class="catalog_label">'.$ketcustomfield[$i].'</div><div><input type="text" name="'.$customfield[$i].'" value="'.$hasil[$customfield[$i]].'" maxlength="19"></div>'."\n";
					break;
				}
		}
	}
	
	
	if ($pakaicatalogattribut) {
		echo "<div class='catalog_label'>"._ATTRIBUTTAMBAHAN."</div>";
		if($action=='edit') {
			echo "<div id=\"product_ukuran_group\" style=\"line-height:30px;\">";
			$q=$mysql->query("SELECT id,size,price,color,hexa,urutan FROM catalogattribut WHERE catalog_id='$pid' ORDER BY urutan ASC");
			
			echo '<table class="attribut-price-table">';
			if ($mysql->num_rows($q) == 0) {
				echo '<tr></tr>';
			} else {
				while($d=$mysql->fetch_array($q)) {
					$isRefine = ($d['hexa'] == '') ? '{refine:false}' : '';
					echo '<tr>';
					// echo "<td>"._SIZE."<br><input type=\"text\" name=\"ukuran[]\" value=\"".$d['size']."\" size=\"10\" maxlength=\"20\"></td><td>"._PRICE."<br><input type=\"text\" name=\"prices[]\" value=\"".$d['price']."\" size=\"10\" maxlength=\"20\"></td><!--<td>"._COLOR."<input type=\"text\" name=\"color[]\" value=\"".$d['color']."\" size=\"10\" maxlength=\"20\">--></td><td>"._ORDER."<br><input type=\"text\" name=\"order[]\" value=\"".$d['urutan']."\" size=\"10\" maxlength=\"20\"></td><!--<td>"._HEXA."<input name=\"hexa[]\" class=\"jscolor $isRefine\" value=\"".$d['hexa']."\"></td>--><td><a class=\"remove-attr\" href=\"javascript:void(0)\"><img src=\"images/delete.gif\"></a></td>";
					
					switch($catalog_attribute_option) {
						case 0:
							echo "<td>"._SIZE."<br><input type=\"text\" name=\"ukuran[]\" value=\"".$d['size']."\" size=\"10\" maxlength=\"20\"></td><td>"._COLOR."<br><input type=\"text\" name=\"color[]\" value=\"".$d['color']."\" size=\"10\" maxlength=\"20\"></td><td>"._PRICE."<br><input type=\"text\" name=\"prices[]\" value=\"".$d['price']."\" size=\"10\" maxlength=\"20\"></td><td>"._ORDER."<br><input type=\"text\" name=\"order[]\" value=\"".$d['urutan']."\" size=\"10\" maxlength=\"20\"></td><!--<td>"._HEXA."<input name=\"hexa[]\" class=\"jscolor $isRefine\" value=\"".$d['hexa']."\"></td>--><td><a class=\"remove-attr\" href=\"javascript:void(0)\"><img src=\"images/delete.gif\"></a></td>";
							break;
						case 1:
							echo "<td>"._SIZE."<br><input type=\"text\" name=\"ukuran[]\" value=\"".$d['size']."\" size=\"10\" maxlength=\"20\"></td><td>"._PRICE."<br><input type=\"text\" name=\"prices[]\" value=\"".$d['price']."\" size=\"10\" maxlength=\"20\"></td><td>"._ORDER."<br><input type=\"text\" name=\"order[]\" value=\"".$d['urutan']."\" size=\"10\" maxlength=\"20\"></td><td><a class=\"remove-attr\" href=\"javascript:void(0)\"><img src=\"images/delete.gif\"></a></td>";
							break;
						case 2:
							echo "<td>"._COLOR."<br><input type=\"text\" name=\"color[]\" value=\"".$d['color']."\" size=\"10\" maxlength=\"20\"><td>"._PRICE."<br><input type=\"text\" name=\"prices[]\" value=\"".$d['price']."\" size=\"10\" maxlength=\"20\"></td></td><td>"._ORDER."<br><input type=\"text\" name=\"order[]\" value=\"".$d['urutan']."\" size=\"10\" maxlength=\"20\"></td><td><a class=\"remove-attr\" href=\"javascript:void(0)\"><img src=\"images/delete.gif\"></a></td>";
							break;
					}
					
					echo '</tr>';
				}
			}
			echo '</table>';
			
			echo "</div>";
		} else {
			echo "<div id=\"product_ukuran_group\" style=\"line-height:30px;\"></div>";
			echo '<table class="attribut-price-table span12"><tr></tr>';
			echo '</table>';
		}
		echo "<div class=\"catalog_label\"><a class=\"product_add_ukuran\" href=\"javascript:void(0);\">"._TAMBAHUKURAN."</a> </div><br /><br /><br />";
		echo "</div>";
	}
	
	
	// //ukuran
	// if($prod_attribut==1)
	// {
	// echo "<div class='catalog_label'>"._SIZE."</div>";
	// echo "<div>";
		// if($action=='edit')
		// {
		// echo "<div id=\"product_ukuran_group\" style=\"line-height:30px;\">";
		// $q=$mysql->query("select id,size,stok from attribut where id='$pid' order by id asc");
		
		// while($d=$mysql->fetch_array($q))
		// {
		// echo"<input type=\"text\" name=\"ukuran[]\" value=\"".$d['size']."\" size=\"10\" maxlength=\"20\"><br/>";
		// }
		// // $l_stok : <input type=\"text\" name=\"stok[]\" size=\"2\" value=\"".$d['stok']."\" >
		// echo "</div>";
		// }
		// else
		// {
		// echo "<div id=\"product_ukuran_group\" style=\"line-height:30px;\"></div>";
		// }
		// echo "<a class=\"product_add_ukuran\" href=\"javascript:void(0);\">"._TAMBAHUKURAN."</a> <br /><br /><br />";
	// echo "</div>";
	// }	
	// //end ukuran
	// //attribut tambahan
	// $q=$mysql->query("SELECT * from catalog_atm limit 1");
	// if($q and $mysql->num_rows($q)>0)
	// {
	// //echo "<div class='catalog_label atttribut_separator'>"._ATTRIBUTTAMBAHAN."</div>";
	// echo "<div>";
	// if ($action=="edit") 
	// {
	// attribut_tambahan($hasil['id']);
	// }
	// else
	// {
	// attribut_tambahan();
	// }
	// echo "</div>";
	// }
	// //end attribut tambahan

	//end kiri
	echo "</div>	<!-- /.catalog_kiri -->";
	
	echo "<div class='catalog_kanan'>";
	///kanan
	////////////foto
	echo "<div class='catalog_label'>"._FOTOADD."</div>";
	if($action=='edit') {
		echo "<div id=\"product_foto_group\" style=\"line-height:30px;\">";
		if($hasil['thumb']!='') {
			$thumbr=explode(":",$hasil['thumb']);
			
			foreach($thumbr as $i => $v) {
				$checked="";
				if($hasil['filename']==$v) {
					$checked="checked='checked'";
				}
				echo "
				<div class='catalog_foto_add' id='catalog_foto_add$i'>
					<img src='".$cfg_thumb_url."/".$v."' ".$imagesize." alt='".$hasil['title']."'>
					<input type='hidden' name='list-thumb[]' value='$v'/>
					<a class='catalog_foto_del' data-img='$v' data-id='$pid' data-div='catalog_foto_add$i' ><img alt=\""._DEL."\" border=\"0\" src=\"../images/deletepic.png\"></a>
					<div class='setfotoutama'>
						<!--<input type='radio' id='setfotoutama$i' name='setfotoutama' value='$v' $checked />
						<label class='labelsetutama' for='setfotoutama$i'>Foto utama? </label>
						<div class='tandautama' id='tandautama$i'></div>-->
					</div>	<!-- /.setfotoutama -->
				
				</div>	<!-- /.catalog_foto_add -->
				";
			
			}
		} else {
			//echo"<div><input type=\"file\" name=\"foto_add[]\" size=\"9\" /></div>";
		}
		
		echo "</div>	<!-- /#product_foto_group -->";
	} else {
		echo "<div id=\"product_foto_group\" style=\"line-height:30px;\"></div>	<!-- /#product_foto_group -->";
	}
	
	include "tambahfoto.php";
	echo "</div>	<!-- /.catalog_kanan -->";
	//////////////
	
	//end kanan
	echo "</div>	<!-- /.catalog_kiri -->";	
	echo "</div>	<!-- /.catalog_partial -->";

	echo "<div class='catalog_submit'><input  class=\"buton\" type=\"submit\" value=\""._SAVE."\"><a class=\"buton\" href=\"".param_url("?p=catalog")."\">" . _BACK . "</a></div>	<!-- /.catalog_submit -->";

	echo "</form>\n";
	echo "</div> <!-- /.catalog_wrapper -->\n";

	$catcontent.=ob_get_clean();
}

if ($action=="tambahdata") {
	$sukses=true;
	//if ($_FILES['filename']['error']==UPLOAD_ERR_NO_FILE) pesan(_ERROR,_FILEERROR1);
	if (strlen($_POST['diskon'])>0) {	
		$diskon = trim(str_replace(" ","",$_POST['diskon']));
		
		if(!is_numeric($diskon)) {	
			$poladiskon = "/[0-9\.]%$/";
			if(substr_count($diskon,"%")==1 and preg_match($poladiskon,$diskon)) {	
				if(!is_numeric(trim(str_replace("%","",$diskon)))) {	
					// pesan(_ERROR,_PERSENERROR);
					$action  =  createmessage(_PERSENERROR, _ERROR, "error", "");
					$sukses=false;
				}
			} else {	
				// pesan(_ERROR,_PERSENERROR);
				$sukses=false;
				$action  =  createmessage(_PERSENERROR, _ERROR, "error", "");
			}
		}
	}
	
	$title = checkrequired($title,_TITLE);

	// if(count($ukuran)<=0 and count($stok)<=0)
	// {
	// pesan(_ERROR,_ERRTAMBAHUKURAN);
	// }
	//upload dulu sebelum insert record
	
	if($sukses) {
	
		$tambahan = '';
		$tambahaninsert = '';
		for ($i=0;$i<$additional;$i++) {
			$tambahan .= ", $customfield[$i]";
			$tambahaninsert .= ", '$".$customfield[$i]."'";
		}
		eval ("\$tambahaninsert = \"$tambahaninsert\";");
		if ($pakaislug) {
			$url = $url == '' ? seo_friendly_url($title, 'catalogdata', $pid) : seo_friendly_url($url, 'catalogdata', $pid);
		}
		$sql =	"INSERT INTO catalogdata (cat_id, date, title,idmerek, ketsingkat, keterangan, harganormal, diskon, sku, url $tambahan $sc_field) VALUES ('$cat_id', NOW(), '$title','$merek', '$ketsingkat', '$keterangan', '$harganormal', '$diskon', '$sku', '$url' $tambahaninsert $sc_value)";
	
		if ($mysql->query($sql)) {
			$newid = $mysql->insert_id();
			
			//atribut tambahan
			save_attribut_tambahan($newid);	
			if($prod_attribut==1) {
				//ukuran
				$ukuran=$_POST['ukuran'];
				$stok=$_POST['stok'];
				if(count($ukuran>0) and count($stok)>0) {
					if (!$mysql->query("DELETE FROM attribut WHERE id='$newid'")) $action = createmessage(_DBERROR, _ERROR, "error", "");	//pesan(_ERROR,_DBERROR);
					
					foreach($ukuran as $i =>$u) {
						if(strlen($ukuran[$i])>0 and strlen($stok[$i])>0) {
							if (!$mysql->query("INSERT INTO  attribut(id,size,stok) values ('$newid','".$ukuran[$i]."','".$stok[$i]."')")) pesan(_ERROR,_DBERROR);
						}
					}
				}
			}
			
			$sizes = $_POST['ukuran'];
			$prices = $_POST['prices'];
			$colors = $_POST['color'];
			$hexa = $_POST['hexa'];
			$orders = $_POST['order'];
			if (count($sizes) > 0) {
				foreach($sizes as $k => $ukuran ) {
					// if ($sizes[$k] != '' && $colors[$k] != '') {
						$sql = "INSERT INTO catalogattribut (catalog_id, price, size, color, hexa, urutan) VALUES ('$newid', '{$prices[$k]}','{$sizes[$k]}', '{$colors[$k]}', '{$hexa[$k]}', '{$orders[$k]}')";
						$mysql->query($sql);
					// }
				}
			}
		} else {
			// pesan(_ERROR,_DBERROR);
			$sukses=false;
			$action = createmessage(_DBERROR, _ERROR, "error", "");
		}
	
		if($sukses) {
			///////////foto tambahan
			
			$totalupload=count($_FILES['foto_add']['name']);
			if($totalupload>0) {
				$namathumbnail=array();
				
				$hasilupload = fiestouploadr('foto_add', $cfg_fullsizepics_path,'', $maxfilesize,$newid,$allowedtypes="gif,jpg,jpeg,png");
				if($hasilupload!=_SUCCESS){ $sukses=false;pesan(_ERROR,$hasilupload); }
				
				$thumb_namer=array();	
				$totalupload=count($_FILES['foto_add']['name']);
				for($x=0;$x<=$totalupload;$x++) {
					if($_FILES['foto_add']['name'][$x] != '') {
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
						
						if ($filewidth > $cfg_max_width) {
							//rename sambil resize gambar asli sesuai yang diizinkan
							
							$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_fullsizepics_path/$thumb_name",'w',$cfg_max_width);
							if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

							//del gambar asli
							//unlink("$cfg_fullsizepics_path/".$_FILES['filename']['name']);

						}
						/*
						else {
						//create thumbnail
						$hasilresize = @fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_thumb_path/$thumb_name",'l',$cfg_max_width);
						if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

						}
						*/
					}
				}
				if(count($namathumbnail)>0) {
					$fotoutama=$namathumbnail[0];
					$jthumb_namer=join(":",$namathumbnail);
					$sql =	"UPDATE catalogdata SET filename='$fotoutama',thumb='$jthumb_namer' WHERE id='$newid'";
					if (!$mysql->query($sql)) $action = createmessage(_DBERROR, _ERROR, "error", "");//pesan(_ERROR,_DBERROR);
				}

			}
			//////////end tambahan
			// pesan(_SUCCESS,_DBSUCCESS,"?p=catalog&action=images&cat_id=$cat_id&r=$random");
			$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
			$action="";
		}
	}
}


// ----------------------------------------------
// OVERWRITE OLD IMAGE DATA
// ----------------------------------------------

if ($action=="ubahdata") { 
	if (strlen($_POST['diskon'])>0) {	
		$diskon = trim(str_replace(" ","",$_POST['diskon']));
		
		if(!is_numeric($diskon)) {	
			$poladiskon = "/[0-9\.]%$/";
			if(substr_count($diskon,"%")==1 and preg_match($poladiskon,$diskon)) {	
				if(!is_numeric(trim(str_replace("%","",$diskon)))) {	
					// pesan(_ERROR,_PERSENERROR);
					$action = createmessage(_PERSENERROR, _ERROR, "error", "");
				}
			} else {	
				// pesan(_ERROR,_PERSENERROR);
				$action = createmessage(_PERSENERROR, _ERROR, "error", "");
			}
		}
	}
	$title = checkrequired($title,_TITLE);

	//$tambahan = '';
	$tambahanupdate = '';
	for ($i=0;$i<$additional;$i++) {
		//$tambahan .= ", $customfield[$i]";
		$tambahanupdate .= ", $customfield[$i]='$".$customfield[$i]."'";
	}
	eval ("\$tambahanupdate = \"$tambahanupdate\";");
	if ($pakaislug) {
		$url = $url == '' ? seo_friendly_url($title, 'catalogdata', $pid) : seo_friendly_url($url, 'catalogdata', $pid);
	}
	$sql =	"UPDATE catalogdata SET cat_id='$cat_id', title='$title', idmerek='$merek', ketsingkat='$ketsingkat', keterangan='$keterangan', harganormal='$harganormal', diskon='$diskon', sku='$sku', url='$url' $tambahanupdate $sc_update WHERE id='$pid'";
	
	$result=$mysql->query($sql);
	//atribut tambahan
	save_attribut_tambahan($pid);	
	
	if($prod_attribut==1) {
		//ukuran
		$ukuran=$_POST['ukuran'];
		$stok=$_POST['stok'];
		if(count($ukuran>0) and count($stok)>0) {
			if (!$mysql->query("DELETE FROM attribut WHERE id='$pid'")) pesan(_ERROR,_DBERROR);
		
			foreach($ukuran as $i =>$u) {
				if(strlen($ukuran[$i])>0 and strlen($stok[$i])>0) {
					if (!$mysql->query("INSERT INTO  attribut(id,size,stok) values ('$pid','".$ukuran[$i]."','".$stok[$i]."')")) pesan(_ERROR,_DBERROR);
				}
			}
		}
	}
	
	if ($pakaicatalogattribut) {
		$sizes 	= $_POST['ukuran'];
		$prices = $_POST['prices'];
		$colors = $_POST['color'];
		$hexa 	= $_POST['hexa'];
		$orders = $_POST['order'];
				
		switch($catalog_attribute_option) {
			case 0:
				$attr_post = $sizes;
				break;
			case 1:
				$attr_post = $sizes;
				break;
			case 2:
				$attr_post = $colors;
				break;
		}
		
		if (count($attr_post) > 0) {
			//Jika pakai attribut warna dan ukuran
			$sql = "DELETE FROM catalogattribut WHERE catalog_id='$pid'";
			if ($result = $mysql->query($sql)) {
				
				foreach($attr_post as $k => $value) {
					$sql = "INSERT INTO catalogattribut (catalog_id, price, size, color, hexa, urutan) VALUES ('$pid', '{$prices[$k]}', '{$sizes[$k]}', '{$colors[$k]}', '{$hexa[$k]}', '{$orders[$k]}')";
					$mysql->query($sql);
				}
			}
		}
	}
	
	/* if (count($_POST['ukuran']) > 0) {
		//Jika pakai attribut warna dan ukuran
		$sql = "DELETE FROM catalogattribut WHERE catalog_id='$pid'";
		if ($result = $mysqli->query($sql)) {
			
			$sizes = $_POST['ukuran'];
			$prices = $_POST['prices'];
			$colors = $_POST['color'];
			$hexa = $_POST['hexa'];
			$orders = $_POST['order'];
			foreach($sizes as $k => $ukuran ) {
				// if ($sizes[$k] != '' && $colors[$k] != '') {
					$sql = "INSERT INTO catalogattribut (catalog_id, price, size, color, hexa, urutan) VALUES ('$pid', '{$prices[$k]}', '{$sizes[$k]}', '{$colors[$k]}', '{$hexa[$k]}', '{$orders[$k]}')";
					$mysqli->query($sql);
				// }
			}
		}
	} */
	
	///////////foto tambahan
	$totalupload=count($_FILES['foto_add']['name']);
	if($totalupload>0) {
		$namathumbnail=array();
		$hasilupload = fiestouploadr('foto_add', $cfg_fullsizepics_path,'', $maxfilesize,$pid,$allowedtypes="gif,jpg,jpeg,png");
		if($hasilupload!=_SUCCESS) pesan(_ERROR,$hasilupload);
		
		$totalupload=count($_FILES['foto_add']['name']);
		for($x=0;$x<=$totalupload;$x++) {
			if($_FILES['foto_add']['name'][$x] != '') {
				//ambil informasi basename dan extension
				$temp = explode(".",$_FILES['foto_add']['name'][$x]);
				$extension = $temp[count($temp)-1];
				$basename = '';
				for ($i=0;$i<count($temp)-1;$i++) {
				$basename .= $temp[$i];
			}
			
			$thumb_name=$namathumbnail[$x];
			
			list($filewidth,$fileheight,$filetype,$fileattr) = getimagesize("$cfg_fullsizepics_path/".$thumb_name);

			//create thumbnail
			$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_thumb_path/$thumb_name",'l',$cfg_thumb_width);
			if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

			if ($filewidth > $cfg_max_width) {

				//rename sambil resize gambar asli sesuai yang diizinkan
				$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_fullsizepics_path/$thumb_name",'w',$cfg_max_width);
				if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

				//del gambar asli
				//unlink("$cfg_fullsizepics_path/".$_FILES['filename']['name']);

			}
			/*
				else {
				//create thumbnail
				$hasilresize = fiestoresize("$cfg_fullsizepics_path/$thumb_name","$cfg_thumb_path/$thumb_name",'l',$cfg_thumb_width);
				if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);

				//rename("$cfg_fullsizepics_path/".$_FILES['filename']['name'],"$cfg_fullsizepics_path/$modifiedfilename");
				}
			*/	
			}
		}
		// echo count($namathumbnail);
		// Modified by aly
		if(count($namathumbnail)>0) {
			// //ambil data thumb awal
			// $thumb_awalr=array();
			// $s=$mysql->query("SELECT thumb from catalogdata WHERE id='$pid' AND thumb<>''");
			// if($s and $mysql->num_rows($s)>0)
			// {
				// list($thumb_awal)=$mysql->fetch_row($s);
				// $thumb_awalr=explode(":",$thumb_awal);
			// }
			// //end ambil data thumb awal
			// $comb_thumb=$namathumbnail;
			// if(count($thumb_awalr)>0)
			// {
				// $comb_thumb=array_merge($thumb_awalr, $namathumbnail);
			// }
			// $jthumb_namer=join(":",$comb_thumb);
			
			// $sql =	"UPDATE catalogdata SET thumb='$jthumb_namer' WHERE id='$pid'";
			// if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);
			
			$thumb_awalr = array();
			if (count($_POST['list-thumb']) > 0) {
				$thumb_awalr = $_POST['list-thumb'];
			}
			
			$comb_thumb = $namathumbnail;
			if(count($thumb_awalr)>0) {
				$comb_thumb = array_merge($thumb_awalr, $namathumbnail);
			}
			$jthumb_namer=join(":",$comb_thumb);
			$setfotoutama = $comb_thumb[0];
			$sql =	"UPDATE catalogdata SET filename='$setfotoutama', thumb='$jthumb_namer' WHERE id='$pid'";
			if (!$mysql->query($sql)) $action = createmessage(_DBERROR, _ERROR, "error", "");//pesan(_ERROR,_DBERROR);
		} else {
			
			$thumb_awalr = array();
			if (count($_POST['list-thumb']) > 0) {
				
				$thumb_awalr = $_POST['list-thumb'];
			}
			$jthumb_namer=join(":",$thumb_awalr);
			$setfotoutama = $thumb_awalr[0];
			
			$sql =	"UPDATE catalogdata SET filename='$setfotoutama', thumb='$jthumb_namer' WHERE id='$pid'";
			if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);
		}
		// End of modified by aly
		
	}
	
	//////////end tambahan
	//SET FOTO UTAMA
	/* $setfotoutama=$_POST['setfotoutama'];
	if($setfotoutama!='')
	{
	$sql =	"UPDATE catalogdata SET filename='$setfotoutama' WHERE id='$pid'";
	if (!$mysql->query($sql)) $action = createmessage(_DBERROR, _ERROR, "error", "");	
	} */
	//
	if ($mysql->query($sql)) {
		$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");	
	} else {
		$action = createmessage(_DBERROR, _ERROR, "error", "");	
	}
}


// ----------------------------------------------
// SHOW THUMBNAILS
// ----------------------------------------------
if ($action=="images") {
	if (empty($cat_id)) {
		pesan(_ERROR,_NOCAT);
	}

	$sql = "SELECT id FROM catalogcat WHERE id=$cat_id";
	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$mysql->free_result($result);

	if ($total_records==0) {
		pesan(_ERROR,_NOCAT);
	}

	$sql = "SELECT id, nama FROM catalogcat WHERE parent='$cat_id' ORDER BY urutan ";
	$result = $mysql->query($sql);
	$total_subcat = $mysql->num_rows($result);
	if ($total_subcat > 0) 
	{
		//$catsubcat = "<div class=\"titleofsubcatlist\">"._SUBCAT."</div>\n";
		$catsubcat .= "<ul>\n";
		while(list($catsubcat_id, $catsubcat_name) = $mysql->fetch_row($result)) {
			$catsubcat .= "<li><a href=\"?p=catalog&action=images&cat_id=$catsubcat_id\">$catsubcat_name</a> ";
			$catsubcat .= "<a href=\"?p=catalog&action=images&cat_id=$catsubcat_id\"><img alt=\""._OPEN."\" border=\"0\" src=\"../images/open.gif\"></a>\n";
			$catsubcat .= "<a href=\"?p=catalog&action=catedit&cat_id=".$catsubcat_id."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a> ";
			$catsubcat .= "<a href=\"?p=catalog&action=catdel&cat_id=".$catsubcat_id."\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></li>\n";
		}
		$catsubcat .= "</ul>\n";
		
		$specialadmin .= "<p><a class=\"buton\" href=\"?p=catalog&action=catnew&parent=$cat_id\">"._ADDSUBCAT."</a></p>";
	}
	
	$cats = new categories();
	$mycats = array();
	$sql = "SELECT id, nama, parent, description FROM catalogcat ORDER BY urutan ";
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'description'=>$row['description'],'level'=>0);
	}
	$cats->get_cats($mycats);
	$mysql->free_result($result);

	$catnav = "<a href=\"?p=catalog&action=main\">$topcatnamenav</a>";
	for ($i=0; $i<count($cats->cats); $i++) {
		$cats->cat_map($cats->cats[$i]['id'],$mycats);
		if ($cats->cats[$i]['id'] == $cat_id) {
			$catdesc = $cats->cats[$i]['description'];
			for ($a=0; $a<count($cats->cat_map); $a++) {
				$cat_parent_id = $cats->cat_map[$a]['id'];
				$cat_parent_name = $cats->cat_map[$a]['nama'];
				$catnav .= "$separatorstyle<a href=\"?p=catalog&action=images&cat_id=$cat_parent_id\">$cat_parent_name</a>";
			}
			$catnav .= $separatorstyle.$cats->cats[$i]['nama'];
		}
	}

	// hitung dulu untuk menentukan berapa halaman...
	$sql = "SELECT id FROM catalogdata WHERE cat_id='$cat_id'";
	
	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$pages = ceil($total_records/$max_page_list);

	// get the image information
	if ($screen=='')
	{
		$screen = 0;
	}
	$start = $screen * $max_page_list;
	//jun 19:35 10/01/2012 lama gak pake $sort
	//$sql = "SELECT id, cat_id, filename, title, publish, ishot FROM catalogdata WHERE cat_id='$cat_id' ORDER BY cat_id ASC, date DESC, title ASC LIMIT $start, $max_page_list";
	$sql = "SELECT id, cat_id, filename, title, publish, ishot, ketsingkat, harganormal, diskon, matauang, 
			if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),diskon) 
			as nilaidiskon,
			harganormal-(if(LOCATE('%',diskon)>0,(TRIM(REPLACE(diskon,'%',''))/100*harganormal),
				diskon)) as hargadiskon 
	FROM catalogdata";	//jun masalah
	if (!empty($cat_id)) {
		$sql .= " WHERE cat_id='$cat_id' ";
	} else {
		$sql .= " ";
	}
	$sql .= " ORDER BY  ".$sort;
	$sql .= " LIMIT $start, $max_page_list";
	

	$photo = $mysql->query($sql);
	$total_images=$mysql->num_rows($photo);

	if ($total_images=="0") {	
		if($total_subcat == 0) {	
			$catcontent .= "<p>"._NOPROD."</p>";
			$specialadmin .= "<p><a class=\"buton\" href=\"?p=catalog&action=catnew&parent=$cat_id\">"._ADDSUBCAT."</a> <a class=\"buton\" href=\"?p=catalog&action=add&cat_id=$cat_id\">"._ADDPRODUCT."</a></p>";
		}
	} else {
		if ($pages>1) $adminpagination = pagination($namamodul,$screen,"cat_id=$cat_id&action=images");
		$catcontent .= show_me_the_images(true);
		$specialadmin .= "<p><a class=\"buton\" href=\"?p=catalog&action=add&cat_id=$cat_id\">"._ADDPRODUCT."</a></p>";
	}
}

// ----------------------------------------------
// DELETE IMAGE
// ----------------------------------------------
if (($action=="delete") || ($action=="confirm_delete")) {

	$admintitle = _DELPROD;
	if (empty($pid)) {
		pesan(_ERROR,_NOPROD);
	}
	$catcontent = '';
	if ($action=="delete") {
		$catcontent .= _PROMPTDEL;
		$catcontent .= "<a class=\"buton\" href=\"".param_url("?p=catalog&pid=$pid&action=confirm_delete&cat_id=$cat_id")."\">"._YES."</a>&nbsp;<a href=\"".param_url("?p=catalog")."\" class=\"buton\" >"._NO."</a></p>";
	} elseif ($action=="confirm_delete") {
		$sql = "SELECT cat_id,filename,thumb FROM catalogdata WHERE id='$pid'";
		$getfilename=$mysql->query($sql);
		list($cat_id,$filename,$thumb) = $mysql->fetch_row($getfilename); 
		if ($filename!='' && file_exists("$cfg_fullsizepics_path/$filename")) @unlink("$cfg_fullsizepics_path/$filename");
		if ($filename!='' && file_exists("$cfg_thumb_path/$filename")) @unlink("$cfg_thumb_path/$filename");
		
		if($thumb!='') {
			$dthumb=explode(":",$thumb);
			if(count($dthumb)>0) {
				foreach($dthumb as $i => $v) {
					if(file_exists("$cfg_fullsizepics_path/$v")) @unlink("$cfg_fullsizepics_path/$v");
					if(file_exists("$cfg_thumb_path/$v")) @unlink("$cfg_thumb_path/$v");
				}
			}
		}
		
		$sql = "DELETE FROM catalogdata WHERE id='$pid'";
		if ($mysql->query($sql)) {
			if (!$mysql->query("DELETE FROM catalogattribut WHERE catalog_id='$pid'")) pesan(_ERROR,_DBERROR);
			$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");	
		} else {
			$action = createmessage(_DBERROR, _ERROR, "error", "");
		}
	}
	
}


// ----------------------------------------------
// ADD CATEGORY
// ----------------------------------------------
if ($action == "catnew") {
	if (empty($parent)) $parent=0;
	$admintitle .= ($parent==0) ? _ADDMAINCAT : _ADDSUBCAT;
	$catcontent .= "<form method=\"post\" action=\"$thisfile\">";
	$catcontent .= "<table border=\"0\"><tr><td>"._CATNAME.":</td><td><input type=\"text\" name=\"nama\" value=\"$cat_name\"></td></tr>\n";
	if ($pakaislug) {
		$catcontent .= "<tr><td>"._SLUGURL.":</td><td><input type=\"text\" name=\"url\"></td></tr>\n";
	}
	/*
	$catcontent .= "<tr><td>"._CATDESC.":</td><td><textarea cols=\"60\" rows=\"10\" class=\"usetiny\" name=\"cat_desc\">$cat_desc</textarea></td></tr>\n";
	*/
	$sql =	"SELECT nama FROM catalogcat where id='$parent'";
	$catlist = $mysql->query($sql);
	list($catlist_name) = $mysql->fetch_row($catlist);
	$catcontent .= "<input type=\"hidden\" name=\"parent\" value=\"$parent\"\n>";
	if ($parent!=0) $catcontent .= "<tr><td>"._SUBCAT.":</td><td>$catlist_name</td></tr>\n";
	
	$catcontent .= "<tr><td>"._ORDER.":</td><td>";
	$catcontent .= "<div id=\"urutancontent\">";
	$catcontent .= createurutan("catalogcat", $parent, $cat_id);
	$catcontent .= "</div>";
	$catcontent .= "</td></tr>\r\n";
	
	$mysql->free_result($catlist);

	$catcontent .= "<tr><td>&nbsp;</td><td>";
	if (!empty($cat_id)) {
		$catcontent .= "<input type=\"hidden\" name=\"action\" value=\"ubah\">";
		$catcontent .= "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">";
		$catcontent .= "<input type=\"submit\" name=\"submit\" value=\""._SAVE."\">";
	} elseif (empty($cat_id)) {
		$catcontent .= "<input type=\"hidden\" name=\"action\" value=\"baru\">";
		$catcontent .= "<input type=\"submit\" name=\"submit\" value=\""._SAVE."\">";
	}
	$catcontent .= "</td></tr>\n";

	$catcontent .= "</tr></table>";
	$catcontent .= "</form>";
}

// ----------------------------------------------
// EDIT CATEGORY
// ----------------------------------------------

if ($action == "catedit") {

	if (empty($cat_id)) {
		pesan(_ERROR,_NOCAT);
	} else {

		$admintitle .= _EDITCAT;
		$sql =	"SELECT nama, parent, description, url FROM catalogcat where id='$cat_id'";
		$cat = $mysql->query($sql);
		list($cat_name, $cat_parent_id,$cat_desc, $url) = $mysql->fetch_row($cat);
		$mysql->free_result($cat);
		$cat_name=preg_replace('/"/','&quot;',$cat_name);
		
		$catcontent .= "<form id=\"fileupload\" class=\"form-horizontal\" action=\"$thisfile\" name=\"mainform\" method=\"POST\" enctype=\"multipart/form-data\">\n";
		//$catcontent .= "<form class=\"form-horizontal\" method=\"POST\" action=\"$thisfile\" enctype=\"multipart/form-data\">";
		$catcontent .= "<div class=\"control-group\"><label class=\"control-label\">"._CATNAME."</label><div class=\"controls\"><input type=\"text\" name=\"nama\" value=\"$cat_name\"></div></div>\n";
		if ($pakaislug) {
			$catcontent .= "<div class=\"control-group\"><label class=\"control-label\">"._SLUGURL."</label><div class=\"controls\"><input type=\"text\" name=\"url\" value=\"$url\"></div></div>\n";
		}
		//	$catcontent .= "<tr><td>"._CATDESC."</td><td><textarea cols=\"60\" rows=\"10\" class=\"usetiny\" name=\"cat_desc\">$cat_desc</textarea></td></tr>\n";
		
		$urlajax = $absolutadminurl."/ajax.php?p=catalogcat&action=urutan";
		if($action == "catedit") {
			$urlajax .= "&pid=".$cat_id;
		}
		$eventurutan = "onchange=\"ajaxpage('".$urlajax."&parent='+this.value, 'urutancontent')\" ";
		
		$catcontent .= "<div class=\"control-group\"><label class=\"control-label\">"._SUBCAT."</label><div class=\"controls\">";
		$catcontent .= "<select name=\"parent\" $eventurutan >\n";

		$cats = new categories();
		$mycats = array();
		$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
		$result = $mysql->query($sql);
		while($row = $mysql->fetch_array($result)) {
			$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
		}
		$cats->get_cats($mycats);
		$mysql->free_result($result);

		$catcontent .="<option value=\"0\" >"._TOP."</option>\r\n";
		for ($i=0; $i<count($cats->cats); $i++) {
			//cek
			$sql_cek = "SELECT * FROM catalogdata WHERE cat_id ='".$cats->cats[$i]['id']."'";
			$query_cek = $mysql->query($sql_cek);
			if ($mysql->num_rows($query_cek)==0){
				$isallowed=true;
				$cats->cat_map($cats->cats[$i]['id'],$mycats);
				$cat_name = $cats->cats[$i]['nama'];
				$catcontenttemp = '<option value="'.$cats->cats[$i]['id'].'"';
				$catcontenttemp .= ($cats->cats[$i]['id']==$cat_parent_id) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
				for ($a=0; $a<count($cats->cat_map); $a++) {
					if ($cats->cat_map[$a]['id']!=$cat_id) {
						if ($isallowed) $catcontenttemp .= " . . . . ";//.$cats->cat_map[$a]['nama'];
					} else {
						$catcontenttemp = "";
						$isallowed=false;
					}
				}
				if ($cats->cats[$i]['id']!=$cat_id) {
					if ($isallowed) $catcontenttemp .= "$cat_name</option>";
				} else {
					$catcontenttemp = "";
					$isallowed=false;
				}
				$catcontent .= $catcontenttemp;
			}
		}

		$catcontent .= "</select></div></div>\n";
		
		$catcontent .= "<div class=\"control-group\"><label class=\"control-label\">"._ORDER."</label><div class=\"controls\">";
		$catcontent .= "<div id=\"urutancontent\">";
		$catcontent .= createurutan("catalogcat", $cat_parent_id, $cat_id);
		$catcontent .= "</div>";
		$catcontent .= "</div></div>\r\n";
		
		$catcontent .= "<div class=\"control-group\"><label class=\"control-label\">"._THUMBNAIL."<br>"._FRMPICEDITNOTE."</label><div class=\"controls\">";
		$catcontent .= "<div id=\"urutancontent\">";
		$sql = "SELECT filename, nama FROM catalogcat WHERE id='".$cat_id."'";
		$query_result = $mysql->query($sql);
		$hasil = $mysql->fetch_assoc($query_result);
		if($hasil['filename']!='') {
			$catcontent .= '<img src="'.$cfg_thumb_url.'/'.$hasil['filename'].'" '.$imagesize.' alt="'.$hasil['nama'].'">';
		} else {
			$catcontent .= '<img src="'.$cfg_app_url.'/images/none.gif" border="0" />';
		}
		$mysql->free_result($query_result);
		$catcontent .= "</div>";
		$catcontent .= "</div></div>\r\n";
		$catcontent .= "<div class=\"control-group\">";
		
		//upload foto
		ob_start();
		include("tambahfoto.php");					
		$catcontent.=ob_get_clean();
		//end of upload foto
		$catcontent .= "</div>\r\n";

		$catcontent .= "<div class=\"control-group\"><div class=\"controls\">";
		if (!empty($cat_id)) {
			$catcontent .= "<input type=\"hidden\" name=\"action\" value=\"ubah\">";
			$catcontent .= "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">";
			$catcontent .= "<input type=\"submit\" name=\"submit\" class=\"buton\" value=\""._SAVE."\">";
		} elseif (empty($cat_id)) {
			$catcontent .= "<input type=\"hidden\" name=\"action\" value=\"baru\">";
			$catcontent .= "<input type=\"submit\" name=\"submit\" class=\"buton\" value=\""._SAVE."\">";
		}
		$catcontent .= "</div></div>\n";

		$catcontent .= "</div>";
		$catcontent .= "</form>";
	}
}

if ($action=="uploadzip") {
	$admintitle .= _UPLOADZIP;
	$catcontent .= "<form action=\"$thisfile\" name=\"mainform\" method=\"POST\" enctype=\"multipart/form-data\">\r\n";
	
	$catcontent .= "<select name=\"cat_id\">\r\n";
	$catcontent .= leastcatoption($cat_id);
	$catcontent .= "</select>\r\n";		
	
	$catcontent .= "<p><input type=\"file\" name=\"filename\" size=\"35\"></p>\r\n";
	$catcontent .= "<input type=\"hidden\" name=\"action\" value=\"unzip\">\r\n";
	$catcontent .= "<p><input type=\"submit\" value=\""._UPLOAD."\"></p>\r\n";
	$catcontent .= "</form>\r\n";
}

if ($action=="unzip") {
	checkrequired($cat_id, _CATEGORY);
	//del semua file sampah
	if ($handle = opendir($cfg_temp_unzip_path)) {
		while (false !== ($file = readdir($handle))) { 
			if ($file != "." && $file != "..") { 
				unlink("$cfg_temp_unzip_path/$file");
			}
		}
		closedir($handle); 
	}
		
	//upload file zip
	$hasilupload = fiestoupload('filename',$cfg_temp_unzip_path,'',10000000,$allowedtypes="zip");
	if ($hasilupload!=_SUCCESS) pesan(_ERROR,$hasilupload);
	
	//extract hanya file gambar yang di root saja (abaikan folder dan file2 di dalamnya)
	$zip = new ZipArchive;
	if ($zip->open($cfg_temp_unzip_path.'/'.$_FILES['filename']['name']) === TRUE) {
		for ($i=0; $i<$zip->numFiles;$i++) {
			$zippedfile = $zip->statIndex($i);
			$pos = strpos($zippedfile['name'],'/');
			if ($pos === false) {
				$temp = explode('.',$zippedfile['name']);
				$extension = strtolower($temp[count($temp)-1]);
				if ($extension=="gif" || $extension=="jpg" || $extension=="jpeg" || $extension=="png") $zip->extractTo($cfg_temp_unzip_path,$zippedfile['name']);
			}		
		}
		$zip->close();
	}
	
	//del file zip yang sudah di-extract
	unlink($cfg_temp_unzip_path.'/'.$_FILES['filename']['name']);
	
	//baca semua file di folder $cfg_temp_unzip_path. jika file gambar, masukkan database dan folder yang benar. jika bukan file gambar, del.
	if ($handle = opendir($cfg_temp_unzip_path)) {
		while (false !== ($file = readdir($handle))) { 
			if ($file != "." && $file != "..") { 
				$size = getimagesize("$cfg_temp_unzip_path/$file");
				if ($size==FALSE) {
					unlink("$cfg_temp_unzip_path/$file");
				} else {

					$temp = explode('.',$file);
					$extension = strtolower($temp[count($temp)-1]);
					$basename = '';
					for ($j=0;$j<count($temp)-1;$j++) {
						$basename .= $temp[$j];
					}
				
					$sql = "INSERT INTO catalogdata (cat_id, date, title, publish, ishot) VALUES ('$cat_id', NOW(), '$basename', '1', '0')";
					if ($mysql->query($sql)) {
						$newid = $mysql->insert_id();
						$modifiedfilename = "$basename-$newid.$extension";
						$sql =	"UPDATE catalogdata SET filename='$modifiedfilename' WHERE id='$newid'";
						if (!$mysql->query($sql)) pesan(_ERROR,_DBERROR);

					} else {
						pesan(_ERROR,_DBERROR);
					}
					
					//create thumbnail
					$hasilresize = fiestoresize("$cfg_temp_unzip_path/$file","$cfg_thumb_path/$modifiedfilename",'l',$cfg_thumb_width);
					if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);
					
					if ($size[0] > $cfg_max_width) {
						//rename sambil resize gambar asli sesuai yang diizinkan
						$hasilresize = fiestoresize("$cfg_temp_unzip_path/$file","$cfg_fullsizepics_path/$modifiedfilename",'w',$cfg_max_width);
						if ($hasilresize!=_SUCCESS) pesan(_ERROR,$hasilresize);
							
						//del gambar asli
						unlink("$cfg_temp_unzip_path/$file");
					} else {
						rename("$cfg_temp_unzip_path/$file","$cfg_fullsizepics_path/$modifiedfilename");
					}

				}
			} 
		}
		closedir($handle); 
	}
	
	$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
}

if ($action=='bulkedit') {
	$admintitle .= _BULKEDIT;
	if ($_POST['submit']==_SAVE) {
	
		for ($i=0;$i<count($_POST['bulkid']);$i++) {
			$published = ($_POST['bulkpublished'][$i]=='on') ? 1 : 0 ;
			$sc_field=array();
			
			foreach($sc1 as $label =>$name) {
				$sc_field[]="$name='".($_POST["$name"][$i]=="1"?"1":"0")."'";
			}
			$sc_field=",".join(",",$sc_field);
		
			if (!$mysql->query("UPDATE catalogdata SET title='".addslashes($_POST['bulktitle'][$i])."', weight='".$_POST['bulkweight'][$i]."', harganormal='".$_POST['bulkharganormal'][$i]."', diskon='".$_POST['bulkdiskon'][$i]."', ishot='".$_POST['bulkishot'][$i]."', publish='$published' $sc_field  WHERE id='".$_POST['bulkid'][$i]."'")) pesan(_ERROR,_DBERROR);	//jun masalah
		}
		// pesan(_SUCCESS,_DBSUCCESS);
		$action = createmessage(_DBSUCCESS, _SUCCESS, "success", "");
	} else {
		$catcontent .= "<form method=\"GET\">\r\n";
		$catcontent .= '
		<script language="JavaScript">
		<!--
		function MM_jumpMenu(targ,selObj,restore) { //v3.0
			if (selObj.options[selObj.selectedIndex].value=="") {
				eval(targ+".location=\'?p=catalog&action=bulkedit\'");
			} else {
				eval(targ+".location=\'?p=catalog&action=bulkedit&cat_id="+selObj.options[selObj.selectedIndex].value+"\'");
			}
			if (restore) selObj.selectedIndex=0;
		}
		//-->
		</script>
		';

		$catcontent .= "<select name=\"cat_id\" onChange=\"MM_jumpMenu('parent',this,0)\">\r\n";
		$catcontent .= leastcatoption($cat_id);
		$catcontent .= "</select>\r\n";		
		$catcontent .= "</form><br />\r\n";		
	
		if ($cat_id!='') {
			$sc_field=array();
			
			foreach($sc1 as $label =>$name) {
				$sc_field[]=$name;
			}
			$sc_field=",".join(",",$sc_field);

			$sql = "SELECT id, title, weight, publish, ishot, harganormal, diskon,filename $sc_field FROM catalogdata WHERE cat_id='$cat_id' ORDER BY date desc, title";	//jun masalah
			$result = $mysql->query($sql);
			$catcontent .= "<form action=\"?p=catalog&action=bulkedit\" method=\"POST\">\r\n";
			$catcontent .= "<table class=\"list\" cellpadding=\"6\" border=\"1\">\r\n";
			$catcontent .= "<tr><th>No</th><th>"._THUMBNAIL."</th><th>"._TITLE."</th><th>"._WEIGHT."</th>";
			
			//<th>"._NORMALPRICE."</th><th>"._DISCOUNT."</th><th>"._SPECIALCASE."</th>
			
			foreach($sc1 as $label =>$name) {
				$matches = array();
				$num_matched = preg_match_all('/\((.*)\)/U', $label, $matches);
				$catcontent .="<th title='$label'>".$matches[1][0]."<br><input type=\"checkbox\" id=\"c$name\"></th>";
			}
			
			$catcontent .="<th>"._PUBLISHED."<br><input type=\"checkbox\" id=\"cpublish\"></th></tr>";
			$i=0;
			while ($d= $mysql->fetch_assoc($result)) {
				
				//////////generate variable sesuai nama field
				foreach($d as $f=>$v){$$f=$v;}
				///////////
				$catcontent .= "<tr>\r\n";
				$catcontent .= "<td><input type=\"hidden\" name=\"bulkid[$i]\" value=\"$id\" />".($i+1)."</td>\r\n";
				$catcontent .= "<td><img src=\"$cfg_thumb_url/$filename\" height=\"80px\"/></td>\r\n";
				$catcontent .= "<td><input type=\"text\" name=\"bulktitle[$i]\" value=\"$title\" /></td>\r\n";
				$catcontent .= "<td><input type=\"text\" name=\"bulkweight[$i]\" value=\"$weight\" style=\"text-align:right\" size=\"5\" /></td>\r\n";
				
				foreach($sc1 as $label =>$name) {

					$catcontent .= "<td>";
					$checked=$$name=="1"?"checked='checked'":"";
					$catcontent .= "<input type='checkbox' name='{$name}[$i]' value='1' $checked /> ";
					$catcontent .= "</td>";
				}

		
				$checked = ($publish) ? 'checked' : '' ;
				$catcontent .= "<td align=\"center\"><input type=\"checkbox\" name=\"bulkpublished[$i]\" value=\"on\" $checked /></td>\r\n";
				$catcontent .= "</tr>\r\n";
				$i++;
			}
			$catcontent .= "</table>\r\n";
			$catcontent .= "<p><input type=\"submit\" name=\"submit\"  class=\"buton\" value=\""._SAVE."\" /></p>\r\n";
			$catcontent .= "</form>\r\n";
		}
	}
}

if ($action=='list' or $action==''  or $action=='search') {
	/////////////////////
	$searchbycat="<select name=\"searchbycat\" id=\"searchbycat\" onchange=\"document.getElementById('searchbybrand').selectedIndex=0;document.form_product_cat.submit()\">\n";
	$cats = new categories();
	$mycats = array();
	$sql = 'SELECT id, nama, parent FROM catalogcat ORDER BY urutan ';
	$result = $mysql->query($sql);
	while($row = $mysql->fetch_array($result)) {
		$mycats[] = array('id'=>$row['id'],'nama'=>$row['nama'],'parent'=>$row['parent'],'level'=>0);
	}
	$cats->get_cats($mycats);
	$searchbycat.="<option value=\"\" >"._ALLCATEGORY."</option>\r\n";
	for ($i=0; $i<count($cats->cats); $i++) {
		//cek apakah ada sub cat dalam cat
		$sql_cek = "SELECT * FROM catalogcat WHERE parent='".$cats->cats[$i]['id']."'";
		$query_cek = $mysql->query($sql_cek);
		if ($mysql->num_rows($query_cek)==0){
			$cats->cat_map($cats->cats[$i]['id'],$mycats);
			$cat_name = $cats->cats[$i]['nama'];
			$searchbycat.= '<option value="'.$cats->cats[$i]['id'].'"';
			$searchbycat.= ($cats->cats[$i]['id']==$_GET['searchbycat']) ? " selected='selected'>$topcatnamecombo" : ">$topcatnamecombo";
			for ($a=0; $a<count($cats->cat_map); $a++) {
				$cat_parent_id = $cats->cat_map[$a]['id'];
				$cat_parent_name = $cats->cat_map[$a]['nama'];
				$searchbycat.= " / $cat_parent_name";
			}
			$searchbycat.=" / $cat_name</option>";
		}
		
	}
	$searchbycat.="</select>\r\n";	
	
	////////////////////
	$sql ='SELECT id,nama FROM catalogmerek ';
	$result=$mysql->query($sql);

	$searchbymerek="<select name=\"searchbybrand\"  onchange=\" document.getElementById('searchbycat').selectedIndex=0;document.form_product_brand.submit();\" id=\"searchbybrand\">\n";
	$searchbymerek.="<option value=''>"._ALLBRAND."</option>";
	if($result and $mysql->num_rows($result)>0) {
		while(list($id,$nama)=$mysql->fetch_row($result)) {
			$selectedbrand=$_GET['searchbybrand']==$id?"selected='selected'":"";
			$searchbymerek.="<option value='$id' $selectedbrand>$nama</option>";
		}
	}
	$searchbymerek.="</select>\n";

	$adminsearch.="
	<form name=\"form_product_cat\" action=\"$thisfile\" method=\"GET\" class=\"form-vertical barisan\">
	<input type=\"hidden\" value=\"catalog\" name=\"p\">
	".param_input()."
	<div class=\"control-group\">
		<div class=\"controls\">$searchbycat</div>
	</div>
	</form>
	<form name=\"form_product_brand\" action=\"$thisfile\" method=\"GET\" class=\"form-vertical barisan\">
	<input type=\"hidden\" value=\"catalog\" name=\"p\">
	".param_input()."
	<div class=\"control-group\">
		<div class=\"controls\">$searchbymerek</div>
	</div>
	</form>
	<p><a class=\"buton\" href=\"".param_url("?p=catalog&action=add")."\">"._ADDPRODUCT."</a></p>";

	$admintitle=_LISTPRODUCT;
	ob_start();
	$keyword=strip_tags($keyword);
	$r_keyword=preg_split("/[\s,]+/",$keyword);

	$sc_field="";
	if(count($sc1)>0) {
		foreach($sc1 as $label =>$name) {
			$sc_field[]=$name;
		}
		$sc_field=",".join(",",$sc_field);
	}


	$sql  = "SELECT id,cat_id,filename,title,date,keterangan,harganormal,diskon,publish $sc_field from catalogdata WHERE  ";
	foreach ($r_keyword as $j=>$splitkeyword) {
		$sql .= ($j==0) ? "" : " AND ";
		$sql .= " title LIKE '%$splitkeyword%'";
	}
	
	
	foreach ($searchedcf as $fieldygdisearch) {
		$sql .= " OR (";
		foreach ($r_keyword as $j=>$splitkeyword) {
			$sql .= ($j==0) ? "" : " AND ";
			$sql .= "$fieldygdisearch LIKE '%$splitkeyword%'";
		}
		$sql .= ")";
	}
		
	if($_GET['searchbybrand']!='') {
		$idmerek=$_GET['searchbybrand'];
		$sql.=" AND idmerek=$idmerek ";
	}
	if($_GET['searchbycat']!='') {
		$idcat=$_GET['searchbycat'];
		$sql.=" AND cat_id=$idcat ";
	}
	
	$sql .=" order by date desc,title  ";	
	// echo $sql;
	$result = $mysql->query($sql);
	$total_records = $mysql->num_rows($result);
	$pages = ceil($total_records/$max_page_list);
	if ($mysql->num_rows($result) == 0) {
		echo "<p>"._NOPRODUCT."</p>";
	} else {
	
		//menampilkan record di halaman yang sesuai
		
		if ($pages>1) $adminpagination = pagination($namamodul,$screen,"action=list&keyword=$keyword&searchbycat=$idcat&searchbybrand=$idmerek");
		$start = $screen * $max_page_list;
		$sql.=" limit $start,$max_page_list";	
		$res = $mysql->query($sql);	
		echo "<table class='stat-table table table-stats table-striped table-sortable table-bordered table-product'>";
		echo "<tr class='produkku'>";
		echo "<th>"._NUM."</th>";
		echo "<th>"._THUMBNAIL."</th>";
		echo "<th>"._PRODUCT."</th>";
		echo "<th>"._HARGAJUAL."</th>";
		echo "<th>"._DISKON."</th>";
		echo "<th title='"._PUBLISHED."'><div class='box-publish'>T</div><div class='box-publish-input'><input type=\"checkbox\"  data-type='publish' id=\"cpublish\" ></div></th>";
		foreach($sc1 as $label =>$name) {
			$matches = array();
			$num_matched = preg_match_all('/\((.*)\)/U', $label, $matches);
			echo "<th title='$label'><div class='box-$name'>".$matches[1][0]."<div class='box-$name-input'><input type=\"checkbox\"  data-type='$name' id=\"c$name\" /></div></th>";
		}
		echo "<th>"._ACTION."</th>";
		echo "</tr>";
		if($res and $mysql->num_rows($res)>0) {
			$no=$start+1;
			while($d=$mysql->fetch_assoc($res)) {
				$pid=$d['id'];
				$cat_id=$d['cat_id'];
				echo "<tr>";
				echo "<td align=center>$no</td>";
				echo "<td><img class='thumb_small' src='$cfg_thumb_url/".$d['filename']."' /></td>";
				echo "<td align='left'><a href='#' data-pk='$pid' class='nama-produk'>".$d['title']."</a></td>";
				echo "<td align='right'><a href='#' data-pk='$pid' class='harga-produk'>".number_format($d['harganormal'],0,',','.')."</a></td>";
				if(strpos($d['diskon'],'%') and $d['diskon']!='') {
					$temp=str_replace("%","",$d['diskon']);
					$d['diskon']=number_format($d['diskon'],0,',','.')."%";
				} else {
					if($d['diskon']!==""){$d['diskon']=number_format($d['diskon'],0,',','.');}
				}

				echo "<td align='right'><a href='#' data-pk='$pid' class='diskon-produk'>".$d['diskon']."</a></td>";			

				$tampilkanchecked=$d['publish']==1?"checked='checked'":"";
				$tampilkan="<input type='checkbox'  data-type='publish' data-id='$pid' name='publish[$i]' class='publish' $tampilkanchecked />";
				echo "<td class='tampilkan'>$tampilkan</td>";	
				foreach($sc1 as $label =>$name) {
					$$name=$d[$name];
					echo "<td class='$name'>";
					$checked=$$name=="1"?"checked='checked'":"";
					echo "<input class='$name' type='checkbox' data-id='$pid'  data-type='$name' name='{$name}[$i]' value='1' $checked /> ";
					echo "</td>";
				}

				echo "<td><a href=\"".param_url("?p=catalog&pid=$pid&action=edit&cat_id=$cat_id&keyword=$keyword&screen=$screen")."\"><img alt=\"Edit\" border=\"0\" src=\"../images/modify.gif\"></a>";
				echo "<a href=\"".param_url("?p=catalog&pid=$pid&cat_id=$cat_id&screen=$screen&action=delete")."\"><img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>";
				echo "</tr>";
				$no++;
			}
		}
		echo "</table>";
	}
	$catcontent.=ob_get_clean();	
}


if ($action=="" or $action=="search" or $action=="list") {
	$specialadmin .= "<p><a class=\"buton\" href=\"".param_url("?p=catalog&action=add")."\">"._ADDPRODUCT."</a></p>";
}


if ($action=='attribut_save') {
	$type_attribut=$_POST['type_attribut'];
	$nama=$_POST['nama_attribut'];
	$idattr=$_POST['id_attribut'];
	$attribut_value=$_POST['attribut_value'];
	foreach($nama as $i =>$v) {
		if($idattr[$i]!='') {
			if($v!='') {
			$q=$mysql->query("UPDATE catalog_atm SET nama='".$v."' WHERE id='".$idattr[$i]."'");
			$q=$mysql->query("DELETE FROM catalog_atd WHERE id_atm='".$idattr[$i]."'");
				if($type_attribut[$i]==2) {
					//dropdown
					$atr_value=explode(",",$attribut_value[$i]);
					foreach($atr_value as $x => $y) {
						if($y!='') {
							$q1=$mysql->query("INSERT INTO catalog_atd(id_atm,value) values ('".$idattr[$i]."','".$y."')");
							if($q1) {
								pesan(_SUCCESS,_SIMPANATTRIBUTBERHASIL);
							}
						}
					}
				}
			
			} else {
				$q=$mysql->query("DELETE FROM catalog_atm WHERE id='".$idattr[$i]."'");
				$q=$mysql->query("DELETE FROM catalog_atd WHERE id_atm='".$idattr[$i]."'");
			}
		} else {
			if($v!='') {
				list($lastid)=$mysql->fetch_row($mysql->query("select max(id) from catalog_atm"));
				$lastid=$lastid+1;
				$q=$mysql->query("INSERT INTO catalog_atm(id,nama,type) values ('$lastid','".$v."','".$type_attribut[$i]."')");
				if($q) {
					if($type_attribut[$i]==2) {
						//dropdown
						$atr_value=explode(",",$attribut_value[$i]);
						foreach($atr_value as $x => $y) {
							if($y!='') {
								$q1=$mysql->query("INSERT INTO catalog_atd(id_atm,value) values ('$lastid','".$y."')");
								if($q1) {
									pesan(_SUCCESS,_SIMPANATTRIBUTBERHASIL);
								}
							}
						}
					}
				}
			
			}
		}
		
		
	}
}

if ($action=='attribut_add')  {
	ob_start();
	$admintitle=_ATTRIBUTTAMBAHAN;
	$select_type="<select name=\"type_attribut[]\"  class=\"pilih_type input-medium\">";
	$select_type.="<option value=''>"._PILIH."</option>";
	foreach($attribut_type as $i => $v) {
		$select_type.="<option value='$i'>$v</option>";
	}
	$select_type.="<select>";
	echo "<div id='data_attribut_type'>$select_type</div>";

	echo "<form class=\"form-horizontal\" method='post' action='?p=catalog&action=attribut_save'>";
	echo "<table border='1' id='attribut_table'>";
	echo "<tr>";
	echo "<th width='105px'>"._NAMAATTRIBUT."</th>";
	echo "<th width='110px' >"._JENISATTRIBUT."</th>";
	echo "<th width='200px'>"._DETAIL."</th>";
	echo "</tr>";
	/////////////////////
	$res=$mysql->query("SELECT id,nama,type FROM catalog_atm order by id");
	while($d=$mysql->fetch_assoc($res)) {
		$detail_value="";
		if($d['type']==2) {
			$res1=$mysql->query("SELECT group_concat( value SEPARATOR ',') value  FROM catalog_atd WHERE id_atm='".$d['id']."'");
			list($detail_value)=$mysql->fetch_row($res1);
		}
		$select_type1="<select name=\"type_attribut[]\"  class=\"pilih_type input-medium\">";
		$select_type1.="<option value=''>"._PILIH."</option>";
		foreach($attribut_type as $i => $v) {
			$select_type1.="<option value='$i' ".($i==$d['type']?"selected='selected'":"").">$v</option>";
		}
		$select_type1.="<select>";
		echo "<tr>";
		echo "<td><input type='hidden' name='id_attribut[]' value='".$d['id']."' /><input type='text' name='nama_attribut[]' class='input-small' value='".$d['nama']."' /></td>";
		echo "<td>$select_type1</td>";
		echo "<td><div  class='".($d['type']==2?"attribut_show":"attribut_value")."'><input type='text' name='attribut_value[]' id='textfield".$d['id']."' class='tagsinput' placeholder='dipisah koma ex: Merah,Biru' value='$detail_value'/><div></td>";
		echo "</tr>";
	}
	////////////////////
	echo "<tr>";
	echo "<td><input type='text' name='nama_attribut[]' class='input-small' /></td>";
	echo "<td>$select_type</td>";
	echo "<td><div  class='attribut_value'><input type='text' placeholder='dipisah koma ex: Merah,Biru' name='attribut_value[]' id='textfield' class='tagsinput'/><div></td>";
	echo "</tr>";
	echo "</table>";
	echo "<div>";
	echo "<a href=\"javascript:void(0);\" class=\"attribut_add buton\">(+) Tambah</a>";
	echo "</div>";
	echo "<div class='save_attribut'>";
	echo "<input class=\"buton\" type='submit' name='save_attribut' value='Save' />";
	echo "</div>";
	echo "</form>";
	$catcontent.=ob_get_clean();	
}

$admincontent .= "<div id=\"catnav\"></div>";
$admincontent .= "<div id=\"catcontent\">$catcontent</div>";
}
?>
