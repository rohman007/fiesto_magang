<?php
if (!$isloadfromindex) {
	include ("../../kelola/urasi.php");
	include ("../../kelola/fungsi.php");
	include ("../../kelola/lang/$lang/definisi.php");
	pesan(_ERROR,_NORIGHT);
}
$action=fiestolaundry($_REQUEST['action'],11);
$module=fiestolaundry($_REQUEST['module'],100);

if ($action=="") {
	$admintitle .= _MODUL;
	$sql = "SELECT nama, judulfrontend FROM module WHERE (grup=2 OR grup=5) AND access='0,1' AND nama!='member' ORDER BY nama";
	$query = $mysql->query($sql);
	$admincontent .= "<table class=\"list stat-table table table-stats table-striped table-sortable table-bordered\">\n";
	$admincontent .= "<tr><th>"._MODULE."</th>";
	$admincontent .= "<th>"._DELETE."</th></tr>\n";
	if ($mysql->num_rows($query)>0){
		while (list($nama, $judulfrontend) = $mysql->fetch_row($query)) {
			$admincontent .= "<tr>\n";
			$admincontent .= "<td>$judulfrontend</td>";
			$admincontent .= "<td align=\"center\"><a href=\"?p=uninstaller&action=remove&module=$nama\">";
			$admincontent .= "<img alt=\"Hapus\" border=\"0\" src=\"../images/delete.gif\"></a></td>\n";
			$admincontent .= "</tr>\n";
		}
	}else{
		$admincontent .= "<tr><td>"._EMPTY."</td><td></td></tr>";
	}
	$admincontent .= "</table>";
}
if ($action == "remove" || $action == "delete") {
	//get array value database yang mau di drop dari urasi masing2 modul
	if (file_exists("../modul/$module/urasi.php")) include ("../modul/$module/urasi.php");

	if ($action == "remove") {
		$admintitle = _DELPAGE;
		$admincontent = _PROMPTDEL;
		$admincontent .= "<a class=\"btn\" href=\"?p=uninstaller&action=delete&module=$module\">"._YES."</a> <a href=\"javascript:history.go(-1)\" class=\"btn\">"._NO."</a></p>";
	} else {
		//Drop MySQL
		//Perhatikan input table dan nama array yang terkait (start)
		$admincontent .= "<h2>Drop database MySQL.</h2>";
		$temp_module = $module."_drop";
		if (count($$temp_module)>0){
			foreach($$temp_module as $module_drop){
				$sql = "DROP TABLE $module_drop";
				if ($mysql->query($sql))
					$admincontent .= "DROP TABLE $module_drop<br />";
				else
					$admincontent .= "DROP TABLE $module_drop gagal / Table tidak ada<br />";
			}
		}else
			$admincontent .= "
				Tidak ada table yang di drop, dengan kemungkinan :<br />
				- Table belum terdaftar dalam urasi.php, ".$module."_drop pada modul $module.<br />
				- Tidak ada table khusus yang terkait dengan modul $module.<br />
				- Modul $module sudah tidak ada / sudah terhapus.<br />
			";
		//Perhatikan input table dan nama array yang terkait (end)
		
		//Delete SQL
		$cek_delete_sql = 0;
		$admincontent .= "<h2>Delete database MySQL.</h2>";
		foreach($del_db as $delete){
			switch($delete){
				case 'config':
					$sql_search = "SELECT * FROM $delete WHERE modul = '$module'";
					$query_search = $mysql->query($sql_search);
					if ($mysql->num_rows($query_search)>0){
						$mysql->query("DELETE FROM $delete WHERE modul='$module'");
						//Print result
						$admincontent .= "DELETE FROM $delete WHERE modul='$module'<br />";
						$cek_delete_sql++;
					}
				break;
				case 'widget':
				case 'menu':
				case 'home':
					$sql_search = "SELECT id FROM ".$delete."type WHERE modul = '$module'";
					$query_search = $mysql->query($sql_search);
					if ($mysql->num_rows($query_search)>0){
						while(list($id) = $mysql->fetch_array($query_search)){
							$sql_get_id = "SELECT id FROM ".$delete." WHERE type=$id";
							$query_get_id = $mysql->query($sql_get_id);
							if ($mysql->num_rows($query_get_id)>0){
								while(list($id_del_lang) = $mysql->fetch_array($query_get_id)){
									//Delete - home_lang, menu_lang, widget_lang
									$mysql->query("DELETE FROM ".$delete."_lang WHERE id=$id_del_lang");
									//Print result
									$admincontent .= "DELETE FROM ".$delete."_lang WHERE id=$id_del_lang<br />";
									//Hapus dan mengurutkan
									if (($delete=='widget')||($delete=='home')){
										//Hapus dan urutkan widget, home
										$sql_posisi = "SELECT posisi FROM ".$delete." WHERE type=$id";
										$query_posisi = $mysql->query($sql_posisi);
										if ($mysql->num_rows($query_posisi)>0){
											//Delete - home, widget
											$mysql->query("DELETE FROM ".$delete." WHERE type=$id");
											//Print result
											$admincontent .= "DELETE FROM ".$delete." WHERE type=$id<br />";
											//Urutkan setelah hapus
											list($posisi) = $mysql->fetch_array($query_posisi);
											urutkansetelahhapus("".$delete."", "posisi='".$posisi."' ");
										}
									}else{
										//Hapus dan urutkan menu
										$sql_posisi = "SELECT parent FROM ".$delete." WHERE id=$id";
										$query_posisi = $mysql->query($sql_posisi);
										if ($mysql->num_rows($query_posisi)>0){
											//Delete - menu
											$mysql->query("DELETE FROM ".$delete." WHERE type=$id");
											//Print result
											$admincontent .= "DELETE FROM ".$delete." WHERE type=$id<br />";
											//Urutkan setelah hapus
											list($parent) = $mysql->fetch_array($query_posisi);
											urutkansetelahhapus("".$delete."", "parent='".$parent."'");
										}
									}
								}
							}
						}
						//Delete - hometype
						$mysql->query("DELETE FROM ".$delete."type WHERE modul='$module'");
						//Print result
						$admincontent .= "DELETE FROM ".$delete."type WHERE modul='$module'<br />";
						$cek_delete_sql++;
					}
				break;
				case 'module':
					$sql_search = "SELECT id FROM ".$delete." WHERE nama = '$module'";
					$query_search = $mysql->query($sql_search);
					if ($mysql->num_rows($query_search)>0){
						while(list($id) = $mysql->fetch_array($query_search)){
							//Delete - module_lang
							$mysql->query("DELETE FROM ".$delete."_lang WHERE id=$id");
							//Print result
							$admincontent .= "DELETE FROM ".$delete."_lang WHERE id=$id<br />";
						}
						//Delete - module
						$mysql->query("DELETE FROM ".$delete." WHERE nama='$module'");
						//Print result
						$admincontent .= "DELETE FROM ".$delete." WHERE nama='$module'<br />";
						$cek_delete_sql++;
					}
				break;
				case 'translation':
					$sql_search = "SELECT id FROM ".$delete." WHERE modul = '$module'";
					$query_search = $mysql->query($sql_search);
					if ($mysql->num_rows($query_search)>0){
						while(list($id) = $mysql->fetch_array($query_search)){
							//Delete - translation_lang
							$mysql->query("DELETE FROM ".$delete."_lang WHERE id=$id");
							//Print result
							$admincontent .= "DELETE FROM ".$delete."_lang WHERE id=$id<br />";
						}
						//Delete - module
						$mysql->query("DELETE FROM ".$delete." WHERE modul='$module'");
						//Print result
						$admincontent .= "DELETE FROM ".$delete." WHERE modul='$module'<br />";
						$cek_delete_sql++;
					}
				break;
			}
		}
		if($cek_delete_sql==0)
			$admincontent .= "Tidak ada yang dihapus.";
		//Delete folder dan directory
		//Delete folder file/..
		//Khusus modul order
		$admincontent .= "<h2>Delete folder file.</h2>";
		if ($module=='order'){
			//modul order ada 2 folder didalam file/.., khusus modul ini saja proses delete 2x
			//nama folder didalam file/.. yang terkait : temp_transaksi, transaksi
			$dirname_file = $cfg_app_path."/file/transaksi";
			if (file_exists($dirname_file)){
				delete_directory($dirname_file);
				//Print result
				$admincontent .= "Delete directory $dirname_file<br />";
			}
			//
			$dirname_file = $cfg_app_path."/file/temp_transaksi";
		}else
			$dirname_file = $cfg_app_path."/file/".$module;
		
		if (file_exists($dirname_file)){
			delete_directory($dirname_file);
			//Print result
			$admincontent .= "Delete directory $dirname_file<br />";
		}else{
			//Print result
			$admincontent .= "Tidak ditemukan folder $module dalam file/..<br />";
		}
		//Delete folder module/..
		//Khusus modul order
		$admincontent .= "<h2>Delete folder modul.</h2>";
		$dirname_modul = $cfg_app_path."/modul/".$module;
		if (file_exists($dirname_modul)){
			delete_directory($dirname_modul);
			//Print result
			$admincontent .= "Delete directory $dirname_modul<br />";
		}else{
			//Print result
			$admincontent .= "Tidak ditemukan folder $module dalam modul/..<br />";
		}
	}
}
if($action!='') $specialadmin .= "<a class=\"btn\" href=\"?p=uninstaller\">"._BACK."</a>";
?>
