<?php 

function get_dropdown($paket_id = '') {
	global $tipe_paket;
	
	$data = '';
	$data .= '<option value="">'._PILIH.'</option>';
	foreach($tipe_paket as $paket) {
		if ($paket_id) {
			$selected = ($paket_id == $paket) ? 'selected' : '';
		}
		$data .= '<option value="'.$paket.'" '.$selected.'>'.$paket.'</option>';
	}
	return $data;
}