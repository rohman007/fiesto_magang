<?php
if (!$isloadfromindex) {
	include ("../../kelola/urasi.php");
	include ("../../kelola/fungsi.php");
	include ("../../kelola/lang/$lang/definisi.php");
	pesan(_ERROR,_NORIGHT);
}
$action=fiestolaundry($_REQUEST['action'],11);
//starter awal cek modul, jika true maka proses instal berhenti.
$hentikan = false;

if ($action=="") {
	$admintitle .= _UPLOADZIP;
	$specialadmin .= "<form action=\"$thisfile\" name=\"mainform\" method=\"POST\" enctype=\"multipart/form-data\" class=\"form-horizonta\">\r\n";
	$specialadmin .= "<input type=\"hidden\" name=\"action\" value=\"unzip\">\r\n";
	$specialadmin .= "<div class=\"control-group\">";
	// $specialadmin .= "<label class=\"control-label\">"._ZIPFILE."</label> : <input type=\"file\" name=\"filename\"><br /><br />\r\n";
	$specialadmin .= "
	<div class=\"input_foto_tambahanx controls\">
		<div data-provides=\"fileupload\" class=\"fileupload fileupload-new\">
			<div class=\"input-append\">
				<div class=\"uneditable-input span2\">
					<i class=\"icon-file fileupload-exists\"></i><span class=\"fileupload-preview\"><label class=\"control-label\">"._ZIPFILE."</label></span>
				</div>
				<span class=\"btn btn-file\"><span class=\"fileupload-new\">Cari File</span><span class=\"fileupload-exists\">Ganti</span>
				<input type=\"file\" id=\"files\" name=\"filename\" accept=\"zip\" />
				</span>
			</div>
		</div>

	</div>";
	$specialadmin .= "<input class=\"btn\" type=\"submit\" value=\""._UPLOAD."\">\r\n";
	$specialadmin .= "</div>";
	$specialadmin .= "</form>\r\n";
}
if ($action=="unzip") {
	//upload file zip
	$hasilupload = fiestoupload('filename',$cfg_temp_unzip_path,'',10000000,$allowedtypes="zip");	
	if ($hasilupload!=_SUCCESS) createmessage($hasilupload, _ERROR, "error", "");
	
	$zip = new ZipArchive;
	if ($zip->open($cfg_temp_unzip_path.'/'.$_FILES['filename']['name']) === TRUE) {
		//Inisialisasi awal
		$temp_namafile = '';
		$files_file = array();
		$files_modul = array();
		//Cari tahu nama file.zip tanpa .zip
		$namafilezip = explode('.',$_FILES['filename']['name']);
		//Proses input nama file
		for ($i=0; $i<$zip->numFiles;$i++) {
			$zippedfile = $zip->statIndex($i);
			// echo $zippedfile['name'].'<br>';
			$pos = substr($zippedfile['name'],0,7);
			if ($pos=='folder_'){
				//Masukkan semua alamat yang mengandung 'folder_'
				$files_file[] = $zippedfile['name'];
			}else{
				//Masukkan semua alamat non 'folder_'
				$files_modul[] = $zippedfile['name'];
			}
		}
		// print_r($files_file);
		// print_r($files_modul);
		// die();

		
		//Cek apakah sudah ada folder modul dengan nama yang sama yang akan diinstal. Jika ada, maka operasi dihentikan.
		if ($handle = opendir($cfg_temp_unzip_path)) {
			while (false !== ($file = readdir($handle))) {
				foreach($files_modul as $cekfoldernow){
					if($file.'/'==$cekfoldernow){
						$hentikan = true;
						break;
					}
				}
			}
		}
			
		//kirim kedalam folder modul/
		if ((count($files_modul)>0)&&($hentikan==false)){
			$admincontent .= "<h2>Folder modul extract.</h2>";
			if($zip->extractTo($cfg_temp_unzip_path, $files_modul)===TRUE){
				$idcountfolder = 0;
				foreach($files_modul as $countfolder){
					$admincontent .= "EXTRACT $files_modul[$idcountfolder] TO $cfg_temp_unzip_path<br />";
					$idcountfolder++;
				}
			}
		}
		//kirim kedalam folder file/
		if ((count($files_file)>0)&&($hentikan==false)){
			$admincontent .= "<h2>Folder file extract.</h2>";
			if($zip->extractTo($cfg_folder_path, $files_file)===TRUE){
				$idcountfolder = 0;
				foreach($files_file as $countfolder){
					$namafilebaru = str_replace("folder_", "", $files_file[$idcountfolder]);
					$admincontent .= "EXTRACT $namafilebaru TO $cfg_folder_path<br />";
					$idcountfolder++;
				}
			}
		}
		//tutup
		// $zip->close();
		// print_r($files_modul);
		// print_r($files_file);
		// die();
		
		//del file zip yang sudah di-extract
		// echo "<br>Temp: ".$cfg_temp_unzip_path.'/'.$_FILES['filename']['name'];
		$file_name = $_FILES['filename']['name'];
		$zip_path = "$cfg_temp_unzip_path/$file_name";
		// chmod($cfg_temp_unzip_path,0777);
		if (file_exists($zip_path)) {
			@unlink($zip_path);
		}
		
		//Proses database.sql start
		if((file_exists($sqlFileToExecute))&&($hentikan==false)){
			$file_content = file($sqlFileToExecute);
			$query = "";
			$admincontent .= "<h2>Database MySQL extract.</h2>";
			foreach($file_content as $sql_line){
				if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
					$query .= $sql_line;
					if (substr(rtrim($query), -1) == ';'){
						$result = mysql_query($query)or die(mysql_error());
						if(!$result){
							$sqlErrorCode = mysql_errno()."<br />";
							$sqlErrorText = mysql_error()."<br />";
							break;
						}else
							$admincontent .= $query."<br />";
						$query = "";
					}
				}
			}
			
		}
		//Proses database end
		//del file database.sql
		if((file_exists($sqlFileToExecute))&&($hentikan==false))
			unlink($sqlFileToExecute);
			
		//Proses rename file dengan imbuhan 'folder_' start
		$objects = scandir($cfg_folder_path);
		$bandingkan = "folder_";
		foreach ($objects as $object) {
			$pos = substr($object,0,7);
			if ($pos=='folder_'){
				$namafilebaru = str_replace("folder_", "", $object);
				rename($cfg_folder_path.'/'.$object, $cfg_folder_path.'/'.$namafilebaru);
			}
		}
		//end
		
		if (($sqlErrorCode == '')&&($hentikan==false)){
			// $specialadmin .= 'Installation was finished succesfully!';
			$specialadmin .= createmessage(_DBSUCCESS, _SUCCESS, "success", "");
		} else {
			if($hentikan==true)
				$specialadmin .= 'Modul sudah pernah terinstall dalam core, uninstall terlebih dahulu.';
			else{
				$specialadmin .= "An error occured during installation!";
				$specialadmin .= "Error code: $sqlErrorCode";
				$specialadmin .= "Error text: $sqlErrorText";
				$specialadmin .= "Statement:<br/> $sqlStmt";
			}
		}
	} else {
		// $specialadmin .= 'Gagal membuka file .zip';
		$specialadmin .= createmessage('Gagal membuka file .zip', _ERROR, "error", "");
	}
}
if($action!='') $specialadmin .= "<a class=\"btn\" href=\"?p=installer\">"._BACK."</a>";
?>
