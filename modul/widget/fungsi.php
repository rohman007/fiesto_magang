<?php
function isselected($blockname,$posisi) {
	if ($blockname==$posisi) {
		return 'selected';
	} else {
		return '';
	}
}
?>