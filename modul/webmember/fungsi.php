<?php
function get_nama_provider($id)
{
	global $mysql;
	$sql = "SELECT nama FROM provider WHERE id='$id'";
	$result = $mysql->query($sql);
	$hasil = $mysql->fetch_row($result);
	return $hasil[0];
}

function get_nominal_produk($id)
{
	global $mysql;
	$sql = "SELECT FLOOR(b.nominal) FROM provider a INNER JOIN produk b ON a.id=b.id_provider WHERE b.id='$id'";
	$result = $mysql->query($sql);
	$hasil = $mysql->fetch_row($result);
	return $hasil[0];
}

// function get_dropdown_level($level = '') {
	// $sql = "SELECT level, nama FROM webmember_level ORDER BY level";
	// $result = $mysql->query($sql);
	// if ($mysql->num_rows($result) > 0) {
		// $data = '';
		// while($row = $mysql->fetch_assoc($result)) {
			// $selected  = ($row['level'] == $level) ? "selected" : "";
			// $data .= '<option value="'.$row['level'].'" '.$selected.'>'.$row['nama'].'</option>';
		// }
	// }
	
	// return $data;
// }

// function view_level_name($level) {
	// $q = $mysql->query("SELECT nama FROM webmember_level WHERE level = '$level' LIMIT 1");
	// if ($mysql->num_rows($q) > 0) {
		// $row = $mysql->fetch_assoc($q);
		// return $row['nama'];
	// }
// }
?>
