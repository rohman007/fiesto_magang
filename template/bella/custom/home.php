<?php
ob_start();
$template = new TemplateForm();

include $cfg_app_path . '/modul/contact/urasi.php';

?>

    <!-- Page Content -->
	<!-- Slider Item -->
	<div id="slide-block">	
		<div id="slide-home" class="owl-carousel owl-theme border-4" style="height: 580px; border-bottom: 15px solid rebeccapurple;">
			<div class="item">
				<a href="<?php echo $template->content('slide1_link');?>">
					<img class="mobile-hidden" src="<?php echo $template->image_url('slide1_img');?>" alt="Slide Image">
					<img class="dekstop-hidden" src="<?php echo $template->image_url('slidemobile1_img');?>" alt="Slide Image">
					<div id="content-slide"><div class="content-slide-line"><?php echo $template->content('slide_teks');?></div></div>
				</a>
			</div>
			<div class="item">
				<a href="<?php echo $template->content('slide2_link');?>">
				<img class="mobile-hidden" src="<?php echo $template->image_url('slide2_img');?>" alt="Slide Image">
				<img class="dekstop-hidden" src="<?php echo $template->image_url('slidemobile2_img');?>" alt="Slide Image">
				<div id="content-slide"><div class="content-slide-line"><?php echo $template->content('slide_teks2');?></div></div>
				</a>
			</div>
			<div class="item">
				<a href="<?php echo $template->content('slide3_link');?>">
				<img class="mobile-hidden" src="<?php echo $template->image_url('slide3_img');?>" alt="Slide Image">
				<img class="dekstop-hidden" src="<?php echo $template->image_url('slidemobile3_img');?>" alt="Slide Image">
				<div id="content-slide"><div class="content-slide-line"><?php echo $template->content('slide_teks3');?></div></div>
				</a>
			</div>
		</div>
	</div>
	<div id="content-center">
		<div id="about-home" class="content-wrapper" style="border-bottom: 5px solid #ddd">
			<h3 class="about_us_title">About Us</h3>
			<div class="container">
				<div class="row">
					<div class="col wow fadeInUp">
						<p class="content-about">
							<?php echo $template->content('title_contact');?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div id="catalog-home" class="content-wrapper">
			<div class="container">
				<div class="row wow fadeInUp">
					<div class="col-sm-12 disabled" style="display: none;"><h1 class="page-header"><?php echo $template->content('title_catalog_block');?></h1></div>
					<div class="col-sm-12"><h1 class="page-header">New Arrival</h1></div>
					<div class="col-sm-12">
						<div id="slide-catalog" class="owl-carousel owl-theme owl-loaded">
							<div class="owl-stage-outer">
								<div class="owl-stage d-flex">
									<?php
										$blog_news=$template->getModulData(
										$modul_table="catalogdata",
										$filter_var=array("cat_id"),
										$filter_val=array($template->content('catalog_category')),//disini value untuk filter ambil dari variable di form.php 
										$field="id,filename,title,publish,isbest,isnew,ispromo,harganormal,ketsingkat",
										$path_images="catalog/small",
										$limit=$template->content('product_limit'),
										$order="id ASC",
										$kondisi_tambahan=""
										);
										
										foreach($blog_news as $i => $v){
										list($filename)=explode(":",$v['filename']);
										$titleurl = array();
										$titleurl["pid"] = $v['title'];
										if ($isbest = 1) {
											$best="<div class='best-icon'><span>Best</span></div>";
										} else {
											$best="";
										}
										if ($isnew = 1) {
											$new="<div class='new-icon'><span>New</span></div>";
										} else {
											$new="";
										}
										if ($ispromo = 1) {
											$promo="<div class='promo-icon'><span>Promo</span></div>";
										} else {
											$promo="";
										}
									?>
									<div class="owl-item">
										<div class="card" style="width: 270px; cover; border: 2px solid #333; border-radius: 5px">
											<img src="<?php echo "$cfg_app_url/file/catalog/small/".$filename;?>" class="card-img-top" alt="..." style="height: 350px; object-fit: cover;">
											<div class="card-body text-center p-0">
												<div>
													<p class="card-text" style="margin: 15px 0 10px; font-size: 1.7rem;">Make Up Product Number One</p>
													<h4 class="card-title" style="margin: 0;">Rp. 200.000</h4>
												</div>
												<a href="#" target="_self" style="display: inline-block; width: 100%; padding: 20px; border-top: 2px solid #333; margin-top: 40px;">
													<i class="ri-shopping-cart-fill fs-4"></i> ADD TO CART
												</a>
											</div>
										</div>
									</div>
									<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
		</div>
		<div id="catalog-home" class="content-wrapper">
			<div class="container">
				<div class="row wow fadeInUp">
					<div class="col-sm-12 disabled" style="display: none;"><h1 class="page-header"><?php echo $template->content('title_catalog_block');?></h1></div>
					<div class="col-sm-12"><h1 class="page-header">Popular Item</h1></div>
					<div class="col-sm-12">
						<div id="slide-catalog" class="owl-carousel owl-theme owl-loaded">
							<div class="owl-stage-outer">
								<div class="owl-stage d-flex">
									<?php
										$blog_news=$template->getModulData(
										$modul_table="catalogdata",
										$filter_var=array("cat_id"),
										$filter_val=array($template->content('catalog_category')),//disini value untuk filter ambil dari variable di form.php 
										$field="id,filename,title,publish,isbest,isnew,ispromo,harganormal,ketsingkat",
										$path_images="catalog/small",
										$limit=$template->content('product_limit'),
										$order="id ASC",
										$kondisi_tambahan=""
										);
										
										foreach($blog_news as $i => $v){
										list($filename)=explode(":",$v['filename']);
										$titleurl = array();
										$titleurl["pid"] = $v['title'];
										if ($isbest = 1) {
											$best="<div class='best-icon'><span>Best</span></div>";
										} else {
											$best="";
										}
										if ($isnew = 1) {
											$new="<div class='new-icon'><span>New</span></div>";
										} else {
											$new="";
										}
										if ($ispromo = 1) {
											$promo="<div class='promo-icon'><span>Promo</span></div>";
										} else {
											$promo="";
										}
									?>
									<div class="owl-item">
										<div class="card" style="width: 270px; cover; border: 2px solid #333; border-radius: 5px">
											<img src="<?php echo "$cfg_app_url/file/catalog/small/".$filename;?>" class="card-img-top" alt="..." style="height: 350px; object-fit: cover;">
											<div class="card-body text-center p-0">
												<div>
													<p class="card-text" style="margin: 15px 0 10px; font-size: 1.7rem;">Make Up Product Number One</p>
													<h4 class="card-title" style="margin: 0;">Rp. 200.000</h4>
												</div>
												<a href="#" target="_self" style="display: inline-block; width: 100%; padding: 20px; border-top: 2px solid #333; margin-top: 40px;">
													<i class="ri-shopping-cart-fill fs-4"></i> ADD TO CART
												</a>
											</div>
										</div>
									</div>
									<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>



<?php
$display_main_content_block .= ob_get_clean();
?>
