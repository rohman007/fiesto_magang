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

// $judul = fiestolaundry($_POST['judul'], 200);
$templaterow1 = fiestolaundry($_POST['template1'], 0, TRUE);
$templaterow2 = fiestolaundry($_POST['template2'], 0, TRUE);
$templaterow3 = fiestolaundry($_POST['template3'], 0, TRUE);
$templaterow4 = fiestolaundry($_POST['template4'], 0, TRUE);
$templaterow5 = fiestolaundry($_POST['template5'], 0, TRUE);
$templaterow6 = fiestolaundry($_POST['template6'], 0, TRUE);
$templaterow7 = fiestolaundry($_POST['template7'], 0, TRUE);
$action = fiestolaundry($_REQUEST['action'], 10);
$t_id = $_POST['tid'];

$modulename = $_GET['p'];

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        // $notificationbuilder .= validation($judul, _TITLE, false);
        // $notificationbuilder .= validation($isi, _PAGECONTENT, false);
//        $judul = checkrequired($judul, _TITLE);
        // $urutan = $_POST['urutan'];
        // $parent = $_POST['parent']!=''?$_POST['parent']:0;
		$temp = array();
		for($i = 1; $i <= 7; $i++) $temp[] = "template$i";
		$template = join(',',$temp);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else 
		{
            // list($idpage) = $mysql->fetch_row($mysql->query("SELECT max(id) FROM page"));
            // $idpage = $idpage + 1;
            $sql = "INSERT INTO template_option (id,judul, $template) VALUES ('$idpage','$judul','$templaterow1','$templaterow2','$templaterow3','$templaterow4','$templaterow5','$templaterow6','$templaterow7')";
            $result = $mysql->query($sql);
			if ($result) {
				$action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
			} else {
				$action = createmessage(_DBERROR, _ERROR, "error", "add");
			}
            // if ($result) {
                // // $mx = getMaxNumber('menu', 'urutan') + 1;
                // // list($idmenu) = $mysql->fetch_row($mysql->query("SELECT max(id) FROM page"));
                // $sql = "INSERT INTO menu (type, parent, judul, isi, urutan) values ('5',$parent,'$judul','$idpage','$mx')";
				
                // $result = $mysql->query($sql);
                // $result = urutkan('menu', $urutan,"parent='0'", $idmenu, "parent='0'");
                // $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
            // } else {
                // $action = createmessage(_DBERROR, _ERROR, "error", "add");
            // }
        }
    } else {
        $action = "add";
    }
}
if ($action == "add") {
    $admintitle = _ADDPAGE;
    $admincontent .= '
	<form class="form-horizontal" method="POST" action="' . $thisfile . '">
	  <input type="hidden" name="action" value="save">';
	for($i = 1; $i <= 7; $i++) {
		$admincontent .= '	      
		  <div class="control-group">
	        <label class="control-label">' . _PAGECONTENT . $i . '</label>
	        <div class="controls"><textarea name="template'.$i.'" rows="20" cols="60" class="usetiny"></textarea></div>
	      </div>';
	}
	$admincontent .= '
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
        // $notificationbuilder .= validation($judul, _TITLE, false);
        // $notificationbuilder .= validation($isi, _PAGECONTENT, false);
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "modify");
        } else {
            // $judul = checkrequired($judul, _TITLE);
            // $urutan = $_POST['urutan'];
			// $parent = $_POST['parent']!=''?$_POST['parent']:0;
          
            $sql = "UPDATE template_option SET template1='$templaterow1', template2='$templaterow2', template3='$templaterow3', template4='$templaterow4', template5='$templaterow5', template6='$templaterow6', template7='$templaterow7'";
            $result = $mysql->query($sql);
            if ($result) {
                // $sqlcek = "SELECT * FROM menu WHERE type=5 and isi='$pid'";
                // $resultcek = $mysql->query($sqlcek);
                // $datacek = $mysql->fetch_assoc($resultcek);
                // $idmenu = $datacek['id'];
                // $kondisiprev = "parent='0' ";
                // $kondisi = "parent='0'";

                // $sql = "UPDATE menu SET judul='$judul',parent='$parent' WHERE id='$idmenu'";
                // $result = $mysql->query($sql);

                // $result = urutkan('menu', $urutan, $kondisi, $idmenu, $kondisiprev);
                // if ($kondisi != $kondisiprev) {
                    // urutkansetelahhapus('menu', $kondisiprev);
                // }
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
if ($action == "modify" || $action == "" ) {
    $admintitle = _EDITPAGE;
	$temp = array();
	for($i = 1; $i <= 7; $i++) $temp[] = "template$i";
	$template = join(',',$temp);	
    $sql = "SELECT id, judul, template FROM template_option";

    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) == "0") {
        $action = createmessage(_NOPAGE, _INFO, "info", "");
    } else {
        // list($id, $judul, $template) = $mysql->fetch_row($result);
        // $publishstatus = ($publish == 1) ? 'checked' : '';
        //$catselect = adminselectcategories('linkcat',$cat_id);
    	//get isi from menu type
		// $q=$mysql->query("SELECT id,parent FROM menu WHERE  type='5' AND isi='$pid'");
		// if($q and $mysql->num_rows($q)>0)
		// {
		// list($idmenu,$subparent)=$mysql->fetch_row($q);
		// }

        $admincontent .= '
		<form class="form-horizontal" method="POST" action="' . $thisfile . '" enctype="multipart/form-data">
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="pid" value="' . $pid . '">';
			 while(list($id, $judul, $template) = $mysql->fetch_row($result)) {
				$admincontent .= '
					 <div class="control-group">
						<label class="control-label">' . _PAGECONTENT . ' 1</label>
						<div class="controls">
							<input type="hidden" name="t_id" value='.$id.'>
							<textarea name="template1" rows="20" cols="60" class="usetiny">' . $rowtemp1 . '</textarea>
						</div>
					 </div>';
			 }
						 // $admincontent .= '
							 // <div class="control-group">
								// <label class="control-label">' . _PAGECONTENT . ' 1</label>
								// <div class="controls">
									// <input type="hidden" name="t_id" value='.$id.'>
									// <textarea name="template1" rows="20" cols="60" class="usetiny">' . $rowtemp1 . '</textarea>
								// </div>
							 // </div>
							 // <div class="control-group">
								// <label class="control-label">' . _PAGECONTENT . ' 2</label>
								// <div class="controls">
									// <textarea name="template2" rows="20" cols="60" class="usetiny">' . $rowtemp2 . '</textarea>
								// </div>
							 // </div>
							 // <div class="control-group">
								// <label class="control-label">' . _PAGECONTENT . ' 3</label>
								// <div class="controls">
									// <textarea name="template3" rows="20" cols="60" class="usetiny">' . $rowtemp3 . '</textarea>
								// </div>
							 // </div>
							 // <div class="control-group">
								// <label class="control-label">' . _PAGECONTENT . ' 4</label>
								// <div class="controls">
									// <textarea name="template4" rows="20" cols="60" class="usetiny">' . $rowtemp4 . '</textarea>
								// </div>
							 // </div>
							 // <div class="control-group">
								// <label class="control-label">' . _PAGECONTENT . ' 5</label>
								// <div class="controls">
									// <textarea name="template5" rows="20" cols="60" class="usetiny">' . $rowtemp5 . '</textarea>
								// </div>
							 // </div>
							 // <div class="control-group">
								// <label class="control-label">' . _PAGECONTENT . ' 6</label>
								// <div class="controls">
									// <textarea name="template6" rows="20" cols="60" class="usetiny">' . $rowtemp6 . '</textarea>
								// </div>
							 // </div>
							 // <div class="control-group">
								// <label class="control-label">' . _PAGECONTENT . ' 7</label>
								// <div class="controls">
									// <textarea name="template7" rows="20" cols="60" class="usetiny">' . $rowtemp7 . '</textarea>
								// </div>
							 // </div>
							 // ';                
						 $admincontent .= '
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

// // DELETE LINK
// if ($action == "remove" || $action == "delete") { //errhandler utk antisipasi pengetikan URL langsung di browser
    // $sql = "SELECT id FROM template_option WHERE id='$pid'";
    // $result = $mysql->query($sql);
    // if ($mysql->num_rows($result) == "0") {
        // $action = createmessage(_NOPAGE, _INFO, "info", "");
    // } else {
        // if ($action == "remove") {
            // $admintitle = _DELPAGE;
            // $admincontent = "<h5>"._PROMPTDEL."</h5>";
            // $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=template_option&action=delete&pid=$pid")."\">" . _YES . "</a> <a class=\"buton\" href=\"javascript:history.go(-1)\">" . _NO . "</a></p>";
        // } else {
            // $sql = "DELETE FROM template_option WHERE id='$pid'";
            // $result = $mysql->query($sql);
            // if ($result) {
                // // $sql = "DELETE FROM menu WHERE isi='$pid' and type=5";
                // // $result = $mysql->query($sql);
                // $action = createmessage(_DELETESUCCESS, _SUCCESS, "success", "");
            // } else {
                // $action = createmessage(_DBERROR, _ERROR, "error", "modify");
            // }
        // }
    // }
// }

// if ($action == "") {
	// $temp = array();
	// for($i = 1; $i <= 7; $i++) $temp[] = "template$i";
	// $template = join(',',$temp);
	// $$rowtemplate = $template;
	// $sql = "SELECT id,judul, $template FROM template_option";
	// $result = $mysql->query($sql);
    // $total_records = $mysql->num_rows($result);
    // $pages = ceil($total_records / $max_page_list);

    // if ($mysql->num_rows($result) > 0) {
        // $start = $screen * $max_page_list;
        // $sql .= " LIMIT $start, $max_page_list";
		
        // $result = $mysql->query($sql);

        // if ($pages > 1)
            // $adminpagination = pagination($namamodul, $screen);
        // $admincontent .= "<table class=\"stat-table table table-stats table-striped table-sortable table-bordered\" >\n";
        // $admincontent .= "<tr><th>" . _TITLE . "</th>";
        // $admincontent .= "<th align=\"center\">" . _ACTION . "</th></tr>\n";
		
        // while (list($id, $judul, $rowtemplate) = $mysql->fetch_row($result)) {
            // $admincontent .= "<tr>\n";
            // $admincontent .= "<td>$judul</td>";
            // $admincontent .= "<td align=\"center\" class=\"action-ud\">";
            // $admincontent .= "<a href=\"".param_url("?p=$modulename&action=modify&pid=$id")."\"><img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a>\r\n";
            // $admincontent .= "<a href=\"".param_url("?p=$modulename&action=remove&pid=$id")."\"><img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
            // $admincontent .= "</tr>\n";
        // }
        // $admincontent .= "</table>";
    // } else {
        // createnotification(_NOPAGE, _INFO, "info");
    // }
    // $admincontent .= "<a class=\"buton\" href=\"".param_url("?p=template_option&action=add")."\" class=\"buton\">" . _ADDPAGE . "</a>";
// }

?>
