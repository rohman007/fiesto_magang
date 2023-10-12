<footer>
	<div id="footerlink">
		<div class="container">
			<div class="row">
				<div class="col-sm-4 wow fadeInUp">
					<?php echo $template->content('footerlink_1');?>
				</div>
				<div class="col-sm-4 wow fadeInUp">
					<?php echo $template->content('footerlink_2');?>
				</div>
				<div class="col-sm-4 wow fadeInUp">
					<?php echo $template->content('footerlink_3');?>
				</div>
			</div>
		</div>
	</div>
	<div id="copyright">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<?php echo $template->content('copyright');?>
				</div>
			</div>
		</div>
	</div>
</footer>
	
	<!--<div id="button-mobile">
		<div class="menu-button-mobile">
			<ul class="list-inline">
				<?php if ($template->content('phone_number') != "") {?>
				<li><a href="tel:<?php echo $template->content('phone_number');?>"><span class="button-phone-icon"></span></a></li>
				<?php } ?>
				<?php if ($template->content('wa_number') != "") {?>
				<li><a href="https://api.whatsapp.com/send?phone=<?php echo $template->content('wa_number');?>"><span class="button-wa-icon"></span></a></li>
				<?php } ?>
				<?php if ($template->content('email_link') != "") {?>
				<li><a href="mailto:<?php echo $template->content('email_link');?>"><span class="button-email-icon"></span></a></li>
				<?php } ?>
				<?php if ($template->content('map_link') != "") {?>
				<li><a href="<?php echo $template->content('map_link');?>"><span class="button-map-icon"></span></a></li>
				<?php } ?>
				<?php if ($template->content('line_link') != "") {?>
				<li><a href="tel:<?php echo $template->content('line_link');?>"><span class="button-line-icon"></span></a></li>
				<?php } ?>
				<?php if ($template->content('bbm_link') != "") {?>
				<li><a href="bbmi://<?php echo $template->content('bbm_link');?>"><span class="button-bbm-icon"></span></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>-->
