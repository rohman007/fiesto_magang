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

	<!-- <section class="slide d-flex align-items-center p-4">
      <div class="container p-0 d-flex justify-content-center ">
        <div class="row d-flex justify-content-between w-100  text-white text-center flex-column flex-lg-row ">
          <div class="col d-flex justify-content-center align-items-center">
            <i class="icon1 ri-thumb-up-fill"></i>
            <h5 class="paraf m-0 ms-2">New Item Every Week</h5>
          </div>
          <div class="col d-flex justify-content-center align-items-center">
            <i class="icon1 ri-time-line"></i>
            <h5 class="paraf m-0 ms-2">Stay Up to Date</h5>
          </div>
          <div class="col d-flex justify-content-center align-items-center">
            <i class="icon1 ri-shield-star-fill"></i>
            <h5 class="paraf m-0 ms-2">Huge collections</h5>
          </div>
        </div>
      </div>
    </section> -->

	<div id="content-center">

		<div id="about-home" class="content-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-4 wow fadeInUp">
						<div class="content-about"><?php echo $template->content('title_contact');?></div>
						<a href="<?php echo $template->content('url_contact');?>" class="btn btn-default more">
							<?php echo $template->content('button_contact');?>
						</a>
					</div>
					<div class="col-md-4 wow fadeInUp">
						<img src="<?php echo $template->image_url('image_contact');?>" alt="Banner">
					</div>
				</div>
			</div>
		</div>


	</div>
	<div id="catalog-home" class="content-wrapper">
		<div class="container">
			<div class="row wow fadeInUp">
				<div class="col-sm-8"><h1 class="page-header"><?php echo $template->content('title_catalog_block');?></h1></div>
				<div class="col-sm-8">
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



<?php
$display_main_content_block .= ob_get_clean();
?>
