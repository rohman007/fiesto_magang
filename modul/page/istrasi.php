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

$judul = fiestolaundry($_POST['judul'], 200);
$isi = fiestolaundry($_POST['isi'], 0, TRUE);
$action = fiestolaundry($_REQUEST['action'], 10);
$url = fiestolaundry($_POST['url'],255);

$modulename = $_GET['p'];

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($judul, _TITLE, false);
        $notificationbuilder .= validation($isi, _PAGECONTENT, false);
//        $judul = checkrequired($judul, _TITLE);
        $urutan = $_POST['urutan'];
        $parent = $_POST['parent']!=''?$_POST['parent']:0;
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else 
		{
            // list($idpage) = $mysql->fetch_row($mysql->query("SELECT max(id) FROM page"));
			$result = $mysql->query("SELECT max(id) FROM page");
            list($idpage) = $result->fetch_row();
            $idpage = $idpage + 1;
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($judul, 'page', $pid) : seo_friendly_url($url, 'page', $pid);
			}
            $sql = "INSERT INTO page (id, judul, isi, url) VALUES ('$idpage', '$judul', '$isi', '$url')";
            $result = $mysql->query($sql);

            if ($result) {
                $mx = getMaxNumber('menu', 'urutan') + 1;
                // list($idmenu) = $mysql->fetch_row($mysql->query("SELECT max(id) FROM page"));
				$result = $mysql->query("SELECT max(id) FROM page");
				list($idpage) = $result->fetch_row();
                $sql = "INSERT INTO menu (type, parent, judul, isi, urutan) values ('5',$parent,'$judul','$idpage','$mx')";
				
                $result = $mysql->query($sql);
                $result = urutkan('menu', $urutan,"parent='0'", $idmenu, "parent='0'");
                $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "add");
            }
        }
    } else {
        $action = "add";
    }
}

if ($action == "add") {
    $admintitle = _ADDPAGE;
	$slugcontent = '';
	if ($pakaislug) {
		$slugcontent .= '<div class="control-group">';
		$slugcontent .= '<label class="control-label">'._SLUGURL.'</label>';
		$slugcontent .= '<div class="controls"><input type="text" name="url" id="url" value="'.$url.'"/></div>';
		$slugcontent .= '</div>';
	}
	
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '">
	  <input type="hidden" name="action" value="save">
	      <div class="control-group">
	        <label class="control-label">' . _TITLE . '</label>
	        <div class="controls"><input type="text" name="judul" size="40"></div>
	      </div>
		  '.$slugcontent.'
	      <div class="control-group">
	        <label class="control-label">' . _PAGECONTENT . '</label>
	        <div class="controls"><textarea name="isi" rows="20" cols="60" class="usetiny"></textarea></div>
	      </div>
		  <div class="control-group">
	        <label class="control-label">' . _SUBMENUOF . '</label>
			    <div class="controls">';
	
	 $urlajax = $absolutadminurl . "/ajax.php?p=menu&action=urutan";
    // if ($action == "edit") {
        // $urlajax .= "&pid=" . $pid;
    // }
    $eventurutan = "onchange=\"ajaxpage('" . $urlajax . "&parent='+this.value, 'urutancontent')\" ";
    $admincontent .= "<select name=\"parent\" $eventurutan >\r\n";
    $cats = new categories();
    $mycats = array();
    $sql = "SELECT id, judul, parent FROM menu WHERE type=(SELECT id FROM menutype WHERE modul='menu' AND jenis='parent' ) ";
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_array($result)) {
        $mycats[] = array('id' => $row['id'], 'judul' => $row['judul'], 'parent' => $row['parent'], 'level' => 0);
    }
    $cats->get_cats($mycats);
    $mysql->free_result($result);
	
    $admincontent .= "<option value=\"0\">$topcatnamecombo " . _TOP . "</option>\r\n";
    for ($i = 0; $i < count($cats->cats); $i++) {
        $isallowed = true;
        $cats->cat_map($cats->cats[$i]['id'], $mycats);
        $judul = $cats->cats[$i]['judul'];
        $admincontenttemp = '<option value="' . $cats->cats[$i]['id'] . '"';
        $admincontenttemp .= ($cats->cats[$i]['id'] == $parent) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
        for ($a = 0; $a < count($cats->cat_map); $a++) {
            if ($cats->cat_map[$a]['id'] != $pid) {
                if ($isallowed)
                    $admincontenttemp .= " / " . $cats->cat_map[$a]['judul'];
            } else {
                $admincontenttemp = "";
                $isallowed = false;
            }
        }
        if ($cats->cats[$i]['id'] != $pid) {
            if ($isallowed)
                $admincontenttemp .= " / $judul</option>";
        } else {
            $admincontenttemp = "";
            $isallowed = false;
        }
        $admincontent .= $admincontenttemp;
    }
    $admincontent .="</select>";
    $admincontent .='
				</div>
	      </div>
			<div class="control-group">
			<label class="control-label">' . _ORDER . '</label>
			<div class="controls" id="urutancontent">' . createurutan("menu", ((strlen($parent) > 0) ? $parent : 0), '') . '</div>
			</div>
	      <div class="control-group">
	        <div class="controls"><input type="submit" name="submit" class="buton"  value="' . _SAVE . '">
                     <input type="submit" name="back" class="buton" value="' . _BACK . '">
                         </div>
	      </div>
	</form>
    ';
}

if ($action == "update") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($judul, _TITLE, false);
        $notificationbuilder .= validation($isi, _PAGECONTENT, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
        } else {
            $judul = checkrequired($judul, _TITLE);
            $urutan = $_POST['urutan'];
			$parent = $_POST['parent']!=''?$_POST['parent']:0;
          
			if ($pakaislug) {
				$url = $url == '' ? seo_friendly_url($judul, 'page', $pid) : seo_friendly_url($url, 'page', $pid);
			}
            $sql = "UPDATE page SET judul='$judul', isi='$isi', url='$url' WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $sqlcek = "SELECT * FROM menu WHERE type=5 and isi='$pid'";
                $resultcek = $mysql->query($sqlcek);
                $datacek = $mysql->fetch_array($resultcek);
                $idmenu = $datacek['id'];
                $kondisiprev = "parent='0' ";
                $kondisi = "parent='0'";

                $sql = "UPDATE menu SET judul='$judul',parent='$parent' WHERE id='$idmenu'";
                $result = $mysql->query($sql);

                $result = urutkan('menu', $urutan, $kondisi, $idmenu, $kondisiprev);
                if ($kondisi != $kondisiprev) {
                    urutkansetelahhapus('menu', $kondisiprev);
                }
                $action = createmessage(_EDITSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    } else {
        $action = "modify";
    }
}

// MODIFY PAGE
if ($action == "modify") {
    $admintitle = _EDITPAGE;
    $sql = "SELECT id, judul, isi, url FROM page WHERE id='$pid'";
    $result = $mysql->query($sql);
    if ($result->num_rows == "0") {
        $action = createmessage(_NOPAGE, _INFO, "info", "");
    } else {
		
        list($id, $judul, $isi, $url) = $result->fetch_row();
        $publishstatus = ($publish == 1) ? 'checked' : '';
        //$catselect = adminselectcategories('linkcat',$cat_id);
    	//get isi from menu type
		$q = $mysql->query("SELECT id,parent FROM menu WHERE  type='5' AND isi='$pid'");
		if($q && $q->num_rows>0) {
			list($idmenu,$subparent) = $q->fetch_row();
		}

		if ($pakaislug) {
			$slugcontent = '<div class="control-group">';
			$slugcontent .= '<label class="control-label">'._SLUGURL.'</label>';
			$slugcontent .= "<div class=\"controls\"><input type=\"text\" name=\"url\" id=\"url\" value=\"".$url."\"/></div>";
			$slugcontent .= '</div>';
		}
        $admincontent .= '
		<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="pid" value="' . $pid . '">
                            <div class="control-group">
                                 <label class="control-label">' . _TITLE . '</label>
                                 <div class="controls"><input type="text" name="judul" value="' . $judul . '" size="40"></div>
                          </div>
						  '.$slugcontent.'
                         <div class="control-group">
                                   <label class="control-label">' . _PAGECONTENT . '</label>
                                     <div class="controls"><textarea name="isi" rows="20" cols="60" class="usetiny">' . $isi . '</textarea></div>
                         </div>                
						  <div class="control-group">
	        <label class="control-label">' . _SUBMENUOF . '</label>
			    <div class="controls">';
				
	$urlajax = $absolutadminurl . "/ajax.php?p=menu&action=urutan";
	$urlajax .= "&pid=" . $idmenu;
	$eventurutan = "onchange=\"ajaxpage('" . $urlajax . "&parent='+this.value, 'urutancontent')\" ";
	$admincontent .= "<select name=\"parent\" $eventurutan >\r\n";
    $cats = new categories();
    $mycats = array();
    $sql = "SELECT id, judul, parent FROM menu WHERE type=(SELECT id FROM menutype WHERE modul='menu' AND jenis='parent' ) ";
    $result = $mysql->query($sql);
    while ($row = $result->fetch_array()) {
        $mycats[] = array('id' => $row['id'], 'judul' => $row['judul'], 'parent' => $row['parent'], 'level' => 0);
    }
    $cats->get_cats($mycats);
    // $mysql->free_result($result);
    $admincontent .= "<option value=\"0\">$topcatnamecombo " . _TOP . "</option>\r\n";
    for ($i = 0; $i < count($cats->cats); $i++) {
        $isallowed = true;
        $cats->cat_map($cats->cats[$i]['id'], $mycats);
        $judul = $cats->cats[$i]['judul'];
        $admincontenttemp = '<option value="' . $cats->cats[$i]['id'] . '"';
        $admincontenttemp .= ($cats->cats[$i]['id'] == $subparent) ? " selected>$topcatnamecombo" : ">$topcatnamecombo";
        for ($a = 0; $a < count($cats->cat_map); $a++) {
            if ($cats->cat_map[$a]['id'] != $pid) {
                if ($isallowed)
                    $admincontenttemp .= " / " . $cats->cat_map[$a]['judul'];
            } else {
                $admincontenttemp = "";
                $isallowed = false;
            }
        }
        if ($cats->cats[$i]['id'] != $pid) {
            if ($isallowed)
                $admincontenttemp .= " / $judul</option>";
        } else {
            $admincontenttemp = "";
            $isallowed = false;
        }
        $admincontent .= $admincontenttemp;
    }
    $admincontent .="</select>";
			$admincontent .='	
							</div>
						</div>
						  <div class="control-group">
                                <label class="control-label">' . _ORDER . '</label>
                                <div class="controls" id="urutancontent">' . createurutan("menu", ((strlen($subparent) > 0) ? $subparent : 0), $idmenu) . '</div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" name="submit" class="buton"  value="' . _SAVE . '">
                                <input type="submit" name="back" class="buton" value="' . _BACK . '">
                            </div>
                        </div>
		</form>
		';
    }
}

// DELETE LINK
if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    $sql = "SELECT id FROM page WHERE id='$pid'";
    $result = $mysql->query($sql);
    if ($result->num_rows == "0") {
        $action = createmessage(_NOPAGE, _INFO, "info", "");
    } else {
        if ($action == "remove") {
            $admintitle = _DELPAGE;
            $admincontent = "<h5>"._PROMPTDEL."</h5>";
            $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=page&action=delete&pid=$pid")."\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
        } else {
            $sql = "DELETE FROM page WHERE id='$pid'";
            $result = $mysql->query($sql);
            if ($result) {
                $sql = "DELETE FROM menu WHERE isi='$pid' and type=5";
                $result = $mysql->query($sql);
                $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            }
        }
    }
}

if ($action == "" or $action == "search") {

	if($action=="search")
	{
	 $admintitle = _SEARCHRESULTS;
	 $sql="SELECT id,judul,isi FROM page WHERE judul LIKE '%$keyword%' OR isi LIKE '%$keyword%' ";
	}
	else
	{
    $sql = "SELECT id,judul,isi FROM page ";
	}
	 $result = $mysql->query($sql);
    $total_records = $result->num_rows;
    $pages = ceil($total_records / $max_page_list);

    if ($total_records > 0) {
        $start = $screen * $max_page_list;
        $sql .= " LIMIT $start, $max_page_list";
		
        $result = $mysql->query($sql);

        if ($pages > 1)
            $adminpagination = pagination($namamodul, $screen);
        $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
        $admincontent .= "<tr><th>" . _TITLE . "</th>";
        $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
        while (list($id, $judul, $isi) = $result->fetch_row()) {
            $admincontent .= "<tr>\n";
            $admincontent .= "<td>$judul</td>";
            $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            $admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            $admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            $admincontent .= "</tr>\n";
        }
        $admincontent .= "</table>";
    } else {
        createnotification(_NOPAGE, _INFO, "info");
    }
    $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=page&action=add")."\" class=\"buton\">" . _ADDPAGE . "</a>";
}


?>
