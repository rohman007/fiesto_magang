<?php 
$cfg_fullsizepics_path = "$cfg_img_path/voucher/large";
$cfg_fullsizepics_url = "$cfg_img_url/voucher/large";
$cfg_thumb_path = "$cfg_img_path/voucher/small";
$cfg_thumb_url = "$cfg_img_url/voucher/small";

$maxfilesize = 500000;
$cfg_max_width = 600;
$modulename = 'voucher';
$discount_type = array(1 => _NOMINALRUPIAH, 2 => _PERCENTAGE/* , 3 => _HADIAH */);

ob_start();
echo <<<SCRIPT
<script>

	$(function() {
		var is_Vonetime = $('input[name="is_onetime"]');
		is_Vonetime.on('click', function() {
			if (is_Vonetime.is(':checked')) {
				$('#start-date, #end-date').hide();
			} else {
				$('#start-date, #end-date').show();
			}
		})
	})
</script>
SCRIPT;

$script_js['voucher'] = ob_get_clean();
