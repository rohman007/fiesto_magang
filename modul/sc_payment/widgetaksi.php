<?php

if($jenis=='norekening'){
	$widget .= "<div id=\"widget_rekening\">\r\n";
	$sql = "SELECT logo,bank,cabang,norekening,atasnama FROM sc_payment";
	             $result = $mysql->query($sql);
                 
				 while(list($logo,$bank,$cabang,$norekening,$atasnama) = $mysql->fetch_row($result))
				 {
				  $widget .= "
				  <div class='widget_rekening'>
					  <div class='widget_rekening_logo'>
						<img src='".$cfg_payment_logo."/$logo' alt='$bank'>
					  </div>
					  <div class='widget_rekening_info'>
						$bank $cabang<br/>
						$norekening<br/>
						$atasnama<br/>
					  </div>
				  </div>";
				   
				  }
				  
	$widget .= "</div>\r\n";
}
?>
