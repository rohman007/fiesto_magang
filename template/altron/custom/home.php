<?php
ob_start();
$template = new TemplateForm();

include $cfg_app_path . '/modul/contact/urasi.php';

?>

    <!-- Page Content -->
	<!-- Slider Item -->
	<div id="slide-block">	
		<div id="slide-home" class="owl-carousel owl-theme">	
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
		<div id="banner-block" class="content-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="banner-thumbnail">
							<img src="<?php echo $template->image_url('image_banner1');?>">
							<div class="conten-thumbnail">
								<h1 class="title-banner"><?php echo $template->content('text_banner1');?></h1>
								<a href="<?php echo $template->content('link_banner1');?>">View Collection</a>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="banner-thumbnail">
							<img src="<?php echo $template->image_url('image_banner2');?>">
							<div class="conten-thumbnail">
								<h1 class="title-banner"><?php echo $template->content('text_banner2');?></h1>
								<a href="<?php echo $template->content('link_banner2');?>">View Collection</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="about-home" class="content-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-6 wow fadeInUp">
						<div class="content-about"><?php echo $template->content('title_contact');?></div>
						<a href="<?php echo $template->content('url_contact');?>" class="btn btn-default more">
							<?php echo $template->content('button_contact');?>
						</a>
					</div>
					<div class="col-md-6 wow fadeInUp">
						<img src="<?php echo $template->image_url('image_contact');?>" alt="Banner">
					</div>
				</div>
			</div>
		</div>
		<div id="catalog-home" class="content-wrapper">
			<div class="container">
				<div class="row wow fadeInUp">
					<div class="col-sm-12"><h1 class="page-header"><?php echo $template->content('title_catalog_block');?></h1></div>
					<div class="col-sm-12">
					<div id="slide-catalog" class="owl-carousel owl-theme">
					
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
					<div class="item">
						<div class="product-col">
							<div class="image-product">
								<a href="<?php echo $urlfunc->makePretty("?p=catalog&action=detail&pid=".$v['id'], $titleurl);?>">
										<img src="<?php echo "$cfg_app_url/file/catalog/small/".$filename;?>" alt="">
								</a>
							</div>
							<div class="caption">
							<div class="title-product">
								<h4>
									<a href="<?php echo $urlfunc->makePretty("?p=catalog&action=detail&pid=".$v['id'], $titleurl);?>"><?php echo $v['title'];?></a>
								</h4>
							</div>
							<div class="desc-catalog">
								<?php echo $v['ketsingkat'];?>
							</div>
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
		<div id="testi-block" class="content-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-sm-3">
						<h1 class="page-header"><?php echo $template->content('title-strength');?></h1>
					</div>
							<?php
							$blog_news=$template->getModulData(
							$modul_table="testi",
							$filter_var=array(),
							$filter_val=array(),//disini value untuk filter ambil dari variable di form.php 
							$field="id,nama,judul,filename,summary,ishot",
							$path_images="test/small",
							$limit=$template->content('testi_limit'),
							$order="id DESC",
							$custom_condition="ishot='1'");
							
							
							foreach($blog_news as $i => $v){
							list($thumb)=explode(":",$v['filename']);
							$titleurl = array();
							$titleurl["pid"] = $v['judul'];
						
							?>
							<div class="col-md-3 dept_col wow fadeInUp">
								<div class="testi-thumb list-services">
									<div class="testi_summary"><?php echo $v['summary'];?></div>
									<div class="footer-testi">
										<div class="testi_image"><img src="<?php echo "$cfg_app_url/file/testi/".$thumb;?>" alt=""></div>
										<div class="testi_content">
											<div class="testi_name"><?php echo $v['nama'];?></div>
											<div class="testi_title"><?php echo $v['judul'];?></div>
										</div>
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
		<div id="artikelhome" class="content-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-xs-12"><h1 class="page-header"><?php echo $template->content('news_testi');?></h1></div>
					<div class="col-xs-12">
						<div id="slide-testi">
						<div class="row">
							<?php
							$blog_news=$template->getModulData(
							$modul_table="newsdata",
							$filter_var=array("cat_id"),
							$filter_val=array($template->content('news_category')),//disini value untuk filter ambil dari variable di form.php
							$field="id,judulberita,summary,thumb,url,tglberita",
							$path_images="news/large",
							$limit=$template->content('news_limit'),
							$order="",
							$kondisi_tambahan="publish=1 AND ishot=1"
							);


							foreach($blog_news as $i => $v){
							list($thumb)=explode(":",$v['thumb']);
							$titleurl = array();
							$titleurl["pid"] = $v['judulberita'];
							$tanggal = date('F jS, Y', strtotime($v['tglberita']));

							?>
								<div class="col-sm-4">
								<div class="dept testi-content">
									<div class="dept_image"><img src="<?php echo "$cfg_app_url/file/news/large/".$thumb;?>" alt=""></div>
									<div class="dept_title"><?php echo $v['judulberita'];?></div>
									<div class="dept_date"><?php echo $tanggal;?></div>
									<div class="dept_subtitle"><?php echo $v['summary'];?></div>
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



<?php
$display_main_content_block .= ob_get_clean();
?>
