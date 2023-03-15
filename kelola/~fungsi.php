<?php
function datamenu($paket) {
    $menur = array();
    switch ($paket) {
        case 1: //toko online
			$menur[1][] = array('catalog', 'icon-circle-blank', _PRODUCTDASHBOARD, '0,1', 1);
			//$menur[1][] = array('catalog&action=add', 'icon-circle-blank', _ADDPRODUCT, '0,1', 2);
            $menur[1][] = array('catalog&action=main', 'icon-circle-blank', _CATEGORYDASHBOARD, '0,1', 3);
            $menur[1][] = array('brand', 'icon-circle-blank', _BRANDDASHBOARD, '0,1', 4);
            $menur[1][] = array('voucher', 'icon-circle-blank', _VOUCHERDASHBOARD, '0,1', 5);
            // $menur[1][] = array('catalog&action=attribut_add', 'icon-circle-blank', _ADDSPECDASHBOARD, '0,1', 5);
            $menur[2][] = array('order&action=step1', 'icon-circle-blank', _NEWORDERDASHBOARD, '0,1', 1);
            $menur[2][] = array('order&action=step3', 'icon-circle-blank', _READYORDERDASHBOARD, '0,1', 2);
            $menur[2][] = array('order&action=timeinterval', 'icon-circle-blank', _SALESREPORTDASHBOARD, '0,1', 3);
			// $menur[2][] = array('sc_payment', 'icon-circle-blank', _PAYMENTDASHBOARD, '0,1', 4);
			// $menur[2][] = array('sc_default', 'icon-circle-blank', _MANAGEPOSTAGEDASHBOARD, '0,1', 4);
			// $menur[2][] = array('pembayaran_gadget', 'icon-circle-blank', _PEMBAYARANDASHBOARD, '0,1', 4);
            $menur[3][] = array('site&action=dashboard', 'icon-circle-blank', _SETTINGSDASHBOARD, '0,1', 1);
            $menur[3][] = array('sc_payment', 'icon-circle-blank', _PAYMENTDASHBOARD, '0,1', 2);
            $menur[3][] = array('sc_postage', 'icon-circle-blank', _POSTAGEDASHBOARD, '0,1', 3);
			$menur[3][] = array('sc_postage&action=manual', 'icon-circle-blank', _POSTAGEINTERNALDASHBOARD, '0,1', 3);
            // $menur[3][] = array('sc_jne', 'icon-circle-blank', _POSTAGEJNEDASHBOARD, '0,1', 3);
            $menur[3][] = array('site', 'icon-circle-blank', _CONFIGDASHBOARD, '0,1', 4);
            $menur[3][] = array('contact', 'icon-circle-blank', _CONTACTDASHBOARD, '0,1', 5);
            $menur[3][] = array('translation', 'icon-circle-blank', _TERMSDASHBOARD, '0,1', 6);
            $menur[3][] = array('installer', 'icon-circle-blank', _INSTALLERDASHBOARD, '0,1', 6);
            $menur[3][] = array('uninstaller', 'icon-circle-blank', _UNINSTALLERDASHBOARD, '0,1', 6);
            $menur[4][] = array('template_option', 'icon-circle-blank', _TEMPLATEOPTIONDASHBOARD, '0,1', 2);
            $menur[4][] = array('decoration', 'icon-circle-blank', _DEKORASIDASHBOARD, '0,1', 2);
            $menur[4][] = array('menu', 'icon-circle-blank', _MENUDASHBOARD, '0,1', 3);
            $menur[4][] = array('home', 'icon-circle-blank', _HOMEDASHBOARD, '0,1', 4);
            $menur[4][] = array('widget', 'icon-circle-blank', _WIDGETDASHBOARD, '0,1', 5);
            $menur[5][] = array('page', 'icon-circle-blank', _PAGEDASHBOARD, '0,1', 1);
			$menur[5][] = array('news', 'icon-circle-blank', _NEWSDASHBOARD, '0,1', 1);
			$menur[5][] = array('event', 'icon-circle-blank', _AGENDADASHBOARD, '0,1', 2);
			$menur[5][] = array('gallery', 'icon-circle-blank', _GALLERYDASHBOARD, '0,1', 3);
            // $menur[5][] = array('counter', 'icon-circle-blank', _STATISTICSDASHBOARD, '0,1', 2);
            // $menur[5][] = array('ym', 'icon-circle-blank', _SUPPORTDASHBOARD, '0,1', 3);
            // $menur[5][] = array('testi', 'icon-circle-blank', _TESTIDASHBOARD, '0,1', 4);
            $menur[5][] = array('testimonial', 'icon-circle-blank', _TESTIDASHBOARD, '0,1', 4);
            $menur[5][] = array('banner', 'icon-circle-blank', _BANNERDASHBOARD, '0,1', 5);
            $menur[5][] = array('member', 'icon-circle-blank', _MEMBERDASHBOARD, '0,1', 6);
            $menur[5][] = array('webmember', 'icon-circle-blank', _WEBMEMBERDASHBOARD, '0,1', 7);			
			// $menur[5][] = array('video', 'icon-circle-blank', _VIDEODASHBOARD, '0,1', 5);
            //$menur[5][] = array('webmember_level', 'icon-circle-blank', _WEBMEMBERLEVELDASHBOARD, '0,1', 7);
            break;
        case 2: //non toko online
            $menur[1][] = array('news', 'icon-circle-blank', _NEWSDASHBOARD, '0,1', 1);
            $menur[1][] = array('event', 'icon-circle-blank', _AGENDADASHBOARD, '0,1', 2);
            $menur[1][] = array('gallery', 'icon-circle-blank', _GALLERYDASHBOARD, '0,1', 3);
            $menur[2][] = array('catalog', 'icon-circle-blank', _PRODUCTDASHBOARD, '0,1', 1);
            $menur[2][] = array('catalog&action=main', 'icon-circle-blank', _CATEGORYDASHBOARD, '0,1', 2);
            $menur[2][] = array('brand', 'icon-circle-blank', _BRANDDASHBOARD, '0,1', 3);
            $menur[3][] = array('site&action=dashboard', 'icon-circle-blank', _SETTINGSDASHBOARD, '0,1', 1);
			$menur[4][] = array('template_option', 'icon-circle-blank', _TEMPLATEOPTIONDASHBOARD, '0,1', 2);

            $menur[4][] = array('decoration', 'icon-circle-blank', _DEKORASIDASHBOARD, '0,1', 2);
            $menur[5][] = array('page', 'icon-circle-blank', _PAGEDASHBOARD, '0,1', 1);
            $menur[5][] = array('counter', 'icon-circle-blank', _STATISTICSDASHBOARD, '0,1', 2);
           
            $menur[5][] = array('ym', 'icon-circle-blank', _SUPPORTDASHBOARD, '0,1', 4);
			$menur[5][] = array('video', 'icon-circle-blank', _VIDEODASHBOARD, '0,1', 5);
            $menur[6][] = array('catalog&action=attribut_add', 'icon-circle-blank', _ADDSPECDASHBOARD, '0,1', 1);
            $menur[7][] = array('site', 'icon-circle-blank', _CONFIGDASHBOARD, '0,1', 1);
            $menur[7][] = array('contact', 'icon-circle-blank', _CONTACTDASHBOARD, '0,1', 3);
            $menur[7][] = array('translation', 'icon-circle-blank', _TERMSDASHBOARD, '0,1', 2);
			$menur[8][] = array('menu', 'icon-circle-blank', _MENUDASHBOARD, '0,1', 3);
            $menur[8][] = array('home', 'icon-circle-blank', _HOMEDASHBOARD, '0,1', 2);
            $menur[8][] = array('widget', 'icon-circle-blank', _WIDGETDASHBOARD, '0,1', 4);
            $menur[9][] = array('banner', 'icon-circle-blank', _BANNERDASHBOARD, '0,1', 1);
            $menur[9][] = array('download', 'icon-circle-blank', _DOWNLOADDASHBOARD, '0,1', 3);
            $menur[9][] = array('testi', 'icon-circle-blank', _TESTIMONIALDASHBOARD, '0,1', 2);
            $menur[9][] = array('link', 'icon-circle-blank', _LINKDASHBOARD, '0,1', 2);
            $menur[9][] = array('poll', 'icon-circle-blank', _POLLDASHBOARD, '0,1', 2);
			// $menur[101][] = array('news', 'icon-circle-blank', _NEWSDASHBOARD, '0,1', 1);
			// $menur[101][] = array('news&action=main', 'icon-circle-blank', _CATEGORYDASHBOARD, '0,1', 1);
			// $menur[101][] = array('news&action=clean', 'icon-circle-blank', _DELOLDNEWS, '0,1', 1);
			// $menur[102][] = array('gallery', 'icon-circle-blank', _GALLERYDASHBOARD, '0,1', 1);
			// $menur[102][] = array('gallery&action=uploadzip', 'icon-circle-blank', _UPLOADZIP, '0,1', 1);
			// $menur[102][] = array('gallery&action=bulkedit', 'icon-circle-blank', _BULKEDIT, '0,1', 1);
			
			
			
            break;
    }
    return $menur;
}

function rootmenubuilder($paket, $parsedurl, $parsedaction, $level) {
    global $idpaketwebsite;
    global $mysql;
    $rootgaptekbuilder = "";
    $menugaptekbuilder = "";
    $rootcanggihbuilder = "";
    $menucanggihbuilder = "";

    $gaptekmodelist = array(1, 2, 3, 4, 5);
    // $gaptekmodelist = array(1, 2, 4, 5, 6);
    //$canggihmodelist = array(6, 7, 8, 9);

    /* SPECIAL ONLY FOR SPECIAL OCCASION: order, main, site */
    if ($parsedurl == "catalog") {
        if ($parsedaction == "") {
            $parsedurl = "catalog";
        } else if ($parsedaction == "attribut_add") {
            $parsedurl = "catalog&action=attribut_add";
        } else if ($parsedaction == "main") {
            $parsedurl = "catalog&action=main";
        } else {//OTHER ACTION
            $parsedurl = "catalog";
        }
    } else if ($parsedurl == "order") {
        if ($parsedaction == "") {
            $parsedurl = "order&action=step1";
        } else if ($parsedaction == "step1") {
            $parsedurl = "order&action=step1";
        } else if ($parsedaction == "step3") {
            $parsedurl = "order&action=step3";
        } else if ($parsedaction == "timeinterval") {
            $parsedurl = "order&action=timeinterval";
        } else {//OTHER ACTION
            $parsedurl = "order&action=step1";
        }
    } else if ($parsedurl == "site") {
        if ($parsedaction == "") {
            $parsedurl = "site";
        } else if ($parsedaction == "dashboard") {
            $parsedurl = "site&action=dashboard";
        } else {//OTHER ACTION
            $parsedurl = "site";
        }
    } else if ($parsedurl == "sc_postage") {
        if ($parsedaction == "") {
            $parsedurl = "sc_postage";
        } else if ($parsedaction == "manual") {
            $parsedurl = "sc_postage&action=manual";
        } else {//OTHER ACTION
            $parsedurl = "sc_postage";
        }
    }
	//else if ($parsedurl == "news") {
        // if ($parsedaction == "") {
            // $parsedurl = "news";
        // } else if ($parsedaction == "main") {
            // $parsedurl = "news&action=main";
        // } else if ($parsedaction == "home") {
            // $parsedurl = "news";
        // } else {//OTHER ACTION
            // $parsedurl = "news";
        // }
   // }
	// else if ($parsedurl == "gallery") {
        // if ($parsedaction == "") {
            // $parsedurl = "gallery";
        // } else if ($parsedaction == "main") {
            // $parsedurl = "gallery&action=main";
        // } else if ($parsedaction == "bulkedit") {
            // $parsedurl = "gallery&action=bulkedit";
        // } else if ($parsedaction == "uploadzip") {
            // $parsedurl = "gallery&action=uploadzip";
        // } else if ($parsedaction == "home") {
            // $parsedurl = "gallery";
        // } else {//OTHER ACTION
            // $parsedurl = "gallery";
        // }
    // }

    switch ($paket) {
        case 1: //toko online
            $rootgaptekbuilder .= adminrootmenu('icon-th-large', 1, _NAVIGATIONCATEGORY2, $parsedurl);
            $rootgaptekbuilder .= adminrootmenu('icon-shopping-cart', 2, _NAVIGATIONCATEGORY1OLSHOP, $parsedurl);
			$rootgaptekbuilder .= adminrootmenu('icon-cog', 3, _NAVIGATIONCATEGORY7, $parsedurl);
            $rootgaptekbuilder .= adminrootmenu('icon-desktop', 4, _NAVIGATIONCATEGORY4, $parsedurl);
            $rootgaptekbuilder .= adminrootmenu('icon-star', 5, _NAVIGATIONCATEGORY5, $parsedurl);
            // $rootgaptekbuilder .= adminrootmenu('icon-cog', 6, _NAVIGATIONCATEGORY10, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 1, _NAVIGATIONCATEGORY2, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 2, _NAVIGATIONCATEGORY1OLSHOP, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 3, _NAVIGATIONCATEGORY7, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 4, _NAVIGATIONCATEGORY4, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 5, _NAVIGATIONCATEGORY5, $parsedurl);
            // $menugaptekbuilder .= adminmenu($level, 6, _NAVIGATIONCATEGORY10, $parsedurl);
            break;
        case 2: //non toko online
            $rootgaptekbuilder .= adminrootmenu('icon-bullhorn', 1, _NAVIGATIONCATEGORY1NONOLSHOP, $parsedurl);
            $rootgaptekbuilder .= adminrootmenu('icon-th-large', 2, _NAVIGATIONCATEGORY2, $parsedurl);
            $rootgaptekbuilder .= adminrootmenu('icon-cog', 3, _NAVIGATIONCATEGORY7, $parsedurl);
            $rootgaptekbuilder .= adminrootmenu('icon-desktop', 4, _NAVIGATIONCATEGORY4, $parsedurl);
            $rootgaptekbuilder .= adminrootmenu('icon-star', 5, _NAVIGATIONCATEGORY5, $parsedurl);
            $rootcanggihbuilder .= adminrootmenu('icon-th-large', 6, _NAVIGATIONCATEGORY6, $parsedurl);
            $rootcanggihbuilder .= adminrootmenu('icon-cog', 7, _NAVIGATIONCATEGORY7, $parsedurl);
            $rootcanggihbuilder .= adminrootmenu('icon-desktop', 8, _NAVIGATIONCATEGORY8, $parsedurl);
            $rootcanggihbuilder .= adminrootmenu('icon-star', 9, _NAVIGATIONCATEGORY9, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 1, _NAVIGATIONCATEGORY1NONOLSHOP, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 2, _NAVIGATIONCATEGORY2, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 3, _NAVIGATIONCATEGORY7, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 4, _NAVIGATIONCATEGORY4, $parsedurl);
            $menugaptekbuilder .= adminmenu($level, 5, _NAVIGATIONCATEGORY5, $parsedurl);
            $menucanggihbuilder .= adminmenu($level, 6, _NAVIGATIONCATEGORY6, $parsedurl);
            $menucanggihbuilder .= adminmenu($level, 7, _NAVIGATIONCATEGORY7, $parsedurl);
            $menucanggihbuilder .= adminmenu($level, 8, _NAVIGATIONCATEGORY8, $parsedurl);
            $menucanggihbuilder .= adminmenu($level, 9, _NAVIGATIONCATEGORY9, $parsedurl);
            break;
    }

    $datamenu = datamenu($idpaketwebsite);
	$group = -1;
    foreach ($datamenu as $i => $tempval) {
        foreach ($datamenu[$i] as $menu) {
            list($nama, $icontbl, $judul, $access, $urutan) = $menu;
            if ($parsedurl == "" && $i == 1) {
                $group = $i;
                break;
            } else if ($nama == $parsedurl) {
                $group = $i;
                break;
            }
			
        }
        if ($group != -1) {
            break;
        }
    }

    if (in_array($group, $gaptekmodelist)) {
        $gaptekselector = "active";
        $canggihselector = "";
        $gaptekmode = "block";
        $canggihmode = "none";
    } else if (in_array($group, $canggihmodelist)) {
        $gaptekselector = "";
        $canggihselector = "active";
        $gaptekmode = "none";
        $canggihmode = "block";
    }
	
    $menu = "
        <script type=\"text/javascript\">
        function showGaptek() {
            document.getElementById('gaptekselector').className = 'active';
            document.getElementById('canggihselector').className = '';
            document.getElementById('gaptek').style.display = 'block';
            document.getElementById('canggih').style.display = 'none';
            document.getElementById('group1').className = 'active';
            document.getElementById('group2').className = '';
            document.getElementById('group4').className = '';
            document.getElementById('group5').className = '';
            document.getElementById('group6').className = '';
            document.getElementById('group7').className = '';
            document.getElementById('group8').className = '';
            document.getElementById('group3').className = '';
            document.getElementById('group9').className = '';
            document.getElementById('menu1').className = 'tab-pane active';
            document.getElementById('menu2').className = 'tab-pane';
            document.getElementById('menu3').className = 'tab-pane';
            document.getElementById('menu4').className = 'tab-pane';
            document.getElementById('menu5').className = 'tab-pane';
            document.getElementById('menu6').className = 'tab-pane';
            document.getElementById('menu7').className = 'tab-pane';
            document.getElementById('menu8').className = 'tab-pane';
            document.getElementById('menu9').className = 'tab-pane';
            
            
        }
        function showCanggih() {
            document.getElementById('gaptekselector').className = '';
            document.getElementById('canggihselector').className = 'active';
            document.getElementById('gaptek').style.display = 'none';
            document.getElementById('canggih').style.display = 'block';
            document.getElementById('group1').className = '';
            document.getElementById('group2').className = '';
            document.getElementById('group3').className = '';
            document.getElementById('group4').className = '';
            document.getElementById('group5').className = '';
            document.getElementById('group6').className = 'active';
            document.getElementById('group7').className = '';
            document.getElementById('group8').className = '';
            document.getElementById('group9').className = '';
            document.getElementById('menu1').className = 'tab-pane';
            document.getElementById('menu2').className = 'tab-pane';
            document.getElementById('menu3').className = 'tab-pane';
            document.getElementById('menu4').className = 'tab-pane';
            document.getElementById('menu5').className = 'tab-pane';
            document.getElementById('menu6').className = 'tab-pane active';
            document.getElementById('menu7').className = 'tab-pane';
            document.getElementById('menu8').className = 'tab-pane';
            document.getElementById('menu9').className = 'tab-pane';
            
        }
        </script>
        <div class=\"left-nav clearfix\">
                    <div class=\"responsive-leftbar\">
                        <i class=\"icon-list\"></i>
                    </div>
                    <!--<div class=\"left-selector-nav\">
                        <ul>
                            <li id=\"gaptekselector\" class=\"$gaptekselector\"><a href=\"#\" onclick=\"showGaptek()\">Simple</a></li> 
                            <li id=\"canggihselector\" class=\"$canggihselector\"><a href=\"#\" onclick=\"showCanggih()\">Advanced</a></li> 
                        </ul>
                    </div>-->
                    <div class=\"left-primary-nav\">
                        <div id=\"gaptek\" style=\"display: $gaptekmode;\">
                            <ul id=\"myTab\">
                                    $rootgaptekbuilder
                            </ul>
                        </div>
                        <div id=\"canggih\" style=\"display: $canggihmode;\">
                            <ul id=\"myTab\">
                                    $rootcanggihbuilder
                            </ul>
                        </div>
                    </div>
                    <div class=\"left-secondary-nav tab-content\">
                                $menugaptekbuilder
                                $menucanggihbuilder
                    </div>
                </div>
        ";
    return $menu;
}

function adminrootmenu($icon, $group, $title, $page) 
{
    global $cfg_app_path, $cfg_app_url, $lang, $heatmapurl, $gaurl, $iscustomdesign, $idpaketwebsite;
    $active = "";
    $datamenu = datamenu($idpaketwebsite);
	if (count($datamenu[$group]) > 0) {
		foreach ($datamenu[$group] as $menu) {
			list($nama, $icontbl, $judul, $access, $urutan) = $menu;
			if ($page == "" && $group == 1) {
				$active = "active";
			} else if ($nama == $page) {
				$active = "active";
			}
		}
	}
    $rootmenu = "<li class=\"$active\" id=\"group$group\"><a href=\"#menu$group\" class=\"$icon\" title=\"$title\"></a></li>";
    return $rootmenu;
}

function adminmenu($level, $group, $title, $page) {
    global $mysql;
    global $cfg_app_path, $cfg_app_url, $lang, $heatmapurl, $gaurl, $iscustomdesign, $idpaketwebsite;
    $icon_active = "icon-circle-arrow-right";
    $builtmenu = "";
    $active = "";
    //while (list($id, $grup, $nama, $icon, $judul, $access, $urutan) = $mysql->fetch_row($result)) {
    $datamenu = datamenu($idpaketwebsite);
	if (count($datamenu[$group]) > 0) {
		foreach ($datamenu[$group] as $menu) {
			list($nama, $icon, $judul, $access, $urutan) = $menu;
			if ($page == "" && $group == 1) {
				$active = "active";
			} else if ($nama == $page) {
				$active = "active";
			}
			$splitaccess = explode(",", $access);
			foreach ($splitaccess as $assigned) {
				if ($nama == 'pseudo') {
					switch ($judul) {
						case 'Statistik':
							if ($level == $assigned) {
								$builtmenu .= "<li><a href=\"https://www.google.com/analytics/reporting/login\"><i class=\"$icon\"></i>$judul</a></li>\r\n";
							}
							break;
					}
				} else {
					if ($level == $assigned) {
						switch ($nama) {
							case 'member':
								$boleh = ($level == 0) ? true : false;
								break;
							case 'template':
								$boleh = (!$iscustomdesign) ? true : false;
								break;
							default:
								$boleh = true;
						}
						if ($boleh) {
							if ($nama == $page) {
								$builtmenu .= "<li><a class=\"item-active\" href=\"$cfg_app_url/kelola/index.php?p=$nama\" id=\"$nama\"><i class=\"$icon_active\"></i>$judul</a></li>\r\n";
							} else {
								$builtmenu .= "<li><a class=\"\" href=\"$cfg_app_url/kelola/index.php?p=$nama\" id=\"$nama\"><i class=\"$icon\"></i>$judul</a></li>\r\n";
							}
						}
					}
				}
			}
		}
	}
    $menu = "<div class=\"tab-pane $active\" id=\"menu$group\">\r\n
        <h4 class=\"side-head\">$title</h4>\r\n
        <ul id=\"nav\" class=\"accordion-nav\">\r\n
            $builtmenu
        </ul>\r\n
    </div>\r\n";
    return $menu;
}

function back($url) {
    header("Location:?p=$url");
}
function stepnavigation($paket)
{

	global $step_left,$step_right;
	$step=$_REQUEST['step'];
	if($step!='')
	{
		//ob_start();

		switch ($paket) 
		{
		case 1:
			$urutan[1]="?p=site&action=dashboard";
			$urutan[2]="?p=template";
			$urutan[3]="?p=decoration";
			$urutan[4]="?p=catalog&&action=add";	
			$urutan[5]="?p=sc_payment";	
			$urutan[6]="?p=sc_postage";	
			$urutan[7]="?p=site&action=jualan";	
			break;
		case 2:
			$urutan[1]="?p=site&action=dashboard";
			$urutan[2]="?p=template";
			$urutan[3]="?p=decoration";
			$urutan[4]="?p=page";	
			$urutan[5]="?p=gallery&action=add&cat_id=0";	
			$urutan[6]="?p=site&action=jualan";	
			break;
		}

		if($urutan[$step-1]!=''){	$step_left= "<a class='icon-circle-arrow-left' href='".param_url($urutan[$step-1]."&step=".($step-1))."'></a>";	}
		if($urutan[$step+1]!=''){	$step_right= "<a class='icon-circle-arrow-right' href='".param_url($urutan[$step+1]."&step=".($step+1))."'></a>";	}
		//return ob_get_clean();
	}
}
function simpleadminmenu($paket) {
global $step_left,$step_right,$status;
$stepnavigation = stepnavigation($paket);
    switch ($paket) {
        case 1:
			    $simpleadminmenu = "
                <div class=\"switch-board gray\">
				<div class=\"widget-header-block\">
				<h4 class=\"widget-header\">" . _FIRSTSTEPSDASHBOARD . "</h4>
				</div>
				<div class=\"firststep prev\">$step_left</div>
				<div class=\"firststep next\">$step_right</div>
                <ul class=\"clearfix switch-item\">
                <li><span class=\"notify-tip\">1</span><a href=\"".param_url("?p=site&action=dashboard&step=1")."\" class=\"orange ".($_GET['step']=='1'?"choosen":"")."\"><i class=\"icon-cogs\"></i><span>Setting</span></a></li>
                <li><span class=\"notify-tip\">2</span><a href=\"".param_url("?p=template&step=2")."\" class=\"red ".($_GET['step']=='2'?"choosen":"")."\"><i class=\"icon-magic\"></i><span>Template</span></a></li>
				<li><span class=\"notify-tip\">3</span><a href=\"".param_url("?p=decoration&step=3")."\" class=\"blue-violate ".($_GET['step']=='3'?"choosen":"")."\"><i class=\"icon-camera-retro\"></i><span>Dekorasi</span></a></li>
				<li><span class=\"notify-tip\">4</span><a href=\"".param_url("?p=catalog&&action=add&step=4")."\" class=\"green ".($_GET['step']=='4'?"choosen":"")."\"><i class=\"icon-th-large\"></i><span>Produk</span></a></li>
                <li><span class=\"notify-tip\">5</span><a href=\"".param_url("?p=sc_payment&step=5")."\" class=\"blue ".($_GET['step']=='5'?"choosen":"")."\"><i class=\"icon-money\"></i><span>Pembayaran</span></a></li>
                <li><span class=\"notify-tip\">6</span><a href=\"".param_url("?p=sc_postage&step=6")."\" class=\"violet ".($_GET['step']=='6'?"choosen":"")."\"><i class=\"icon-truck\"></i><span>Pengiriman</span></a></li>
				";
				if($status!=1)
				{
				$simpleadminmenu .= "
                <li><span class=\"notify-tip\">7</span><a href=\"".param_url("?p=site&action=launch&step=7")."\" class=\"bondi-blue ".($_GET['step']=='7'?"choosen":"")."\"><i class=\"icon-plane\"></i><span>Launch</span></a></li>
				";
				}
				$simpleadminmenu .= "
				</ul>
				
                </div>";
            break;
        case 2:
            $simpleadminmenu = "
                <div class=\"switch-board gray\">
				<div class=\"widget-header-block\">
				<h4 class=\"widget-header\">" . _FIRSTSTEPSDASHBOARD . "</h4>
				<div class=\"firststep prev\">$step_left</div>
				<div class=\"firststep next\">$step_right</div>
				</div>
                <ul class=\"clearfix switch-item\">
                <li><span class=\"notify-tip\">1</span><a href=\"".param_url("?p=site&action=dashboard&step=1")."\" class=\"orange ".($_GET['step']=='1'?"choosen":"")."\"><i class=\"icon-cogs\"></i><span>Setting</span></a></li>
				<li><span class=\"notify-tip\">2</span><a href=\"".param_url("?p=template&step=2")."\" class=\"red ".($_GET['step']=='2'?"choosen":"")."\"><i class=\"icon-magic\"></i><span>Template</span></a></li>
				<li><span class=\"notify-tip\">3</span><a href=\"".param_url("?p=decoration&step=3")."\" class=\"blue-violate ".($_GET['step']=='3'?"choosen":"")."\"><i class=\"icon-camera-retro\"></i><span>Dekorasi</span></a></li>
                <li><span class=\"notify-tip\">4</span><a href=\"".param_url("?p=page&step=4")."\" class=\"blue ".($_GET['step']=='4'?"choosen":"")."\"><i class=\"icon-file\"></i><span>Halaman</span></a></li>
                <li><span class=\"notify-tip\">5</span><a href=\"".param_url("?p=gallery&action=add&cat_id=0&step=5")."\" class=\"green ".($_GET['step']=='5'?"choosen":"")."\"><i class=\"icon-picture\"></i><span>Galeri</span></a></li>";
				if($status!=1)
				{
				$simpleadminmenu .= "
                <li><span class=\"notify-tip\">6</span><a href=\"".param_url("?p=site&action=launch&step=6")."\" class=\"bondi-blue ".($_GET['step']=='6'?"choosen":"")."\"><i class=\"icon-plane\"></i><span>Launch</span></a></li>
				";
				}
			$simpleadminmenu.="	
                </ul>
                </div>";
            break;
    }
    return $simpleadminmenu;
}

function dasboarditem($paket) 
{
global $mysql;
    switch ($paket) {
        case 1: //toko online
            $s = $mysql->query("SELECT (SELECT count(*) FROM sc_transaction WHERE status=1 or status=2) new, (SELECT count(*) FROM sc_transaction WHERE status=3) waitsend");
            list($neworder,$waitsendorder,$confirmorder) = $mysql->fetch_row($s);
            if ($neworder == null || $neworder == "") {
                $neworder = 0;
            }
            if ($confirmorder == null || $confirmorder == "") {
                $confirmorder = 0;
            }
            if ($waitsendorder == null || $waitsendorder == "") {
                $waitsendorder = 0;
            }
            $dashboarditem = "
		
				<div class=\"row-fluid\">
					<div class=\"span12\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\"><i class=\"icon-shopping-cart \"></i> ". _SALESDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">

									<div class=\"span6\">
										<div class=\"board-widgets blue small-widget span6\">
											 <a href=\"?p=order&action=step1\">
											 <span class=\"widget-stat\">$neworder</span>
											 <span class=\"widget-label\">" . _NEWORDERDASHBOARD . "</span>
											 <span class=\"widget-icon icon-shopping-cart\"></span>
											 </a>
										</div>
                                                                                <div class=\"board-widgets blue small-widget span6\">
											 <a href=\"?p=order&action=step3\">
											 <span class=\"widget-stat\">$waitsendorder</span>
											 <span class=\"widget-label\">" . _READYORDERDASHBOARD . "</span>
											 <span class=\"widget-icon icon-truck\"></span>
											 </a>
										</div>
									</div>
									<div class=\"span6\">
										<div class=\"board-widgets blue small-widget span6\">
											 <a>
											 <span class=\"widget-label\">" . _SEARCHORDERDASHBOARD . "</span>
											 <span class=\"widget-label\">
												 <form method=\"GET\">
													<input type=\"hidden\" value=\"order\" name=\"p\">
													<input type=\"hidden\" value=\"searchresult\" name=\"action\">
													<input style=\"width: 60%;\" type=\"text\" name=\"keyword\" placeholder=\"" . _SEARCH . "\" value=\"\"/>
													<button class=\"buton icon-search\" style=\"padding-top: -10px; background-color: blue;\" name=\"\"></button>
												</form>
											 </span>
											 </a>
										</div>
                                                                                <div class=\"board-widgets blue small-widget span6\">
											 <a href=\"?p=order&action=timeinterval\">
											 <span class=\"widget-label\">" . _SALESREPORTDASHBOARD . "</span>
											 <span class=\"widget-icon icon-file-alt\"></span>
											 </a>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class=\"row-fluid\">
					<div class=\"span6\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\"><i class=\"icon-th-large\"></i> " . _PRODUCTDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">
									<div class=\"span6\">
										<div class=\"board-widgets green small-widget\">
											<a href=\"?p=catalog&action=main\">
											<span class=\"widget-label\">" . _CATEGORYDASHBOARD . "</span>
											<span class=\"widget-icon icon-list-ul\"></span>
											</a>
										</div>
									</div>
								<div class=\"span6\">
							<div class=\"board-widgets green small-widget\">
								<a href=\"?p=brand\">
								<span class=\"widget-label\">" . _BRANDDASHBOARD . "</span>
								<span class=\"widget-icon icon-tags\"></span>
								</a>
							</div>
						</div>
								</div>
							</div>
						</div>
					</div>
					<div class=\"span6\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\"><i class=\"icon-desktop\"></i> " . _VIEWDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">
									<div class=\"span6\">
										<div class=\"board-widgets yellow small-widget\">
											 <a href=\"?p=banner\">
											 <span class=\"widget-label\">" . _BANNERDASHBOARD . "</span>
											 <span class=\"widget-icon icon-cog\"></span>
											 </a>
										</div>
									</div>
									<div class=\"span6\">
										<div class=\"board-widgets yellow small-widget\">
											 <a href=\"?p=decoration\">
											 <span class=\"widget-label\">" . _DEKORASIDASHBOARD . "</span>
											 <span class=\"widget-icon icon-gift\"></span>
											 </a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class=\"row-fluid\">
					<div class=\"span12\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\"><i class=\"icon-star\"></i> " . _FEATUREDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">

									<div class=\"span12\">
										<div class=\"board-widgets violet small-widget span4\">
											 <a href=\"?p=page\">
											 <span class=\"widget-label\">" . _PAGEDASHBOARD . "</span>
											 <span class=\"widget-icon icon-user\"></span>
											 </a>
										</div>
                                        <div class=\"board-widgets violet small-widget span4\">
											 <a href=\"?p=counter\">
											 <span class=\"widget-label\">" . _STATISTICSDASHBOARD . "</span>
											 <span class=\"widget-icon icon-bar-chart\"></span>
											 </a>
										</div>
										<div class=\"board-widgets violet small-widget span4\">
											 <a href=\"?p=ym\">
											 <span class=\"widget-label\">" . _SUPPORTDASHBOARD . "</span>
											 <span class=\"widget-icon icon-headphones\"></span>
											 </a>
										</div>
									</div>
						
								</div>
							</div>
						</div>
					</div>
				</div>";
            break;

        case 2: //NON TOKO ONLINE
		
            $dashboarditem = "
			<!--
				<div class=\"row-fluid\">
					<div class=\"span12\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\">" . _FIRSTSTEPSDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">

									<div class=\"span4\">
										<div class=\"board-widgets peach small-widget\">
											 <a href=\"?p=site\">
											 <span class=\"widget-label\">" . _SETTINGSDASHBOARD . "</span>
											 <span class=\"widget-icon\">1</span>
											 </a>
										</div>
									</div>
									<div class=\"span4\">
										<div class=\"board-widgets peach small-widget\">
											 <a href=\"?p=page\">
											 <span class=\"widget-label\">" . _PAGEDASHBOARD . "</span>
											 <span class=\"widget-icon\">2</span>
											 </a>
										</div>
									</div>
									<div class=\"span4\">
										<div class=\"board-widgets peach small-widget\">
											 <a href=\"?p=catalog\">
											 <span class=\"widget-label\">" . _PRODUCTDASHBOARD . "</span>
											 <span class=\"widget-icon\">3</span>
											 </a>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			-->	
				<div class=\"row-fluid\">
					<div class=\"span12\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\">" . _CONTENTDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">

									<div class=\"span4\">
										<div class=\"board-widgets blue small-widget\">
											 <a href=\"?p=news\">
											 <span class=\"widget-label\">" . _NEWSDASHBOARD . "</span>
											 <span class=\"widget-icon icon-globe\"></span>
											 </a>
										</div>
									</div>
									<div class=\"span4\">
										<div class=\"board-widgets blue small-widget\">
											 <a href=\"?p=event\">
											 <span class=\"widget-label\">" . _AGENDADASHBOARD . "</span>
											 <span class=\"widget-icon icon-calendar\"></span>
											 </a>
										</div>
									</div>
									<div class=\"span4\">
										<div class=\"board-widgets blue small-widget\">
											 <a href=\"?p=gallery\">
											 <span class=\"widget-label\">" . _GALLERYDASHBOARD . "</span>
											 <span class=\"widget-icon icon-picture\"></span>
											 </a>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class=\"row-fluid\">
					<div class=\"span6\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\">" . _PRODUCTDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">
									<div class=\"span6\">
										<div class=\"board-widgets yellow small-widget\">
											<a href=\"?p=catalog\">
											<span class=\"widget-label\">" . _CATEGORYDASHBOARD . "</span>
											<span class=\"widget-icon icon-list-ul\"></span>
											</a>
										</div>
									</div>
								<div class=\"span6\">
							<div class=\"board-widgets yellow small-widget\">
								<a href=\"?p=brand\">
								<span class=\"widget-label\">" . _BRANDDASHBOARD . "</span>
								<span class=\"widget-icon icon-tags\"></span>
								</a>
							</div>
						</div>
								</div>
							</div>
						</div>
					</div>
					<div class=\"span6\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\">" . _VIEWDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">
									<div class=\"span6\">
										<div class=\"board-widgets toscha small-widget\">
											 <a href=\"?p=banner\">
											 <span class=\"widget-label\">" . _BANNERDASHBOARD . "</span>
											 <span class=\"widget-icon icon-cog\"></span>
											 </a>
										</div>
									</div>
									<div class=\"span6\">
										<div class=\"board-widgets toscha small-widget\">
											 <a href=\"?p=decoration\">
											 <span class=\"widget-label\">" . _DEKORASIDASHBOARD . "</span>
											 <span class=\"widget-icon icon-gift\"></span>
											 </a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class=\"row-fluid\">
					<div class=\"span12\">
						<div class=\"content-widgets\">
							<div>
								<div class=\"widget-header-block\">
									<h4 class=\"widget-header\">" . _FEATUREDASHBOARD . "</h4>
								</div>
								<div class=\"content-box\">

									<div class=\"span4\">
										<div class=\"board-widgets violet small-widget\">
											 <a href=\"?p=page\">
											 <span class=\"widget-label\">" . _PAGEDASHBOARD . "</span>
											 <span class=\"widget-icon icon-user\"></span>
											 </a>
										</div>
									</div>
									<div class=\"span4\">
                                        <div class=\"board-widgets violet small-widget\">
											 <a href=\"?p=counter\">
											 <span class=\"widget-label\">" . _STATISTICSDASHBOARD . "</span>
											 <span class=\"widget-icon icon-bar-chart\"></span>
											 </a>
										</div>
									</div>
									<div class=\"span4\">
										<!--<div class=\"board-widgets violet small-widget span6\">
											 <a href=\"?p=mgm\">
											 <span class=\"widget-label\">" . _MGMDASHBOARD . "</span>
											 <span class=\"widget-icon icon-group\"></span>
											 </a>
										</div>-->
                                        <div class=\"board-widgets violet small-widget\">
											 <a href=\"?p=ym\">
											 <span class=\"widget-label\">" . _SUPPORTDASHBOARD . "</span>
											 <span class=\"widget-icon icon-headphones\"></span>
											 </a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
					";
            break;
    }
    return $dashboarditem;
}

function generateFlotBarChart($title, $data) {
    $flotbar = "
    <script type=\"text/javascript\">
            $(function() {
                $.plot($(\"#flotbar-chart #flotbar-container\"),
                    [{
                        data: $data,
                        label: \"$title\",
                        points: {
                            show: true
                        },
                        lines: {
                            fill: true,
                            show: true
                        }
                    }
                ],
                        {
                            series: {
                                points: {
                                    show: true,
                                    lineWidth: 2,
                                    fill: true,
                                    fillColor: \"#ffffff\",
                                    symbol: \"circle\",
                                    radius: 5,
                                },
                                shadowSize: 0,
                            },
                            grid: {
                                hoverable: true,
                                tickColor: \"#aaaaaa\",
                                borderWidth: 2,
                            },
                            colors: [\"#cb4b4b\"],
                            tooltip: true,
                            tooltipOpts: {
                                shifts: {
                                    x: -100
                                },
                                defaultTheme: false
                            },
                            xaxis: {
                                mode: \"null\"
                            },
                        }
                );
            });
        </script>";
    return $flotbar;
}

function oldadminmenu($level, $group) {
global $mysql;
    global $cfg_app_path, $cfg_app_url, $lang, $heatmapurl, $gaurl, $iscustomdesign;
    $sql = "SELECT id, nama, judul, access FROM module WHERE grup='$group' ORDER BY grup, judul";
    $result = $mysql->query($sql);

    $menu = "";
    while (list($id, $nama, $judulmodul, $access) = $mysql->fetch_row($result)) {
        $splitaccess = explode(",", $access);
        foreach ($splitaccess as $assigned) {
            if ($nama == 'pseudo') {
                switch ($judulmodul) {
                    case 'Google Analytics':
                        if ($level == $assigned)
                            $menu .= "<li><a href=\"https://www.google.com/analytics/reporting/login\"><i class=\"icon-external-link\"></i>$judulmodul</a></li>\r\n";
                        break;
                }
            } else {
                if ($level == $assigned) {
                    switch ($nama) {
                        case 'member':
                            $boleh = ($level == 0) ? true : false;
                            break;
                        case 'template':
                            $boleh = (!$iscustomdesign) ? true : false;
                            break;
                        default:
                            $boleh = true;
                    }
                    if ($boleh)
                        $menu .= "<li><a href=\"$cfg_app_url/kelola/index.php?p=$nama\" id=\"$nama\"><i class=\"icon-external-link\"></i>$judulmodul</a></li>\r\n";
                }
            }
        }
    }
    return $menu;
}

//frontend menu
function menustructure() {
global $mysql;
    global $cfg_app_url, $cfg_app_path, $urlfunc;
    $sql = "SELECT id, parent, type, judul, isi FROM menu ORDER BY urutan";
    $cats = new categories();
    $mycats = array();
    $result = $mysql->query($sql);
    while ($row = $mysql->fetch_assoc($result)) {
        $sql1 = "SELECT urlpattern, modul, jenis FROM menutype WHERE id='" . $row['type'] . "'";
        $result1 = $mysql->query($sql1);
        list($urlpattern, $modulmenu, $jenismenu) = $mysql->fetch_row($result1);
        $url = str_replace('[var]', $row['isi'], $urlpattern);

        $titleurl = array();
        if (substr_count($url, "pid=") > 0) {
            if ($modulmenu == "page") {
                $tabel = $modulmenu;
                $kolom = "judul";
            } else {
                $tabel = $modulmenu . "data";
                $kolom = (($modulmenu == "news") ? "judulberita" : "title");
            }
            $sqljudul = "SELECT $kolom FROM $tabel WHERE id='" . $row['isi'] . "' ";
            $resultjudul = $mysql->query($sqljudul);
            list($itemname) = $mysql->fetch_row($resultjudul);
            $titleurl["pid"] = $itemname;
        } elseif (substr_count($url, "cat_id=") > 0) {
            if ($row['isi'] > 0) {
                $sqljudul = "SELECT nama FROM " . $modulmenu . "cat WHERE id='" . $row['isi'] . "' ";
                $resultjudul = $mysql->query($sqljudul);
                list($catname) = $mysql->fetch_row($resultjudul);
                $titleurl["cat_id"] = $catname;
            } else {
                $titleurl["cat_id"] = 'all';
            }
        }

        if (file_exists("$cfg_app_path/modul/$modulmenu/prettyurasi.php"))
            include ("$cfg_app_path/modul/$modulmenu/prettyurasi.php");
        if (strlen($url) > 0)
            $url = $urlfunc->makePretty($url, $titleurl);

        $mycats[] = array('id' => $row['id'], 'parent' => $row['parent'], 'type' => $row['type'], 'judul' => $row['judul'], 'url' => $url, 'level' => 0);
        if (file_exists("$cfg_app_path/modul/$modulmenu/menuaksi.php"))
            include ("$cfg_app_path/modul/$modulmenu/menuaksi.php");
    }
    $cats->get_cats($mycats);

    $currlevel = 1;
    $catcontent .= "<ul id=\"nav\" class=\"dropdown dropdown-horizontal\">\r\n";
    for ($i = 0; $i < count($cats->cats); $i++) {
        if ($cats->cats[$i]['url'] == '') {
            $menuitem = "<li class=\"dir\">" . $cats->cats[$i]['judul'];
        } else {
            $menuitem = "<li><a href=\"" . $cats->cats[$i]['url'] . "\">" . $cats->cats[$i]['judul'] . "</a></li>\r\n";
        }
        $selisihlevel = $cats->cats[$i]['level'] - $currlevel;
        if ($selisihlevel > 0) {
            $catcontent .= "<ul>\r\n";
            $catcontent .= "$menuitem\r\n";
        }
        if ($selisihlevel == 0) {
            $catcontent .= "$menuitem\r\n";
        }
        if ($selisihlevel < 0) {
            for ($j = 0; $j < -$selisihlevel; $j++) {
                $catcontent .= "</ul>\r\n";
                if ($cats->cats[$i]['tipe'] == '0')
                    $catcontent .= "</li>\r\n";
            }
            $catcontent .= "$menuitem\r\n";
        }
        $currlevel = $cats->cats[$i]['level'];
    }
    $catcontent .= "</ul>\r\n";
    return $catcontent;
}

function verifyaccess($level, $tobeverified) {
global $mysql;
    global $cfg_app_url;
    $sql = "SELECT access FROM module WHERE nama='$tobeverified'";
    $result = $mysql->query($sql);
    list($access) = $mysql->fetch_row($result);
    $splitaccess = explode(",", $access);
    foreach ($splitaccess as $assigned) {
        if ($level == $assigned) {
            return TRUE;
        }
    }
    return FALSE;
}

function convertbyte($size) {
    if ($size >= pow(2, 30)) {
        $stringsize = number_format($size / pow(2, 30), 1, ',', '.') . " GB";
    } elseif ($size >= pow(2, 20)) {
        $stringsize = number_format($size / pow(2, 20), 1, ',', '.') . " MB";
    } elseif ($size >= pow(2, 10)) {
        $stringsize = number_format($size / pow(2, 10), 1, ',', '.') . " KB";
    } else {
        $stringsize = number_format($size, 1, ',', '.') . " bytes";
    }
    return $stringsize;
}

function pagination($namamodul, $screen = '', $prmtr = '') {
    global $pages;
	$hal .="<div  class='pagination pagination-small'>";
	//$hal .= _PAGE;
	$hal .="<ul>";
   
    if (!isset($screen)) {
        $screen = 0;
    }
    if ($screen > 0) {
        $prev = $screen - 1;
        if ($prmtr == "") {
            $hal .= "<li><a href=\"?p=$namamodul&screen=$prev\">Prev</a></li>\r\n";
        } else {
            $hal .= "<li><a href=\"?p=$namamodul&screen=$prev&$prmtr\">Prev</a></li>\r\n";
        }
		
    }
    for ($i = 0; $i < $pages; $i++) {
        $display_num = ($i + 1);
        if ($screen == $i) {
            $hal .= " <li><a><b>$display_num</b></a></li>";
        } else {
            if ($prmtr == "") {
                $hal .= " <li><a href=\"?p=$namamodul&screen=$i\">$display_num</a></li>";
            } else {
                $hal .= " <li><a href=\"?p=$namamodul&screen=$i&$prmtr\">$display_num</a></li>";
            }
        }
    }
    if ($screen < $pages) {
        $next = $screen + 1;
        if ($next < $pages) {
            if ($prmtr == "") {
                $hal .= " <li><a href=\"?p=$namamodul&screen=$next\">Next</a></li>\r\n";
            } else {
                $hal .= " <li><a href=\"?p=$namamodul&screen=$next&$prmtr\">Next</a></li>\r\n";
            }
        }
    }
	 $hal.="</ul></div>";
	 // $hal.="<div>";
    // if ($pages == 1) {
        // $hal .= "<br>" . _TOTAL . $pages . _PAGESINGULAR;
    // } else {
        // $hal .= "<br>" . _TOTAL . $pages . _PAGEPLURAL;
    // }
	// $hal.="</div>";
   
    return $hal;
}

function aksipagination($namamodul, $screen = '', $prmtr = '', $search='', $title = array()) {
    global $pages, $cfg_app_url, $urlfunc;
    $hal .= _PAGE;
    $tambahan = ((substr_count($prmtr, 'action') == 0) ? "&action=" : "");

    if (!isset($screen)) {
        $screen = 0;
    }
    if ($screen > 0) {
        $prev = $screen - 1;
        if ($prmtr == "") {
            $hal .= ($search != '') ? 
            "<a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan", $title) . $search . "&screen=$prev\">&lt;</a>\r\n" : 
            "<a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&screen=$prev", $title) . "\">&lt;</a>\r\n";
        } else {
            $hal .= ($search != '') ? 
            "<a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&$prmtr", $title) . $search . "&screen=$prev\">&lt;</a>\r\n" : 
            "<a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&$prmtr&screen=$prev", $title) . "\">&lt;</a>\r\n";
        }
    }
    for ($i = 0; $i < $pages; $i++) {
        $display_num = ($i + 1);
        if ($screen == $i) {
            $hal .= " <b>$display_num</b>";
        } else {
            if ($prmtr == "") {
                $hal .= ($search != '') ? 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan", $title) . $search . "&screen=$i\">$display_num</a>" : 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&screen=$i", $title) . "\">$display_num</a>";
            } else {
                $hal .= ($search != '') ? 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&$prmtr", $title) . $search . "&screen=$i\">$display_num</a>" : 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&$prmtr&screen=$i", $title) . "\">$display_num</a>";
            }
        }
    }
    if ($screen < $pages) {
        $next = $screen + 1;
        if ($next < $pages) {
            if ($prmtr == "") {
                $hal .= ($search != '') ? 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan", $title) . $search . "&screen=$next\">&gt;</a>\r\n" : 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&screen=$next", $title) . "\">&gt;</a>\r\n";
            } else {
                $hal .= ($search != '') ? 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&$prmtr", $title) . $search . "&screen=$next\">&gt;</a>\r\n" : 
                " <a href=\"" . $urlfunc->makePretty("?p=$namamodul$tambahan&$prmtr&screen=$next", $title) . "\">&gt;</a>\r\n";
            }
        }
    }
    if ($pages == 1) {
        $hal .= "<br>" . _TOTAL . " " . $pages . " " . _PAGESINGULAR;
    } else {
        $hal .= "<br>" . _TOTAL . " " . $pages . " " . _PAGEPLURAL;
    }
    return $hal;
}

function getrandomnumber() {
    $hasil = '';
    for ($i = 0; $i < 9; $i++) {
        $hasil.=rand(0, 9);
    }
    return $hasil;
}

function fiestoupload($fieldname, $destdir, $destfile, $maxsize, $allowedtypes = "gif,jpg,jpeg,png") {

    /*
      $fieldname : field name di form
      $destdir : direktori tujuan
      $destfile : nama file (minus extension, which is always the same as uploaded)
      $maxsize : ukuran maksimum dalam byte (harus konsisten dengan MAX_FILE_SIZE di html)
      $lang : (optional) bahasa. default="id".
      $allowedtypes : (optional) jenis extension yang diizinkan, dipisahkan tanda koma. default = "gif,jpg,jpeg,png".
     */
    if ($_FILES[$fieldname]['name'] != '') {
        $maxsizeinkb = intval($maxsize / 1000);

        //Filter 1: cek apakah file terupload dengan benar
        switch ($_FILES[$fieldname]['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return _FILETOOBIG . " $maxsizeinkb kbytes.";
                break;
            case UPLOAD_ERR_PARTIAL:
                return _FILEPARTIAL;
                break;
            case UPLOAD_ERR_NO_FILE:
                return _FILEERROR1;
                break;
        }

        //Filter 2: cek apakah ukuran sesuai yang diizinkan. Beda dengan filter 1 yang membandingkan terhadap setting php.ini, sekarang dibandingkan dengan aturan yang dibuat sendiri di config
        if ($_FILES[$fieldname]['size'] > $maxsize) {
            return _FILETOOBIG . " $maxsizeinkb kbytes.";
        }

        //Filter 3: cek apakah extension sesuai yang diizinkan

        $rallowedtypes = explode(',', $allowedtypes);
        $temp = explode('.', $_FILES[$fieldname]['name']);
        $extension = strtolower($temp[count($temp) - 1]);

        $isallowed = false;
        foreach ($rallowedtypes as $allowedtype) {
            if ($extension == $allowedtype)
                $isallowed = true;
        }

        if (!$isallowed) {
            return _ALLOWEDTYPE . " $allowedtypes.";
        }

        //Filter 4: cek apakah benar-benar file gambar (hanya jika $allowedtypes="gif,jpg,jpeg,png")
        //Tidak cek MIME-type karena barubah-ubah terus
        //Tidak cek extension karena nanti dipaksa berubah
        //Cek dilakukan sebelum dipindah ke destination dir (masih di temp)

        if ($extension == "gif" || $extension == "jpg" || $extension == "jpeg" || $extension == "png") {
            $size = getimagesize($_FILES[$fieldname]['tmp_name']);
            if ($size == FALSE) {
                return _ALLOWEDTYPE . " $allowedtypes.";
            }
        }

        //Filter 5: Jalankan
        $thelastdestination = ($destfile == '') ? "$destdir/" . $_FILES[$fieldname]['name'] : "$destdir/$destfile.$extension";
        if (!move_uploaded_file($_FILES[$fieldname]['tmp_name'], $thelastdestination)) {
            return _MAYBEPERMISSION;
        }
        return _SUCCESS;
    } else {
        return _FILEPARTIAL;
    }
}

function fiestouploadr($fieldname, $destdir, $destfile, $maxsize, $prefname = "", $allowedtypes = "gif,jpg,jpeg,png") {
    global $namathumbnail;
    /*
      $fieldname : field name di form
      $destdir : direktori tujuan
      $destfile : nama file (minus extension, which is always the same as uploaded)
      $maxsize : ukuran maksimum dalam byte (harus konsisten dengan MAX_FILE_SIZE di html)
      $lang : (optional) bahasa. default="id".
      $allowedtypes : (optional) jenis extension yang diizinkan, dipisahkan tanda koma. default = "gif,jpg,jpeg,png".
     */
    $totalupload = count($_FILES[$fieldname]['name']);
    for ($i = 0; $i <= $totalupload; $i++) {
        if ($_FILES[$fieldname]['name'][$i] != '') {
            $maxsizeinkb = intval($maxsize / 1000);

            //Filter 1: cek apakah file terupload dengan benar
            switch ($_FILES[$fieldname]['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    return _FILETOOBIG . " $maxsizeinkb kbytes.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    return _FILEPARTIAL;
                    break;
                case UPLOAD_ERR_NO_FILE:
                    return _FILEERROR1;
                    break;
            }

            //Filter 2: cek apakah ukuran sesuai yang diizinkan. Beda dengan filter 1 yang membandingkan terhadap setting php.ini, sekarang dibandingkan dengan aturan yang dibuat sendiri di config
            if ($_FILES[$fieldname]['size'][$i] > $maxsize) {
                return _FILETOOBIG . " $maxsizeinkb kbytes.";
            }

            //Filter 3: cek apakah extension sesuai yang diizinkan

            $rallowedtypes = explode(',', $allowedtypes);
            $temp = explode('.', $_FILES[$fieldname]['name'][$i]);
            $extension = strtolower($temp[count($temp) - 1]);
			
            $isallowed = false;
            foreach ($rallowedtypes as $allowedtype) {
                if ($extension == $allowedtype)
                    $isallowed = true;
            }

            if (!$isallowed) {
                return _ALLOWEDTYPE . " $allowedtypes.";
            }

            //Filter 4: cek apakah benar-benar file gambar (hanya jika $allowedtypes="gif,jpg,jpeg,png")
            //Tidak cek MIME-type karena barubah-ubah terus
            //Tidak cek extension karena nanti dipaksa berubah
            //Cek dilakukan sebelum dipindah ke destination dir (masih di temp)

            if ($extension == "gif" || $extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                $size = getimagesize($_FILES[$fieldname]['tmp_name'][$i]);
                if ($size == FALSE) {
                    return _ALLOWEDTYPE . " $allowedtypes.";
                }
            }

            //Filter 5: Jalankan
			$strpath = pathinfo($_FILES[$fieldname]['name'][$i]);
			$basename = $strpath['filename'];
            $uniq = uniqid();
            $thumb_name = clean_input("$uniq$prefname-$basename").'.'.$extension;
            $namathumbnail[$i] = $thumb_name;
            $thelastdestination = "$destdir/$thumb_name";
            if (!move_uploaded_file($_FILES[$fieldname]['tmp_name'][$i], $thelastdestination)) {
                return _MAYBEPERMISSION;
            }
        }
    }

    return _SUCCESS;
}

function clean_input($string){
	$string = preg_replace("`\[.*\]`U","-",$string);
	$string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
	$string = htmlentities($string, ENT_COMPAT, 'utf-8');
	$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
	$string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
	
	return strtolower(trim($string, '-'));
}

// function fiestoresize($srcimgfile, $dstimgfile, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight=100) {
// /*
// Resize gambar (misalnya bikin thumbnail)
// string $scrimgfile : nama file asal
// string $dstimgfile : nama file tujuan
// string enum('s'|'l'|'w'|'h') $thumbcalcbase : dasar perhitungan resize menjadi thumbnail (shorter side, longer side, width, height).
// int $thumbcalcpx : thumbnail image width/height in pixel
// 16:14 14/02/2010 tambahan:
// string enum('b'|'f') $thumbcalcbase : dasar perhitungan resize menjadi thumbnail
// b = both = maxwidth dan maxweight dua2nya ditentukan, ukuran hasil dimaksimalkan namun dipertahankan proporsional
// f = fixed = maxwidth dan maxweight dua2nya ditentukan, ukuran hasil dipaksa mengikuti ketentuan meskipun terpaksa tidak proporsional
// int $thumbcalcpxheight : thumbnail image height in pixel, hanya jika tambahan dipakai. default = 100 pixel.
// */
// $temp = explode('.', $srcimgfile);
// $extension = strtolower($temp[count($temp) - 1]);
// switch ($extension) {
// case 'jpg':
// case 'jpeg':
// $srcimg = imagecreatefromjpeg($srcimgfile);
// list($dstw, $dsth) = resizecalc($srcimg, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight);
// $dstimg = imagecreatetruecolor($dstw, $dsth);
// if (!imagecopyresampled($dstimg, $srcimg, 0, 0, 0, 0, $dstw, $dsth, imagesx($srcimg), imagesy($srcimg)))
// pesan(_ERROR, _CANTRESAMPLE);
// if (!imagejpeg($dstimg, $dstimgfile, 100))
// return _MAYBEPERMISSION;
// break;
// case 'gif':
// $srcimg = imagecreatefromgif($srcimgfile);
// list($dstw, $dsth) = resizecalc($srcimg, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight);
// $dstimg = imagecreate($dstw, $dsth);
// if (!imagecopyresampled($dstimg, $srcimg, 0, 0, 0, 0, $dstw, $dsth, imagesx($srcimg), imagesy($srcimg)))
// pesan(_ERROR, _CANTRESAMPLE);
// if (!imagegif($dstimg, $dstimgfile))
// return _MAYBEPERMISSION;
// break;
// case 'png':
// $srcimg = imagecreatefrompng($srcimgfile);
// list($dstw, $dsth) = resizecalc($srcimg, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight);
// $dstimg = imagecreatetruecolor($dstw, $dsth);
// if (!imagecopyresampled($dstimg, $srcimg, 0, 0, 0, 0, $dstw, $dsth, imagesx($srcimg), imagesy($srcimg)))
// pesan(_ERROR, _CANTRESAMPLE);
// if (!imagepng($dstimg, $dstimgfile))
// return _MAYBEPERMISSION;
// break;
// }
// return _SUCCESS;
// }

function fiestoresize($srcimgfile, $dstimgfile, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight = 100) {
    /*
      Resize gambar (misalnya bikin thumbnail)

      string $scrimgfile : nama file asal
      string $dstimgfile : nama file tujuan
      string enum('s'|'l'|'w'|'h') $thumbcalcbase : dasar perhitungan resize menjadi thumbnail (shorter side, longer side, width, height).
      int $thumbcalcpx : thumbnail image width/height in pixel

      16:14 14/02/2010 tambahan:
      string enum('b'|'f') $thumbcalcbase : dasar perhitungan resize menjadi thumbnail
      b = both = maxwidth dan maxweight dua2nya ditentukan, ukuran hasil dimaksimalkan namun dipertahankan proporsional
      f = fixed = maxwidth dan maxweight dua2nya ditentukan, ukuran hasil dipaksa mengikuti ketentuan meskipun terpaksa tidak proporsional
      int $thumbcalcpxheight : thumbnail image height in pixel, hanya jika tambahan dipakai. default = 100 pixel.
     */

    $temp = explode('.', $srcimgfile);
    $extension = strtolower($temp[count($temp) - 1]);
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $srcimg = imagecreatefromjpeg($srcimgfile);
            list($dstw, $dsth) = resizecalc($srcimg, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight);
            $dstimg = imagecreatetruecolor($dstw, $dsth);
            if (!imagecopyresampled($dstimg, $srcimg, 0, 0, 0, 0, $dstw, $dsth, imagesx($srcimg), imagesy($srcimg)))
                pesan(_ERROR, _CANTRESAMPLE);
            if (!imagejpeg($dstimg, $dstimgfile, 100))
                return _MAYBEPERMISSION;
            break;
        case 'gif':
            $srcimg = imagecreatefromgif($srcimgfile);
            list($dstw, $dsth) = resizecalc($srcimg, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight);
            // $dstimg = imagecreate($dstw,$dsth); 

            /*
             * bug fixed by aly - resize gambar transparan GIF dan animasi GIF
             * Tgl: 22/09/2012 9:40
             */
            // return value sumber gambar harus dari function imagecreatetruecolor()
            // - http://php.net/manual/en/function.imagecolortransparent.php
            $dstimg = imagecreatetruecolor($dstw, $dsth);
            $imgallocate = imagecolorallocate($dstimg, 0, 0, 0);

            // set backgroud menjadi transparan
            imagecolortransparent($dstimg, $imgallocate);
            $transparent = imagecolorallocatealpha($dstimg, 255, 255, 255, 127);
            imagefilledrectangle($dstimg, 0, 0, $dstw, $dsth, $transparent);
            //==========================			
            if (!imagecopyresampled($dstimg, $srcimg, 0, 0, 0, 0, $dstw, $dsth, imagesx($srcimg), imagesy($srcimg)))
                pesan(_ERROR, _CANTRESAMPLE);
            if (!imagegif($dstimg, $dstimgfile))
                return _MAYBEPERMISSION;
            break;
        case 'png':
            $srcimg = imagecreatefrompng($srcimgfile);
            list($dstw, $dsth) = resizecalc($srcimg, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight);
            $dstimg = imagecreatetruecolor($dstw, $dsth);
            /*
             * bug fixed by aly - resize gambar transparan PNG
             * Tgl: 22/09/2012 9:40
             */
            imagealphablending($dstimg, false);
            imagesavealpha($dstimg, true);
            $transparent = imagecolorallocatealpha($dstimg, 255, 255, 255, 127);
            imagefilledrectangle($dstimg, 0, 0, $dstw, $dsth, $transparent);
            //==========================
            if (!imagecopyresampled($dstimg, $srcimg, 0, 0, 0, 0, $dstw, $dsth, imagesx($srcimg), imagesy($srcimg)))
                pesan(_ERROR, _CANTRESAMPLE);
            if (!imagepng($dstimg, $dstimgfile))
                return _MAYBEPERMISSION;
            break;
    }
    return _SUCCESS;
}

function resizecalc($srcimg, $thumbcalcbase, $thumbcalcpx, $thumbcalcpxheight) {
    switch ($thumbcalcbase) {
        case 'h':
            $dsth = $thumbcalcpx;
            $dstw = round(imagesx($srcimg) / imagesy($srcimg) * $dsth);
            break;
        case 'w':
            $dstw = $thumbcalcpx;
            $dsth = round(imagesy($srcimg) / imagesx($srcimg) * $dstw);
            break;
        case 'l':
            if (imagesx($srcimg) <= imagesy($srcimg)) { //portrait
                $dsth = $thumbcalcpx;
                $dstw = round(imagesx($srcimg) / imagesy($srcimg) * $dsth);
            } else { //landscape
                $dstw = $thumbcalcpx;
                $dsth = round(imagesy($srcimg) / imagesx($srcimg) * $dstw);
            }
            break;
        case 's':
            if (imagesx($srcimg) <= imagesy($srcimg)) { //portrait
                $dstw = $thumbcalcpx;
                $dsth = round(imagesy($srcimg) / imagesx($srcimg) * $dstw);
            } else { //landscape
                $dsth = $thumbcalcpx;
                $dstw = round(imagesx($srcimg) / imagesy($srcimg) * $dsth);
            }
            break;
        case 'b':
            if ($thumbcalcpx / imagesx($srcimg) <= $thumbcalcpxheight / imagesy($srcimg)) { //ikuti x
                $dstw = $thumbcalcpx;
                $dsth = round(imagesy($srcimg) / imagesx($srcimg) * $dstw);
            } else { //ikuti y
                $dsth = $thumbcalcpxheight;
                $dstw = round(imagesx($srcimg) / imagesy($srcimg) * $dsth);
            }
            break;
        case 'f':
            $dstw = $thumbcalcpx;
            $dsth = $thumbcalcpxheight;
            break;
    }
    return array($dstw, $dsth);
}

function tglformat($str, $displaytime = false) {
    global $namabulan, $lang;
    if (($timestamp = strtotime($str)) !== -1) {
        $i = getdate($timestamp);
        $angkabulan = $i['mon'] - 1;
        if ($lang == 'id') {
            $angkabulan = $i['mon'] - 1;
            $j = "$i[mday] $namabulan[$angkabulan] $i[year]";
        } else {
            $j = "$i[month] $i[mday], $i[year]";
        }
        if ($displaytime)
            $j .= " " . date("H:i", $timestamp);
        return $j;
    } else {
        return "(Invalid date)";
    }
}

function fiestolaundry($fieldvalue, $maxlength = 0, $allowhtml = FALSE) {
    /*
      Field laundry. Return cleaned up field.

      string $fieldvalue : nilai field
      int $maxlength : panjang max yang diperbolehkan //harus konsisten dengan form html atau field database
     */
    // Trim field
    $fieldvalue = trim($fieldvalue);

    //Pengamanan: Potong string sebatas yang diizinkan
    if ($maxlength > 0) {
        $fieldvalue = substr($fieldvalue, 0, $maxlength);
    }

    //Pengamanan: Hilangkan HTML dan PHP tag
    if (!$allowhtml) {
        $fieldvalue = strip_tags($fieldvalue);
    }

    $fieldvalue = str_replace('?>', '', $fieldvalue);
    $fieldvalue = str_replace('<?', '', $fieldvalue);
    $fieldvalue = str_replace('<script', '', $fieldvalue);
    $fieldvalue = str_replace('--', '', $fieldvalue);
    $fieldvalue = str_replace('1=1', '', $fieldvalue);
    $fieldvalue = addslashes($fieldvalue);
    return $fieldvalue;
}

function datevalidation($start, $end, $startdatename, $enddatename) {
    $d1 = strtotime($start);
    $d2 = strtotime($end);
    $diff = $d2 - $d1;
    if ($diff < 0) {
        return "$startdatename " . _MUSTBEGREATER . " $enddatename!;";
    }
}

function validation($fieldvalue, $humanfieldname, $isnumeric) {
    /*
      string $fieldvalue : nilai field
      string $humanfieldname : nama field manusiawi
      string $isnumeric : nilai field harus numeric
     */
    if ($fieldvalue == '') {
        return "$humanfieldname " . _ISREQUIRED . "!;";
    }
    if ($isnumeric) {
        if (is_numeric($fieldvalue) != $isnumeric) {
            return "$humanfieldname " . _MUSTBENUMBER . "!;";
        }
    }
}

// function validation($fieldvalue, $humanfieldname, $isnumeric) {
// /*
// string $fieldvalue : nilai field
// string $humanfieldname : nama field manusiawi
// string $isnumeric : nilai field harus numeric
// */
// if ($fieldvalue == '') {
// $fieldvalue = "<li>$humanfieldname " . _ISREQUIRED . "!</li>";
// return "<ul>$fieldvalue</ul>";
// }
// if ($isnumeric) {
// if (is_numeric($fieldvalue) != $isnumeric) {
// return "<li>$humanfieldname " . _MUSTBENUMBER . "!</li>";
// }
// }
// }


function emailvalidation($email) {
    /*
      string $email: alamat email yang akan di cek
     */
    if (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $email) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/", $email)) {
        return _INVALIDEMAIL . "!<br/>";
    }
}

function createstatus($message, $type = "error") {
    /*
      string $message: pesan yang akan ditampilkan
      string $type: tipe dari pesan yang akan ditampilkan: error, success, warning, info
     */
    return "
    <div class=\"alert alert-$type\">
        <i class=\"icon-exclamation-sign\"></i><strong>$message!</strong>
    </div>";
}

function createnotification($error_message, $error_title, $type = "error") {
    global $msg_warning;
    /*
      string $error_message: pesan error yang akan ditampilkan
      string $error_title: judul dari pesan yang akan ditampilkan
      string $type: tipe dari pesan yang akan ditampilkan: error, success, warning, info
     */
    if ($type == "success") {
        $alert_type = "alert-success";
        $alert_icon = "icon-ok-sign";
    } else if ($type == "error") {
        $alert_type = "alert-error";
        $alert_icon = "icon-minus-sign";
    } else if ($type == "warning") {
        $alert_type = "alert-warning";
        $alert_icon = "icon-exclamation-sign";
    } else if ($type == "info") {
        $alert_type = "alert-info";
        $alert_icon = "icon-info-sign";
    }

    $err = explode(';', $error_message);
    if (count($err) > 1) {
        $strErr = "<ul>";
        foreach ($err as $data) {
            if ($data != '') {
                $strErr .= "<li>$data</li>";
            }
        }
        $strErr .= "</ul>";
    } else {
        $strErr = $err[0];
    }
    $msg_warning = "
    <div class=\"alert $alert_type\">
        <i class=\"$alert_icon\"></i><strong>$error_title!</strong>
        $strErr
    </div>";
}

function createnotificationincontent($error_message, $error_title, $type = "error") {
    /*
      string $error_message: pesan error yang akan ditampilkan
      string $error_title: judul dari pesan yang akan ditampilkan
      string $type: tipe dari pesan yang akan ditampilkan: error, success, warning, info
     */
    if ($type == "success") {
        $alert_type = "alert-success";
        $alert_icon = "icon-ok-sign";
    } else if ($type == "error") {
        $alert_type = "alert-error";
        $alert_icon = "icon-minus-sign";
    } else if ($type == "warning") {
        $alert_type = "alert-warning";
        $alert_icon = "icon-exclamation-sign";
    } else if ($type == "info") {
        $alert_type = "alert-info";
        $alert_icon = "icon-info-sign";
    }

    $err = explode(';', $error_message);
    if (count($err) > 1) {
        $strErr = "<ul>";
        foreach ($err as $data) {
            if ($data != '') {
                $strErr .= "<li>$data</li>";
            }
        }
        $strErr .= "</ul>";
    } else {
        $strErr = $err[0];
    }
    return "
    <div class=\"alert $alert_type\">
        <i class=\"$alert_icon\"></i><strong>$error_title!</strong>
        $strErr
    </div>";
}

function createmessage($error_message, $error_title, $type = "error", $action = "") {
    global $msg_warning;
    /*
      string $error_message: pesan error yang akan ditampilkan
      string $error_title: judul dari pesan yang akan ditampilkan
      string $type: tipe dari pesan yang akan ditampilkan: error, success, warning, info
     */
    if ($type == "success") {
        $alert_type = "alert-success";
        $alert_icon = "icon-ok-sign";
    } else if ($type == "error") {
        $alert_type = "alert-error";
        $alert_icon = "icon-minus-sign";
    } else if ($type == "warning") {
        $alert_type = "alert-warning";
        $alert_icon = "icon-exclamation-sign";
    } else if ($type == "info") {
        $alert_type = "alert-info";
        $alert_icon = "icon-info-sign";
    }
    $err = explode(';', $error_message);
    if (count($err) > 1) {
        $strErr = "<ul>";
        foreach ($err as $data) {
            if ($data != '') {
                $strErr .= "<li>$data</li>";
            }
        }
        $strErr .= "</ul>";
    } else {
        $strErr = $err[0];
    }

    $msg_warning = "
    <div class=\"alert $alert_type\">
        <i class=\"$alert_icon\"></i><strong>$error_title!</strong>
        $strErr
    </div>";
    return $action;
}

/* checkrequired jadi validation */

function checkrequired($fieldvalue, $humanfieldname) {
    /*
      string $fieldvalue : nilai field
      string $humanfieldname : nama field manusiawi
     */
    if ($fieldvalue == '') {
        pesan(_ERROR, "<span class=\"fieldname\">$humanfieldname</span> " . _ISREQUIRED);
    } else {
        return $fieldvalue;
    }
}

function pesan($judul, $isi) {
    global $msg_warning;
    /*
      Display error message sekaligus (optional) log percobaan attack.

      string $judul : judul error message
      string $isi : isi error message
      mix $backnum: jumlah halaman yang di-skip untuk link "back" (jika diisi integer), atau URL
      string $backmsg : teks link untuk link back
      string $tulislog : (optional) nama file log. jika kosong berarti log tidak ditulis.
     */
    $msg_warning = createstatus($isi, $judul);
}

function adminlistcategories($namatabel, $namamodul) {
global $mysql;
	$sql = "SELECT id,nama,urutan FROM $namatabel ORDER BY urutan";
	$result = $mysql->query($sql);
	$admincontent .= "<ul>\r\n";
	while (list($cat_id, $nama, $urutan) = $mysql->fetch_row($result)) {
		$admincontent .= "<li><a href=\"".param_url("?p=$namamodul&action=viewcat&cat_id=$cat_id")."\">$nama</a>  
		<a href=\"".param_url("?p=$namamodul&action=viewcat&cat_id=$cat_id")."\">
		<img alt=\"" . _OPEN . "\" border=\"0\" src=\"../images/open.gif\"></a>
		<a href=\"".param_url("?p=$namamodul&action=catedit&cat_id=$cat_id")."\">
		<img alt=\"" . _EDIT . "\" border=\"0\" src=\"../images/modify.gif\"></a> 
		<a href=\"".param_url("?p=$namamodul&action=catdel&cat_id=$cat_id")."\">
		<img alt=\"" . _DEL . "\" border=\"0\" src=\"../images/delete.gif\"></a></li>";
	}
	$admincontent .= "</ul>\r\n";
	return $admincontent;
}

function adminselectcategories($namatabel, $selected = '') {
global $mysql;
    $sql = "SELECT id,nama FROM $namatabel ORDER BY urutan";
    $result = $mysql->query($sql);
    $catselect .= "<select name=\"cat_id\">\r\r\n";
    while (list($cat_id, $nama) = $mysql->fetch_row($result)) {
        if ($selected == $cat_id) {
            $catselect .= "<option value=\"$cat_id\" selected>$nama</option>\r\r\n";
        } else {
            $catselect .= "<option value=\"$cat_id\">$nama</option>\r\r\n";
        }
    }
    $catselect .= "</select>\r\r\n";
    return $catselect;
}

function showwidget($posisi) {
global $mysql;
    global $cfg_app_url, $cfg_img_url, $cfg_app_path, $cfg_img_path, $lang, $namabulan,
    $config_site_templatefolder, $widgetawidth, $widgetbwidth, $urlfunc,
    $cfg_max_width, $cfg_thumb_width, $cfg_carousel_height, $mainblockwidth,
    $cfg_max_cols, $jumlahtampilanmarquee, $carousel_bgcolor, $script_js, $style_css,$pakaicart,$pakaiongkir;
    global $class_widget_row;
	$widget="";
    $sql1 = "SELECT w.type, w.judul, w.isi, w.isborder, wt.modul, wt.jenis FROM widget w, widgettype wt WHERE w.posisi='$posisi' AND w.type=wt.id ORDER BY w.urutan";
    $result1 = $mysql->query($sql1);
    if ($result1 and $mysql->num_rows($result1) > 0) {
        $i = 0;
        while (list($type, $judul, $isi, $isborder, $namamodul, $jenis) = $mysql->fetch_row($result1)) {
            $i++;
            // if ($isborder) {
                // // $widget .= "<div class=\"widget$posisi$class_widget_row\" id=\"$posisi$i\">\r\n";
                // $widget .= "<div class=\"col-lg-12 col-md-6 col-sm-6\">\r\n";
                // $widget .= "<div class=\"well\">\r\n";
                // $widget .= "\t<div class=\"widgettitle\"><div class=\"wrapper\">$judul</div></div>\r\n";
                // $widget .= "\t\t<div class=\"widgetcontent\">\r\n";
                // // $widget .= "\t\t\t<div class=\"wrapper\">\r\n";
                // if (file_exists("modul/$namamodul/urasi.php"))
                    // include ("modul/$namamodul/urasi.php");
                // $sql = "SELECT konstanta, terjemahan FROM translation WHERE modul='$namamodul'";
                // $result = $mysql->query($sql);
                // while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
                    // define($konstanta, $terjemahan);
                // }
                // if (file_exists("modul/$namamodul/widgetaksi.php")) {
                    // if (file_exists("$cfg_app_path/modul/$namamodul/prettyurasi.php"))
                        // include ("$cfg_app_path/modul/$namamodul/prettyurasi.php");
                    // include ("$cfg_app_path/modul/$namamodul/widgetaksi.php");
                // }
                // // $widget .= "\t\t\t</div>\r\n"; //end wrapper
                // $widget .= "\t\t</div>\r\n"; //end widgetcontent
                // $widget .= "\t<div class=\"widgetfooter\"></div>\r\n";
                // $widget .= "</div>\r\n"; //end well
                // $widget .= "</div>\r\n"; //end widget
                // $widget .= "<div class=\"widgetseparator\"></div>\r\n";
            // } else {
                // $widget .= "<div class=\"widget$posisi$class_widget_row\" id=\"$posisi$i\">\r\n";
                // $widget .= "\t<div class=\"widgetnoborder\">\r\n";
                // if (file_exists("modul/$namamodul/urasi.php"))
                    // include ("modul/$namamodul/urasi.php");
                // $sql = "SELECT konstanta, terjemahan FROM translation WHERE modul='$namamodul'";
                // $result = $mysql->query($sql);
                // while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
                    // define($konstanta, $terjemahan);
                // }
                // if (file_exists("modul/$namamodul/widgetaksi.php"))
                    // include ("modul/$namamodul/widgetaksi.php");
                // $widget .= "\t</div>\r\n"; //end widgetcontent
                // $widget .= "</div>\r\n"; //end widget
                // $widget .= "<div class=\"widgetseparator\"></div>\r\n";
            // }
			
			$widgetnoborder = (!$isborder) ? "widgetnoborder" : "well";
			$widget .= "<div class=\"col-md-12 col-sm-6 widget-wrapper\">\r\n";
			$widget .= "<div class=\"$widgetnoborder\">\r\n";
			if ($isborder) $widget .= "\t<div class=\"widgettitle\"><h4>$judul</h4></div>\r\n";
			$widget .= "\t\t<div class=\"widgetcontent\">\r\n";
			// $widget .= "\t\t\t<div class=\"wrapper\">\r\n";
			if (file_exists("modul/$namamodul/urasi.php"))
				include ("modul/$namamodul/urasi.php");
			$sql = "SELECT konstanta, terjemahan FROM translation WHERE modul='$namamodul'";
			$result = $mysql->query($sql);
			while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
				define($konstanta, $terjemahan);
			}
			if (file_exists("modul/$namamodul/widgetaksi.php")) {
				if (file_exists("$cfg_app_path/modul/$namamodul/prettyurasi.php"))
					include ("$cfg_app_path/modul/$namamodul/prettyurasi.php");
				include ("$cfg_app_path/modul/$namamodul/widgetaksi.php");
			}
			// $widget .= "\t\t\t</div>\r\n"; //end wrapper
			$widget .= "\t\t</div>\r\n"; //end widgetcontent
			$widget .= "\t<div class=\"widgetfooter\"></div>\r\n";
			$widget .= "</div>\r\n"; //end well
			$widget .= "</div>\r\n"; //end widget
        }
    }
    return $widget;
}

function showhome($posisi) {
global $mysql;
    global $lang, $cfg_img_path, $cfg_fullsizepics_path, $cfg_thumb_url, $cfg_thumb_path,
    $cfg_max_cols, $cfg_max_width, $cfg_thumb_width, $cfg_img_url, $mainblockwidth,
    $cfg_carousel_height, $urlfunc, $jumlahtampilanmarquee, $cfg_app_url,$style_css,$script_js,
    $carousel_bgcolor, $config_site_templatefolder, $cfg_app_path, $namabulan, $pakaicart, $pakaiongkir, $ismobile, $script_js, $style_css, $isi, $sort, $cfg_fullsizepics_url;
    global $class_product_row, $class_product_item, $class_home;
    $sql1 = "SELECT h.id, h.type, h.judul, h.isi, h.isborder, ht.modul, ht.jenis FROM home h, hometype ht WHERE h.posisi='$posisi' AND h.type=ht.id ORDER BY h.urutan";
    $result1 = $mysql->query($sql1);
    $i = 0;
    if ($result1 and $mysql->num_rows($result1) > 0) {
        while (list($homeblockid, $type, $judul, $isi, $isborder, $namamodul, $jenis) = $mysql->fetch_row($result1)) {
            $i++;
            // if ($isborder) {
                // $home .= "<div class=\"block$class_home_row\" id=\"$posisi$i\">\r\n";
                // $home .= "\t<div class=\"blocktitle\"><div class=\"wrapper\">$judul</div></div>\r\n";
                // $home .= "\t\t<div class=\"blockcontent\">\r\n";
                // $home .= "\t\t\t<div class=\"wrapper\">\r\n";
                // if (file_exists("$cfg_app_path/modul/$namamodul/urasi.php"))
                    // include ("$cfg_app_path/modul/$namamodul/urasi.php");
                // $sql = "SELECT konstanta, terjemahan FROM translation WHERE modul='$namamodul'";
                // $result = $mysql->query($sql);
                // while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
                    // define($konstanta, $terjemahan);
                // }
                // if ($_SESSION['ismobile'] && file_exists("$cfg_app_path/modul/$namamodul/mhomeaksi.php")) {
                    // include ("$cfg_app_path/modul/$namamodul/mhomeaksi.php");
                // } else {
                    // if (file_exists("$cfg_app_path/modul/$namamodul/homeaksi.php"))
                        // include ("$cfg_app_path/modul/$namamodul/homeaksi.php");
                // }
                // $home .= "\t\t\t</div>\r\n"; //end wrapper
                // $home .= "\t\t</div>\r\n"; //end content
                // $home .= "\t<div class=\"blockfooter\"></div>\r\n";
                // $home .= "</div>\r\n"; //end block
                // $home .= "<div class=\"blockseparator\"></div>\r\n";
            // } else {
                // //$home .= "<div class=\"block\">\r\n";
                // $home .= "\t<div class=\"blocknoborder\">\r\n";
                // if (file_exists("$cfg_app_path/modul/$namamodul/urasi.php"))
                    // include ("$cfg_app_path/modul/$namamodul/urasi.php");
                // $sql = "SELECT konstanta, terjemahan FROM translation WHERE modul='$namamodul'";
                // $result = $mysql->query($sql);
                // while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
                    // define($konstanta, $terjemahan);
                // }
                // if ($_SESSION['ismobile'] && file_exists("$cfg_app_path/modul/$namamodul/mhomeaksi.php")) {
                    // include ("$cfg_app_path/modul/$namamodul/mhomeaksi.php");
                // } else {
                    // if (file_exists("$cfg_app_path/modul/$namamodul/homeaksi.php"))
                        // include ("$cfg_app_path/modul/$namamodul/homeaksi.php");
                // }
                // $home .= "\t</div>\r\n"; //end blockcontent
                // //$home .= "</div>\r\n";	//end block
                // $home .= "<div class=\"blockseparator\"></div>\r\n";
            // }
			
				$home .= "<div class=\"content-wrapper\">\r\n";
                $home .= "\t<div class=\"row\">\r\n";
				if ($isborder) $home .= "<div class=\"col-lg-12\"><h1 class=\"page-header\">$judul</h1></div>\r\n";
                if (file_exists("$cfg_app_path/modul/$namamodul/urasi.php"))
                    include ("$cfg_app_path/modul/$namamodul/urasi.php");
                $sql = "SELECT konstanta, terjemahan FROM translation WHERE modul='$namamodul'";
                $result = $mysql->query($sql);
                while (list($konstanta, $terjemahan) = $mysql->fetch_row($result)) {
                    define($konstanta, $terjemahan);
                }
                if ($_SESSION['ismobile'] && file_exists("$cfg_app_path/modul/$namamodul/mhomeaksi.php")) {
                    include ("$cfg_app_path/modul/$namamodul/mhomeaksi.php");
                } else {
                    if (file_exists("$cfg_app_path/modul/$namamodul/homeaksi.php"))
                        include ("$cfg_app_path/modul/$namamodul/homeaksi.php");
                }
                $home .= "\t</div>\r\n";
                $home .= "</div>\r\n"; //end content-wrapper			
        }
    }
    return $home;
}

function searchbox($namamodul) {
	global $urlfunc, $config_site_isfriendly;
	if ($config_site_isfriendly && $_SESSION['s_userid'] == '') { //jun 7:53 08/01/2012: cek $_SESSION['s_userid'] agar tak dipanggil dari kelola
		$event = "submitSearchForm('" . $urlfunc->makePretty("?p=$namamodul&action=search") . "'); return false;";
		$searchform = "<form class=\"form-horizontal\" method=\"GET\" action=\"$thisfile\" onsubmit=\"$event\" >\r\n";
		$searchform .= "<div class=\"control-group\">\r\n";
		$searchform .= "<input type=\"hidden\" name=\"action\" value=\"search\" />\r\n";
		$searchform .= "<input type=\"hidden\" name=\"p\" value=\"$namamodul\" />\r\n";
		$searchform .= param_input();
		$searchform .= "<div class=\"controls\">\r\n";
		$searchform .= "<input class=\"searchboxtext\" type=\"text\" placeholder=\"" . _SEARCH . "\" name=\"keyword\" id=\"keyword\" />\r\n";
		$searchform .= "<input class=\"span2 buton\" type=\"submit\" value=\"" . _SEARCH . "\" />\r\n";
		$searchform .= "</div>\r\n";
		$searchform .= "</div>\r\n";
		$searchform .= "</form>\r\n";
	} else {
		$searchform = "<form class=\"form-horizontal\" method=\"GET\" action=\"$thisfile\">\r\n";
		$searchform .= "<div class=\"control-group\">\r\n";
		$searchform .= "<input type=\"hidden\" name=\"action\" value=\"search\" />\r\n";
		$searchform .= "<input type=\"hidden\" name=\"p\" value=\"$namamodul\" />\r\n";
		$searchform .= param_input();
		$searchform .= "<input class=\"searchboxtext\" type=\"text\" placeholder=\"" . _SEARCH . "\" name=\"keyword\" id=\"keyword\" />\r\n";
		$searchform .= "<input class=\"buton\" type=\"submit\" value=\"" . _SEARCH . "\" />\r\n";
		$searchform .= "</div>\r\n";
		$searchform .= "</form>\r\n";
	}
	return $searchform;
}

function fiestopass($raw) {
//md5 is 32 digit,max symbol is 2 digit so password should be varchar(64)
    $symbol['0'] = "_^";
    $symbol['1'] = "^";
    $symbol['2'] = "[";
    $symbol['3'] = "}_";
    $symbol['4'] = "[]";
    $symbol['5'] = "_}";
    $symbol['6'] = "]";
    $symbol['7'] = "^_";
    $symbol['8'] = "{";
    $symbol['9'] = "^{";
    $symbol['a'] = "]_";
    $symbol['b'] = "]}";
    $symbol['c'] = "_";
    $symbol['d'] = "}";
    $symbol['e'] = "^}";
    $symbol['f'] = "{_";
    $pwd = 'F!#' . $raw . '5t0' . 'bumbunyaswu' . strlen($raw);
    $pwd = md5(sha1($pwd));
    for ($i = 0; $i < strlen($pwd); $i++) {
        $cooked .= $symbol[substr($pwd, $i, 1)];
    }
    return $cooked;
}

/* validatemail jadi emailvalidation */

function validatemail($email) {
    if (preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/", $email) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/", $email)) {
        return false;
    } else {
        return true;
    }
}

function fiestophpmailer($to, $subject, $txtmsg, $from, $namafrom = '', $replyto = '', $htmlmsg = '', $attachments = '') {
    global $cfg_app_path, $smtpsecure, $smtphost, $smtpport, $smtpuser, $smtppass, $serverlocation;
    /*
      $to : email tujuan, bisa lebih dari satu (dipisah tanda koma, tidak pengaruh apakah ada atau tidak ada spasi setelah koma)
      $subject : subject email
      $txtmsg : body plain teks
      $from : email pengirim
      $namafrom : jika mau ada nama lengkap pengirim
      $replyto : jika mau reply tidak kembali ke from
      $htmlmsg (optional) : body HTML
      $attachments : path file yang diattach, bisa lebih dari satu (dipisah tanda koma, tidak pengaruh apakah ada atau tidak ada spasi setelah koma)
     */
	// $serverlocation == 'id';
    $r_kepada = explode(',', $to);
    if ($serverlocation == 'off') {
        fiestolog('', 'email.txt', 'w');
        foreach ($r_kepada as $kepada) {
            $kepada = trim($kepada);
            $somecontent .= "To: $kepada\r\n";
            $somecontent .= "Subject: $subject\r\n";
            $somecontent .= "Message: $txtmsg\r\n";
            if ($namafrom != '') {
                $somecontent .= "From: $namafrom <$from>\r\n";
            } else {
                $somecontent .= "From: $from\r\n";
            }
            if ($replyto != '')
                $somecontent .= "Reply To: $replyto\r\n";

            fiestolog($somecontent, 'email.txt');
            $rec = date("d/m/y H:i:s") . "\t$kepada\t$kepada\r\n";
            fiestolog($rec, 'logmail.txt');
        }
        return true;
    } else {
	
        $mail = new PHPMailer();
        $mail->Host = $smtphost;
        $mail->Port = $smtpport;
        $mail->Username = $smtpuser;
        $mail->Password = $smtppass;
        $mail->WordWrap = 50;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Subject = $subject;
        $mail->From = $from;
        if ($htmlmsg == '') {
            $mail->IsHTML(false);
            $mail->Body = $txtmsg;
        } else {
            $mail->IsHTML(true);
            $mail->Body = $htmlmsg;
            $mail->AltBody = $txtmsg;
        }
        if ($namafrom != '')
            $mail->FromName = $namafrom;
        if ($replyto != '')
            $mail->AddReplyTo($replyto);
        if ($smtpsecure != '')
            $mail->SMTPSecure = $smtpsecure;
        if ($attachments != '') {
            $r_attachments = explode(',', $attachments);
            foreach ($r_attachments as $attachment) {
                $attachment = trim($attachment);
                $mail->AddAttachment($attachment);
            }
        }
		foreach ($r_kepada as $kepada) {
			$kepada = trim($kepada);
			$mail->ClearAddresses();
			$mail->AddAddress($kepada);
			if ($mail->Send()) {
				$rec = date("d/m/y H:i:s") . "\t$kepada\t$kepada\r\n";
				fiestolog($rec, 'logmail.txt');
			} else {
				return false;
			}
		}
        return true;
    }
}

function fiestolog($string, $filename, $flag = 'a') {
    global $cfg_app_path;
    $handle = fopen("$cfg_app_path/logs/$filename", $flag);
    fwrite($handle, $string);
    fclose($handle);
    return true;
}

function logcounter() {
global $mysql;
	if(strpos($_SERVER['HTTP_USER_AGENT'],"bot")===false)
	{
		$visitor = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . time());
		if($_COOKIE['countertrack']=="")
		{
			setCookie("countertrack", $visitor, time() + 60 * 30); //definisi unique visitor = 30 menit
			$sql = "INSERT INTO counter(visitor, kunjungan, tanggal,ip,useragent) VALUES ('$visitor','1', NOW(),'".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."')";
			$mysql->query($sql);
		} else {
				$sql = "UPDATE counter SET kunjungan=kunjungan+1,last=NOW() WHERE visitor='".$_COOKIE['countertrack']."'";
				$mysql->query($sql);
		}
	}
}

function delcounter() {
global $mysql;
    $sql = "SELECT COUNT(kunjungan), SUM(kunjungan) FROM counter WHERE tanggal <= DATE_SUB(NOW(), INTERVAL 60 DAY)";
    $result = $mysql->query($sql);
    list($visitors, $hits) = $mysql->fetch_row($result);
    $sql = "UPDATE counterhistory SET nilai=nilai+$visitors WHERE nama='pasttimevisitors'";
    $mysql->query($sql);
    $sql = "UPDATE counterhistory SET nilai=nilai+$hits WHERE nama='pasttimehits'";
    $mysql->query($sql);
    $sql = "DELETE FROM counter WHERE tanggal <= DATE_SUB(NOW(), INTERVAL 60 DAY)";
    $mysql->query($sql);
}

function createurutan($tabel, $parent_id = "", $id = "") {
global $mysql;
    if ($tabel == "menu") {
        $kolom = "id, judul, urutan ";
        $kondisi = "parent='" . $parent_id . "' ";
        $sql = "SELECT $kolom FROM $tabel WHERE parent='" . $parent_id . "' AND id!='" . $id . "' ORDER BY urutan ASC ";
    }
    if ($tabel == "home" or $tabel == "widget") {
        $kolom = "id, judul, urutan ";
        $kondisi = "posisi='" . $parent_id . "' ";
        $sql = "SELECT $kolom FROM $tabel WHERE posisi='" . $parent_id . "' AND id!='" . $id . "' ORDER BY urutan ASC ";
    }
    if ($tabel == "bannercat" or $tabel == "filecat" or $tabel == "linkcat" or $tabel == "newscat" or $tabel == "roomlokasi") {
        $kolom = "id, nama, urutan ";
        $kondisi = "";
        $sql = "SELECT $kolom FROM $tabel WHERE id!='" . $id . "' ORDER BY urutan ASC ";
    }
    if ($tabel == "gallerycat" or $tabel == "catalogcat") {
        $kolom = "id, nama, urutan ";
        $kondisi = "parent='" . $parent_id . "' ";
        $sql = "SELECT $kolom FROM $tabel WHERE parent='" . $parent_id . "' AND id!='" . $id . "' ORDER BY urutan ASC ";
    }
    if (strlen($sql) > 0) {
        $result = $mysql->query($sql);
        $total = $mysql->num_rows($result);

        $bottomval = 1;

        $sqlurut = "SELECT id FROM $tabel 
				WHERE urutan<(
					SELECT urutan FROM $tabel WHERE id='" . $id . "' ";
        $sqlurut .= ( (strlen($kondisi) > 0) ? "AND $kondisi " : "");
        $sqlurut .= ") ";
        $sqlurut .= ( (strlen($kondisi) > 0) ? "AND $kondisi " : "");
        $sqlurut .= "ORDER BY urutan DESC LIMIT 1 ";

        $resulturut = $mysql->query($sqlurut);
        list($idprev) = $mysql->fetch_row($resulturut);

        $hasil = "<select name=\"urutan\" id=\"urutan\">";

        if ($total > 0) {
            $hasilopsi = "";
            while (list($iddt, $judul, $urutan) = $mysql->fetch_row($result)) {
                $selected = (($iddt == $idprev) ? "selected=\"selected\" " : "");
                $hasilopsi .= "<option value=\"" . ($urutan + 1) . "\" $selected >" . _BELOW . " " . $judul . "</option>";
                $bottomval = $urutan + 1;
            }
        }

        if ($total > 0) {
            $selected = ((strlen($idprev) == 0 and $total > 0) ? "selected=\"selected\" " : "");
            if (strlen($id) > 0) {
                $sqlmax = "SELECT MAX(urutan) as maximum FROM $tabel ";
                $sqlmax .= ( (strlen($kondisi) > 0) ? "WHERE $kondisi " : "");
                $resultmax = $mysql->query($sqlmax);
                if ($mysql->num_rows($resultmax) > 0) {
                    list($maxurutan) = $mysql->fetch_row($resultmax);
                    if ($maxurutan > 0) {
                        $sqlmaxid = "SELECT id FROM $tabel WHERE urutan='$maxurutan' ";
                        $sqlmaxid .= ( (strlen($kondisi) > 0) ? "AND $kondisi " : "");
                        $resultmaxid = $mysql->query($sqlmaxid);
                        if ($mysql->num_rows($resultmaxid) > 0) {
                            list($maxurutanid) = $mysql->fetch_row($resultmaxid);
                            if ($maxurutanid == $id and $maxurutanid > 0 and $total <= 1) {
                                $selected = "selected=\"selected\" ";
                            } else {
                                $selected = "";
                            }
                        }
                    }
                }
            }

            $hasil .= "<option value=\"$bottomval\" $selected>" . _BOTTOMMOST . "</option>";
        }

        $selected = ((strlen($idprev) == 0 and $total == 0) ? "selected=\"selected\" " : "");
        if (strlen($selected) == 0 and strlen($idprev) == 0 and strlen($id) > 0) {
            $sqlpalingatas = "SELECT id FROM $tabel WHERE id='" . $id . "' ";
            $sqlpalingatas .= ( (strlen($kondisi) > 0) ? "AND $kondisi " : "");
            $resultpalingatas = $mysql->query($sqlpalingatas);
            if ($mysql->num_rows($resultpalingatas) > 0) {
                $selected = "selected=\"selected\" ";
            }
        }
        $hasil .= "<option value=\"1\" $selected>" . _TOPMOST . "</option>";
        if ($total > 1) {
            $hasil .= $hasilopsi;
        }
        /*
          elseif(strlen($id)>0)
          {	$hasil .= $hasilopsi;
          }
         */
        $hasil .= "</select>";
        return $hasil;
    }
}

function urutkan($tabel, $urutan, $kondisi = "", $id = "", $kondisiprev = "") {
global $mysql;
    $urutansebelumnya = "";
    $kondisi = trim($kondisi);
    $kondisiprev = trim($kondisiprev);
    if (strlen($id) > 0) {
        $sqlurut = "SELECT urutan FROM $tabel WHERE id='" . $id . "'";
        $resulturut = $mysql->query($sqlurut);
        list($urutansebelumnya) = $mysql->fetch_row($resulturut);
        $mx = getMaxNumber($tabel, 'urutan') + 5;
        $sql = "UPDATE $tabel SET urutan='$mx' WHERE id='" . $id . "'";
        $result = $mysql->query($sql);
    }
    $sql = "UPDATE $tabel SET urutan=urutan+1 WHERE ";
    $sql .= "urutan>='" . $urutan . "' ";
    $sql .= ( (strlen($kondisi) > 0) ? "AND $kondisi" : "");
    $result = $mysql->query($sql);
    if (strlen($urutansebelumnya) > 0 and $kondisi == $kondisiprev) {
        $sql = "UPDATE $tabel SET urutan=urutan-1 WHERE urutan>='" . ($urutansebelumnya + 1) . "' " . ((strlen($kondisi) > 0) ? "AND $kondisi" : "");
        $result = $mysql->query($sql);
    }

    $sql = "select * FROM $tabel " . ((strlen($kondisi) > 0) ? "WHERE $kondisi" : "");
    $total = $mysql->num_rows($mysql->query($sql));
    //echo $sql;

    if (strlen($urutansebelumnya) > 0 and $kondisi == $kondisiprev and $urutansebelumnya < $urutan) {
        if ($urutan > ($total + 1)) {
            $urutan = $total;
        }
        $sql = "UPDATE $tabel SET urutan='" . ($urutan - 1) . "' WHERE id='" . $id . "'";
    } else {
        if ($urutan > $total) {
            $urutan = $total;
        }
        $sql = "UPDATE $tabel SET urutan='" . $urutan . "' WHERE id='" . $id . "'";
    }
    $result = $mysql->query($sql);
    return $result;
}

function getMaxNumber($tabel, $kolom, $kondisi = "") {
global $mysql;
    $sql = "SELECT max($kolom) FROM $tabel ";
    if (strlen($kondisi) > 0) {
        $sql .= "WHERE $kondisi ";
    }
    $result = $mysql->query($sql);
    list($max) = $mysql->fetch_row($result);
    return $max;
}

function urutkansetelahhapus($tabel, $kondisi = "") {
global $mysql;
    $sql = "SELECT id, urutan FROM $tabel " . ((strlen($kondisi) > 0) ? "WHERE $kondisi" : "") . " ORDER BY urutan ASC ";
    $result = $mysql->query($sql);
    $index = 1;
    while (list($id, $urutan) = $mysql->fetch_row($result)) {
        if ($urutan != $index) {
            $sqlupdate = "UPDATE $tabel SET urutan='" . $index . "' WHERE id='" . $id . "'";
            $mysql->query($sqlupdate);
        }
        $index++;
    }
}

function ftp_sync($dir) {

    global $conn_id;

    if ($dir != ".") {
        if (ftp_chdir($conn_id, $dir) == false) {
            echo ("<p>Change Dir Failed: $dir</p>\r\n");
            return;
        }
        if (!(is_dir($dir)))
            mkdir($dir);
        chdir($dir);
    }

    $contents = ftp_nlist($conn_id, ".");
    foreach ($contents as $file) {

        if ($file == '.' || $file == '..')
            continue;

        if (@ftp_chdir($conn_id, $file)) {
            ftp_chdir($conn_id, "..");
            ftp_sync($file);
        } else
            ftp_get($conn_id, $file, $file, FTP_BINARY);
    }

    ftp_chdir($conn_id, "..");
    chdir("..");
}

function smartcopy($source, $dest, $options = array('folderPermission' => 0755, 'filePermission' => 0644)) {
    /**
     * Copy file or folder from source to destination, it can do
     * recursive copy as well and is very smart
     * It recursively creates the dest file or directory path if there weren't exists
     * Situtaions :
     * - Src:/home/test/file.txt ,Dst:/home/test/b ,Result:/home/test/b -> If source was file copy file.txt name with b as name to destination
     * - Src:/home/test/file.txt ,Dst:/home/test/b/ ,Result:/home/test/b/file.txt -> If source was file Creates b directory if does not exsits and copy file.txt into it
     * - Src:/home/test ,Dst:/home/ ,Result:/home/test/** -> If source was directory copy test directory and all of its content into dest     
     * - Src:/home/test/ ,Dst:/home/ ,Result:/home/**-> if source was direcotry copy its content to dest
     * - Src:/home/test ,Dst:/home/test2 ,Result:/home/test2/** -> if source was directoy copy it and its content to dest with test2 as name
     * - Src:/home/test/ ,Dst:/home/test2 ,Result:->/home/test2/** if source was directoy copy it and its content to dest with test2 as name
     * @todo
     *     - Should have rollback technique so it can undo the copy when it wasn't successful
     *  - Auto destination technique should be possible to turn off
     *  - Supporting callback function
     *  - May prevent some issues on shared enviroments : http://us3.php.net/umask
     * @param $source //file or folder
     * @param $dest ///file or folder
     * @param $options //folderPermission,filePermission
     * @return boolean
     */
    $result = false;

    if (is_file($source)) {
        if ($dest[strlen($dest) - 1] == '/') {
            if (!file_exists($dest)) {
                cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
            }
            $__dest = $dest . "/" . basename($source);
        } else {
            $__dest = $dest;
        }
        $result = copy($source, $__dest);
        chmod($__dest, $options['filePermission']);
    } elseif (is_dir($source)) {
        if ($dest[strlen($dest) - 1] == '/') {
            if ($source[strlen($source) - 1] == '/') {
                //Copy only contents
            } else {
                //Change parent itself and its contents
                $dest = $dest . basename($source);
                @mkdir($dest);
                chmod($dest, $options['filePermission']);
            }
        } else {
            if ($source[strlen($source) - 1] == '/') {
                //Copy parent directory with new name and all its content
                @mkdir($dest, $options['folderPermission']);
                chmod($dest, $options['filePermission']);
            } else {
                //Copy parent directory with new name and all its content
                @mkdir($dest, $options['folderPermission']);
                chmod($dest, $options['filePermission']);
            }
        }

        $dirHandle = opendir($source);
        while ($file = readdir($dirHandle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($source . "/" . $file)) {
                    $__dest = $dest . "/" . $file;
                } else {
                    $__dest = $dest . "/" . $file;
                }
                //echo "$source/$file ||| $__dest<br />";
                $result = smartCopy($source . "/" . $file, $__dest, $options);
            }
        }
        closedir($dirHandle);
    } else {
        $result = false;
    }
    return $result;
}

function deltree($dir) {
    $files = glob($dir . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file))
            deltree($file);
        else
            unlink($file);
    }

    if (is_dir($dir))
        rmdir($dir);
}

class categories {

    var $cats = array();
    var $subs = num;
    var $cat_map = array();

    function get_cats_($cats_result, $parent_id = 0, $level = 1) {
        for ($i = 0; $i < count($cats_result); $i++) {
            if ($cats_result[$i]['parent'] == $parent_id) {
                $cats_result[$i]['level'] = $level;
                $this->cats[] = $cats_result[$i];
                $this->get_cats_($cats_result, $cats_result[$i]['id'], $level + 1, $type);
            }
        }
    }

    function get_cats($cats_result, $parent_id = 0, $level = 1) {
        $this->cats = array();
        $this->tmp_cats = array();
        $this->get_cats_($cats_result, $parent_id, $level);
    }

    function count_subs($id, $cats_result) {
        $this->tmp_cats = array();
        $this->subs = NULL;
        $this->get_cats($cats_result, $id, 1);
        $this->subs = count($this->tmp_cats);
    }

    function cat_map_($id, $cats_result) {
        for ($i = 0; $i < count($cats_result); $i++) {
            $cats_result_[$cats_result[$i]['id']] = $cats_result[$i];
        }
        while (list($a, $b) = @each($cats_result_)) {
            if ($cats_result_[$id]['parent'] > 0 && $cats_result_[$id]['parent'] == $cats_result_[$a]['id']) {
                $this->cat_map[] = $cats_result_[$a];
                if ($cats_result_[$a]['parent'] > 0) {
                    $this->cat_map_($cats_result_[$a]['id'], $cats_result, $type);
                }
            }
        }
    }

    function cat_map($id, $cats_result) {
        @$this->cat_map = array();
        @$this->tmp_cat_map = array();
        $this->cat_map_($id, $cats_result);
        $this->cat_map = @array_reverse($this->cat_map);
    }

}

/**
 * Mobile Detect
 *
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version    SVN: $Id: Mobile_Detect.php 3 2009-05-21 13:06:28Z vic.stanciu $
 */
class Mobile_Detect {

    protected $accept;
    protected $userAgent;
    protected $isMobile = false;
    protected $isAndroid = null;
    protected $isBlackberry = null;
    protected $isOpera = null;
    protected $isPalm = null;
    protected $isWindows = null;
    protected $isGeneric = null;
    protected $isIphone = null;
    protected $isIpad = null;
    protected $devices = array(
        "android" => "android",
        "blackberry" => "blackberry",
        "iphone" => "(iphone|ipod)",
        "ipad" => "ipad",
        "opera" => "(opera mini|opera mobi)",
        "palm" => "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
        "windows" => "windows ce; (iemobile|ppc|smartphone)",
        "generic" => "(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|nokia|samsung|SonyEricsson)"
    );

    public function __construct() {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->accept = $_SERVER['HTTP_ACCEPT'];

        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            $this->isMobile = true;
        } elseif (strpos($this->accept, 'text/vnd.wap.wml') > 0 || strpos($this->accept, 'application/vnd.wap.xhtml+xml') > 0) {
            $this->isMobile = true;
        } else {
            foreach ($this->devices as $device => $regexp) {
                if ($this->isDevice($device)) {
                    $this->isMobile = true;
                }
            }
        }
    }

    /**
     * Overloads isAndroid() | isBlackberry() | isOpera() | isPalm() | isWindows() | isGeneric() through isDevice()
     *
     * @param string $name
     * @param array $arguments
     * @return bool
     */
    public function __call($name, $arguments) {
        $device = strtolower(substr($name, 2));
        if ($name == "is" . ucfirst($device)) {
            return $this->isDevice($device);
        } else {
            trigger_error("Method $name not defined", E_USER_ERROR);
        }
    }

    /**
     * Returns true if any type of mobile device detected, including special ones
     * @return bool
     */
    public function isMobile() {
        return $this->isMobile;
    }

    protected function isDevice($device) {
        $var = "is" . ucfirst($device);
        $return = $this->$var === null ? (bool) preg_match("/" . $this->devices[$device] . "/i", $this->userAgent) : $this->$var;

        if (($device != 'generic' && $return == true) || $device == 'ipad') {
            $this->isGeneric = false;
        }

        return $return;
    }

}

// Generate Guid
function Guid($autoincrement) {
global $mysql;
//    HOW TO USE
//    $qShowStatusTranscation = "SHOW TABLE STATUS LIKE 'sc_transaction'";
//    $qShowStatusResult = $mysql->query($qShowStatusTranscation);
//    $row = $mysql->fetch_assoc($qShowStatusResult);
//    $transactionIncrement = $row['Auto_increment'];
//    $Guid = Guid($transactionIncrement);
//    $transId = $Guid;
    $autoincrement = str_pad($autoincrement, 4, "0", STR_PAD_LEFT);
    $s = strtoupper(md5(uniqid(rand(), true)));
    $guidText = substr($s, 0, 7) . $autoincrement . (substr($s, 8, 1));
    return $guidText;
}

function generateRandomString($length = 2) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function NewGuid($autoincrement, $form = 'cart') {
	$y = date('y');
	$m = date('m');
    $autoincrement = str_pad($autoincrement, 4, "0", STR_PAD_LEFT);
    // $s = strtoupper(generateRandomString());
	if ($form == 'cart') {
		$s = strtoupper(md5(uniqid(rand(), true)));
	} else {
		$s = strtoupper(generateRandomString());
	}
    $guidText = substr($s, 0, 2) . $y.$m.$autoincrement;
    return $guidText;
}

// End Generate Guid 
/*
  class PrettyURL {

  //bypass pretty for v2.x
  //jika ingin pakai pretty, silakan refer to v1.x
  function makePretty($stringurl, $title=array()) {
  return $stringurl;
  }

  }
 */

class PrettyURL {

    var $parts;
    var $basename;
    var $nama_var_modul = "p";
    var $nama_var_action = "action";

    function makePretty($stringurl, $title = array()) 
	{
       global $cfg_app_url,$config_site_isfriendly, $id_separator;
		
		$url=parse_url($stringurl);
		parse_str($url['query'],$vars);
		while(list($k,$v) = each($vars))
		{
			if(array_key_exists($k,$title))
			{	
				$v = $v.$id_separator.$this->makePrettyText(strtolower($title[$k]));
			}
			$strurl[]=$v;
		}
		$join_url="";
		if(count($strurl)>0)
		{
		$join_url="/".join("/",$strurl);
		}
		
		return $cfg_app_url.$join_url;
    }

    function parseURL() {
        global $cfg_app_url;
        // grab URL query string and script name
        //$uri = $_SERVER['REQUEST_URI'];
        $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        $uri = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $uri = str_replace($cfg_app_url, "", $uri);

        $script = $_SERVER['SCRIPT_NAME'];
        //get extension
        $exp_d = explode(".", $script);
        $ext = end($exp_d);

        // if extension is found in URL, eliminate it
        if (strstr($uri, ".")) {
            $arr_uri = explode('.', $uri);
            // get last part
            $last = end($arr_uri);

            if ($last == $ext) {
                array_pop($arr_uri);
                $uri = implode('.', $arr_uri);
            }
        }

        // pick the name without extension
        $basename = basename($script, '.' . $ext);
        // slicing query string
        $temp = explode('/', $uri);
        $key = array_search($basename, $temp);
        $parts = array_slice($temp, $key + 1);
        $this->basename = $basename;
        $this->parts = $parts;
    }

    function getBasename() { //	return array of sliced query string
        return $this->basename;
    }

    function getParts() { //	return array of sliced query string
        return $this->parts;
    }

    function setParts() {
        // pair off query string variable and query string value
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $urlparts = $this->getParts();
        for ($i = 0; $i < $numargs; $i++) {
            // make them available for webpage
            eval("\$GLOBALS['" . $arg_list[$i] . "']= '$urlparts[$i]';");
        }
    }

    function makePrettyText($text) {
        $letters = array(" ", "-");
        $pretty_text = trim($text);
        $pretty_text = strip_tags($pretty_text);
        $pretty_text = str_replace($letters, "_", $pretty_text);
        $pretty_text = preg_replace('/[^a-z0-9_]/i', '', $pretty_text);
        return $pretty_text;
    }

}

////////////////////GENERATE FIELD URL////////////////////// 
function cleanInput($text,$type="") 
{
	if($type="url")
	{
	$letters = array(" ", "-");
	$pretty_text = trim($text);
	$pretty_text = strip_tags($pretty_text);
	$pretty_text = str_replace($letters, "_", $pretty_text);
	$pretty_text = preg_replace('/[^a-z0-9_]/i', '', $pretty_text);
	}
	return trim($pretty_text);
}
function updateUrl($table="",$title="",$id="")
{
global $mysql;
	if($table!="" and $title!="" and $id!="")
	{
		if(isTableExist($table))
		{
			$url=cleanInput($title,"url");
			$kondisi=$id!=""?" AND id<>'$id'":"";
			$q=$mysql->query("SELECT url FROM $table WHERE url='$url' $kondisi");
			if($q and $mysql->num_rows($q)>0)
			{
				$d=$mysql->fetch_assoc($q);
				$url=$id."_".$d['url'];
			}
			$update=$mysql->query("UPDATE $table SET url='$url' WHERE id='$id'");
		}
	}
	else
	{
	die("parameter tidak boleh kosong =>updateUrl(table,title,id)");
	}
	//return $url;
}
function isTableExist($table)
{
global $mysql;
$q=$mysql->query("SHOW TABLE STATUS LIKE '$table'");
if($q and $mysql->num_rows($q))
{
	return true;
}
else
{
die(" nama tabel $table tidak ada di database ");
}
}
function getAutoIncrement($table="")
{
if($table!="")
{
	$auto_increment=1;
	$q=$mysql->query("SHOW TABLE STATUS LIKE '$table'");
	if($q and $mysql->num_rows($q))
	{
	$d=$mysql->fetch_assoc($q);
	$auto_increment=$d['Auto_increment'];
	return $auto_increment;
	}
	else
	{
	die(" nama tabel $table tidak ada di database ");
	}
	
}
else
{
die(" parameter nama tabel belum di isi : getAutoIncrement(namatabel) ");
}

}
////////////////////END GENERATE FIELD URL////////////////////// 
function xmlurl($url) {
    global $config_site_isfriendly, $cfg_app_url;
    if (!$config_site_isfriendly)
        $url = "$cfg_app_url/index.php$url";
    $url = htmlspecialchars($url, ENT_COMPAT, 'UTF-8');
    return $url;
}

function cekformsecret() {
    /* dijalankan ketika save atau update */
    $kondisi = true;
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';
    if (isset($_SESSION["FORM_SECRET"])) {
        if (strcasecmp($form_secret, $_SESSION["FORM_SECRET"]) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $kondisi = true;
            unset($_SESSION["FORM_SECRET"]);
        } else {
            //	echo "gak cocok";
            $kondisi = false;
        }
    } else {
        //	echo "no key";
        //Secret key missing
        $kondisi = false;
    }
    return $kondisi;
}

function setformsecret() {
    /* dijalankan ketika form add panggil */
    $secret = md5(uniqid(rand(), true));
    $_SESSION['FORM_SECRET'] = $secret;
    /* tambahkan sript dibawah ini taruh didalam form add
     * $content.=setformsecret();
     */
    $content .="<input type=\"hidden\" name=\"form_secret\" id=\"form_secret\" value=\"" . $_SESSION['FORM_SECRET'] . "\" />";
    return $content;
}
function videohelp()
{
	global $video_help,$script_js,$domainid;
	
	$w=125;
	$h=113;
	$video_url='';
	$p=$_GET['p'];
	$action=$_GET['action'];
	$step=$_GET['step'];
	$varcookie="disable_video_{$step}_".$domainid;
	
	////////////////SETTING YOUTUBE BERDASARKAN PARAMETER URL
	/*
	EX :$video_url="ubGpDoyJvmI"; $title_url="Produk";
	*/
//p=site&action=dashboard&step=1
	if($p=='catalog' and $action==''){$video_url="hZp6djnLB5M"; $title_url="Produk";}
	elseif($p=='site' and $action=='dashboard' and $step=='1'){$video_url="-XpnBnxsU0E"; $title_url="Langkah 1";}
	elseif($p=='template' and $action=='' and $step=='2'){$video_url="6tafz1AShFc"; $title_url="Langkah 2";}
	elseif($p=='decoration' and $action=='' and $step=='3'){$video_url="iUoUHIr0CbY"; $title_url="Langkah 3";}
	elseif($p=='catalog' and $action=='add' and $step=='4'){$video_url="rXUEY8zmq8A"; $title_url="Langkah 4";}
	elseif($p=='sc_payment' and $action=='' and $step=='5'){$video_url="PDnbkBfOwbY"; $title_url="Langkah 5";}
	elseif($p=='sc_postage' and $action=='' and $step=='6'){$video_url="sVKh_5hc-Mw"; $title_url="Langkah 6";}
	elseif($p=='site' and $action=='launch' and $step=='7'){$video_url="D17Q9kNfPk4"; $title_url="Langkah 7";}
	
	//non toko online
	elseif($p=='page' and $step=='4'){$video_url="QBfq8MvYMFs"; $title_url="Langkah 4";}
	elseif($p=='gallery' and $action=='add' and  $step=='5'){$video_url="Zeux0qnDm-I"; $title_url="Langkah 5";}
	
	else if($p=='catalog' and $action=='main'){$video_url="Kg3yHKmGb2w"; $title_url="Kategori";}
	else if($p=='catalog' and $action=='add'){$video_url="Kg3yHKmGb2w"; $title_url="Tambah Produk";}
	else if($p=='catalog' and $action=='edit'){$video_url="Kg3yHKmGb2w"; $title_url="Edit Produk";}
	else if($p=='catalog' and $action=='delete'){$video_url="Kg3yHKmGb2w"; $title_url="Hapus Produk";}
	else if($p=='catalog' and $action=='catedit'){$video_url="Kg3yHKmGb2w"; $title_url="Edit Kategori";}
	else if($p=='home' and $action==''){$video_url="n7Ng0YbTwkU"; $title_url="Home";}
	else if($p=='page' and $action==''){$video_url="UAG6NCGGarE"; $title_url="Halaman";}
	else if($p=='page' and $action=='add'){$video_url="UAG6NCGGarE"; $title_url="Tambah Halaman";}
	else if($p=='page' and $action=='modify'){$video_url="UAG6NCGGarE"; $title_url="Tambah Halaman";}
	else if($p=='decoration' and $action==''){$video_url="w_wf7Yo063g"; $title_url="Dekorasi";}
	else if($p=='counter' and $action==''){$video_url="LrRB5mQ0WW4"; $title_url="Counter";}
	else if($p=='banner' and $action==''){$video_url="oeiBPujlMwI"; $title_url="Banner";}
	else if($p=='widget' and $action==''){$video_url="D69v1QqzOOw"; $title_url="Widget";}
	else if($p=='site' and $action==''){$video_url="0ufa0JhEmMA"; $title_url="Umum";}
	else if($p=='site' and $action=='dashboard'){$video_url="P0GwizCobw4"; $title_url="Pengaturan Dasar";}
	else if($p=='template' and $action==''){$video_url="a92Qy_Z1NeQ"; $title_url="Template";}
	else if($p=='ym' and $action==''){$video_url="uGPPkEV-1-s"; $title_url="Support";}
	else if($p=='ym' and $action=='add'){$video_url="uGPPkEV-1-s"; $title_url="Support";}
	else if($p=='ym' and $action=='modify'){$video_url="uGPPkEV-1-s"; $title_url="Support";}
	else if($p=='ym' and $action=='remove'){$video_url="uGPPkEV-1-s"; $title_url="Support";}
	else if($p=='catalog' and $action=='attribut_add'){$video_url="3j3_Em1v1fg"; $title_url="Spek Tambahan";}
	else if($p=='sc_postage' and $action==''){$video_url="lLREiVtbLGI"; $title_url="Pengiriman";}
	else if($p=='sc_postage' and $action=='manual'){$video_url="lLREiVtbLGI"; $title_url="Pengiriman";}
	else if($p=='sc_postage' and $action=='add'){$video_url="lLREiVtbLGI"; $title_url="Pengiriman";}
	else if($p=='sc_postage' and $action=='modify'){$video_url="lLREiVtbLGI"; $title_url="Pengiriman";}
	else if($p=='sc_postage' and $action=='remove'){$video_url="lLREiVtbLGI"; $title_url="Pengiriman";}
	else if($p=='sc_payment' and $action==''){$video_url="-FtmHnbWRbI"; $title_url="Pembayaran";}
	else if($p=='sc_payment' and $action=='add'){$video_url="-FtmHnbWRbI"; $title_url="Pembayaran";}
	else if($p=='sc_payment' and $action=='modify'){$video_url="-FtmHnbWRbI"; $title_url="Pembayaran";}
	else if($p=='sc_payment' and $action=='remove'){$video_url="-FtmHnbWRbI"; $title_url="Pembayaran";}
	else if($p=='order' and $action=='step1'){$video_url="8wIm8CcLXQA"; $title_url="Order Terbaru";}
	else if($p=='order' and $action=='step3'){$video_url="3e5Rug_bb7s"; $title_url="Order Siap Kirim";}
	else if($p=='mgm' and $action==''){$video_url="WmrXrA3YfpU"; $title_url="MGM";}
	else if($p=='brand' and $action==''){$video_url="RTIQlJTbE-8"; $title_url="Merek";}
	else if($p=='brand' and $action=='add'){$video_url="RTIQlJTbE-8"; $title_url="Merek";}
	else if($p=='brand' and $action=='modify'){$video_url="RTIQlJTbE-8"; $title_url="Merek";}
	else if($p=='brand' and $action=='remove'){$video_url="RTIQlJTbE-8"; $title_url="Merek";}
	else if($p=='menu' and $action==''){$video_url="5dge6O3rHX4"; $title_url="Menu";}
	else if($p=='order' and $action=='timeinterval'){$video_url="2JFeWu1adqo"; $title_url="Laporan Order";}
	else if($p=='contact' and $action==''){$video_url="ZmGhs1s4x8Y"; $title_url="Kontak";}
	else if($p=='translation' and $action==''){$video_url="4iMMZKqj7uc"; $title_url="Istilah";}
	else if($p=='' and $action==''){$video_url="ZgCQhYYNIzw"; $title_url="Dashboard";}

	////////////////END SETTING YOUTUBE
	$video=false;
	if($video_url!='')
	{
	
		if($title_url=='')$title_url="help video";
		$video_help="<div class=\"admin-meta\">
					<a  title=\"$title_url\" class=\"various fancybox.iframe\" href=\"http://www.youtube.com/embed/$video_url?autoplay=1&rel=0\" ></a>
					</div>
					";
					
	$video=true;		
	}
	
	
	if($video_url!='' and $step!='' and ($_COOKIE[$varcookie]==0 OR $_COOKIE[$varcookie]=='') and $_SESSION[$varcookie]!=1)
	{
	$_SESSION["$varcookie"]=1;
	$script_js['load_first']=<<<END
	<script>
	function video_step_close(step)
	{
		check=$("#novideoawal").prop("checked");
		$.ajax(
		{
			type: "POST",
			url: "ajax.php?action=video_step&check="+check+"&step="+step
		});
		
	}
	jQuery(document).ready(function() {
	$.fancybox("<iframe scrolling='auto' frameborder='0' height='460px' width='580px' allowfullscreen='' mozallowfullscreen='' webkitallowfullscreen='' hspace='0' vspace='0'  src='http://www.youtube.com/embed/$video_url?autoplay=1&rel=0'></iframe><p class='video_awal_option'><input type='checkbox' id='novideoawal' onclick='video_step_close($step)'/> Jangan tampilkan lagi</p>",
		{
        	maxWidth	: 800,
			maxHeight	: 600,
			fitToView	: false,
			width		: "584px",
			height		: "495px",
			autoSize	: false,
			closeClick	: false,
			openEffect	: "none",
			closeEffect	: "none",
			helpers : {
			title : {
				type : "inside"
			},
			overlay : {
				css : {
					"background" : "rgba(238,238,238,0.85)"
				}
			}
			}
		}
	);
});
</script>
END;
	$video=true;
	}
	
	if(!$video)
	{
	
		global $cfg_app_path;
		$alamat_url=$_SERVER["REQUEST_URI"]."\r\n";
		fiestolog($alamat_url,"novideo.txt");
		$video_help="";
	}

	return $video_help;
}



function param_input()
{
//untuk menambahkan parameter di form yang menggunkan method get
$par=array("step","domain");//url tambahan
foreach($par as $i =>$v)
{
	if($_REQUEST[$v]!='')
	{
	$param_input.="<input type='hidden' name='$v' value='".($_REQUEST[$v])."' /> \r\n";
	}
}

return $param_input;
}
// function param_url($urlfull="",$param_add="")
// {
// /*
// fungsi ini dibuat untuk kebutuhan langkah 1,2 dll, gunanya untuk selalu menambahkan parameter step disetiap url

// param_url("?p=catalog&action=add","pid=1&cat=23")
// */
// //untuk menambahkan parameter di setiap url
	// //get path
	// $r_path = parse_url($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	// $path=$r_path['path'];
	
	// if($urlfull=="")
	// {
	// $urlfull=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	// }
	
	// if($urlfull<>'')
	// {
	// $url_parsed = parse_url($urlfull);
	// parse_str($url_parsed['query'], $url_parts);
	// }
// // print_r($url_parsed);
	
	// $par=array("step","domain");//url tambahan
	// $build_param;
	// foreach($par as $i =>$v)
	// {
		// if($_GET[$v]!='')
		// {
			// if($url_parts[$v]=='')
			// {
			// $url_parts[$v]=$_GET[$v];
			// }
		// }
	// }
	
		
	// if($param_add!='')
	// {
		// parse_str($param_add, $param_parts);
		// foreach($param_parts as $i => $v)
		// {
		// $url_parts[$i]=$param_parts[$i];
		// }
	// }
	// if($_SERVER['HTTP_HOST']=="localhost")
	// {
	// $build_param="http://".$path."?".http_build_query($url_parts);
	// }
	// else
	// {
	// $build_param="http://".$_SERVER['HTTP_HOST'].$path."?".http_build_query($url_parts);
	// }
	
	// return $build_param;
// }

function param_url($urlfull="",$param_add="")
{
/*
fungsi ini dibuat untuk kebutuhan langkah 1,2 dll, gunanya untuk selalu menambahkan parameter step disetiap url

param_url("?p=catalog&action=add","pid=1&cat=23")
*/
//untuk menambahkan parameter di setiap url
	if($urlfull=="")
	{
	$urlfull=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	if($urlfull<>'')
	{
	$url_parsed = parse_url($urlfull);
	parse_str($url_parsed['query'], $url_parts);
	}

	
	$par=array("step","domain");//url tambahan
	$build_param;
	foreach($par as $i =>$v)
	{
		if($_GET[$v]!='')
		{
			if($url_parts[$v]=='')
			{
			$url_parts[$v]=$_GET[$v];
			}
		}
	}
	
		
	if($param_add!='')
	{
		parse_str($param_add, $param_parts);
		foreach($param_parts as $i => $v)
		{
		$url_parts[$i]=$param_parts[$i];
		}
	}
	$build_param=$url_parsed['host'].$url_parsed['path']."?".http_build_query($url_parts);
	
	return $build_param;
}

function topmenu($paket)
{
	$menu=array();
	switch ($paket) 
	{
	case 1:
		
	$menu[]="<a class=\"button btn btn-notification menubutton\" href=\"./index.php?p=catalog&action=add\" title=\"Tambah Produk\"><i class=\"icon-plus-sign-alt\"></i></a>";
	$menu[]="<a class=\"button btn btn-notification menubutton\" href=\"./index.php?p=home\" title=\"Tampilkan Produk, Artikel, Banner, dll.\"><i class=\"icon-th\"></i></a>";		

		break;
	case 2:
	
	$menu[]="<a class=\"button btn btn-notification menubutton\" href=\"#\" title=\"Buat Artikel Baru\"><i class=\"icon-pencil\"></i></a>";
	$menu[]="<a class=\"button btn btn-notification menubutton\" href=\"./index.php?p=catalog&action=add\" title=\"Tambah Produk\"><i class=\"icon-plus-sign-alt\"></i></a>";
	$menu[]="<a class=\"button btn btn-notification menubutton\" href=\"./index.php?p=home\" title=\"Tampilkan Produk, Artikel, Banner, dll.\"><i class=\"icon-th\"></i></a>";

		break;
	}

	$build_menu=join("\r\n",$menu);
	return $build_menu;	
}
function dropdown_list_web()
{
global $cfg_app_url;
$domainid=$_GET['domain'];
$menu=array();
if(count($_SESSION['xwdshop_weblist'][1])>0)
{
	foreach($_SESSION['xwdshop_weblist'][1] as $id =>$v)
	{
		$active="";
		if($v==$domainid)$active="website_active";
		$menu[]=" <li class='$active'><a href=\"http://$v\">$v</a></li>";
	}
}
if(count($_SESSION['xwdshop_weblist'][2])>0)
{
	foreach($_SESSION['xwdshop_weblist'][2] as $id =>$v)
	{
		$active="";
		if($v==$domainid)$active="website_active";
		$menu[]=" <li class='$active'><a href=\"$cfg_app_url/kelola/?domain=$v\">"._EDIT." $v</a></li>";
	}
}
$build_menu=join("\r\n",$menu);
return $build_menu;	
}

function showtips()
{
global $masterdb;
global $mysql;
ob_start();
$q=$mysql->query("SELECT * from $masterdb.tips $kondisi");
if($q and $mysql->num_rows($q)>0)
{
	echo "<div class=\"row-fluid\">";
	while($d=$mysql->fetch_assoc($q))
	{
	echo "<div>".$d['judul']."</div>";
	echo "<div>".$d['isi']."</div>";
	}
	echo "</div>";
}
return ob_get_clean();
}
function last_auto_increment($table)
{
global $mysql;
$result = $mysql->query("SHOW TABLE STATUS WHERE `Name` = '$table'");
$data = $mysql->fetch_assoc($result);
$next_increment = $data['Auto_increment'];
return $next_increment;
}

function gCode($autoincrement, $pjg=8)
{
	$nilai=str_pad($autoincrement, $pjg, "0", STR_PAD_LEFT);
	$hasil=(($autoincrement%9)==0?9:($autoincrement%9)).$nilai;
	$result=base_convert($hasil,10,36);
	return strtoupper($result);
}

function crupiah($uang)
{
	$matauang="Rp ";
	return $matauang.number_format((float)$uang, 0, ',', '.');
}

function fuang($uang)
{
	return number_format((float)$uang, 0, ',', '.');
}

function show_error_log($error_logs)
{
	$str_len = strlen($error_logs);
	$error_logs = substr_replace($error_logs, '', $str_len-1, 1);
	$error_data = explode(";", $error_logs);
	$html .= "<div class=\"form-notif\">";
	$html .= "<div class=\"form-error\">";
	$html .= "<ul>";
	foreach($error_data as $i => $v)
	{
		$html .= "<li>".$v."</li>";
	}
	$html .= "</ul>";
	$html .= "</div>";
	$html .= "<div class=\"region-tombol-back\">";
	$html .= "<a href=\"javascript:window.history.go(-1)\" class=\"tombol-kembali\">"._BACK."</a>";
	$html .= "</div>";
	$html .= "</div>";
	return $html;
}

function recursive_dropdown($parent, $level, $cat)
{
global $mysql;
	for($i=0;$i<$level;$i++) $spasi .= "&nbsp;&nbsp;&nbsp;&nbsp;";
	$sql = "SELECT id, nama FROM catalogcat WHERE parent='$parent'";
	$result = $mysql->query($sql);
	if($mysql->num_rows($result))
	{
		while($data = $mysql->fetch_assoc($result))
		{
			$is_selected = ($cat == $data["id"]) ? "selected" : "";
			$html .= "<option class=\"ml-10\" value=\"".$data["id"]."\" ".$is_selected.">".$spasi.$data["nama"]."</option>";
			$html .= recursive_dropdown($data["id"], ($level+1), $cat);
		}
	}
	return $html;
}

function info_login_member()
{
global $mysql;
/*
	if(strlen($_SESSION['member_uname'])>0 AND strlen($_SESSION['member_uid'])>0 AND strlen($_SESSION['member_level'])>0)
	{
	return array('uname'=>$_SESSION['member_uname'],'uid'=>$_SESSION['member_uid'],'level'=>$_SESSION['member_level']);
	}
*/
	$sql = $mysql->query("SELECT level FROM webmember WHERE user_id='".$_SESSION['member_uid']."'");
	list($memberlevel) = $mysql->fetch_row($sql);
	if(strlen($_SESSION['member_uname'])>0 AND strlen($_SESSION['member_uid'])>0 AND strlen($memberlevel)>0)
	{	
	return array('uname'=>$_SESSION['member_uname'],'uid'=>$_SESSION['member_uid'],'level'=>$memberlevel);
	}
	else
	{
	unset($_SESSION['member_uname']);
	unset($_SESSION['member_uid']);
	unset($_SESSION['member_level']);
	return false;
	}
}

function get1Value($sql) {
global $mysql;
	$result = $mysql->query($sql);
	return $data = $mysql->fetch_row($result);
}

function get_brand_name($brand_id) {
global $mysql;
	$sql = "SELECT nama FROM catalogmerek WHERE id='$brand_id'";
	$result = $mysql->query($sql);
	list($data) = $mysql->fetch_row($result);
	
	return $data;
}

function get_cat_name($cat_id) {
global $mysql;
	$sql = "SELECT nama FROM catalogcat WHERE id='$cat_id'";
	$result = $mysql->query($sql);
	list($data) = $mysql->fetch_row($result);
	
	return $data;
}

function get_stock_from_api($code, $qty = 1) {
global $mysql;
	define(PF_URL, "https://www.perfect-corner.com//api/stock?");
	$curl = curl_init();
	$is_valid = true;
	
	curl_setopt_array($curl, array(
		CURLOPT_URL => PF_URL . "code=$code&qty=$qty",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET"
	));

	$json = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
	
	if ($err) {
		$is_valid = false;	//"cURL Error #:" . $err;
	} else {
		$response = json_decode($json, true);

		$status = $response['perfect_corner']['status']['code'];
		$enough = $response['perfect_corner']['results']['enough'];

		if ($status == 200) {
			if (isset($enough) && $enough == true) {
				$is_valid = true;
			} else {
				$is_valid = false;
			}
		} else {
			$is_valid = false;
		}
	}
	
	return $is_valid;
	
}

function seo_friendly_url($string, $table_name, $pid = ''){
global $mysql;
    $string = str_replace(array('[\', \']'), '', $string);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
    // $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
	
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
	
	$is_valid = false;
	
	$index = 0;
	$temp = $string;
	while(!$is_valid)
	{
		$sql = "SELECT url FROM ".$table_name." WHERE url='".$temp."'";
		if($pid!="") $sql .= " AND id!='".$pid."'";
		$query = $mysql->query($sql);
		if($mysql->num_rows($query)>0) 
		{
			$index++;
			$temp = $string . "" . $index;
		}
		else 
		{
			$is_valid = true;
			if($index > 0)
			{
				$string .= "" . $index;
			}
		}
	}
	
    return strtolower(trim($string, '-'));
}

function get_item_stock($pid) {
	global $mysql;
	
	$sql = "SELECT stock FROM catalogdata WHERE id='$pid'";
	$result = $mysql->query($sql);
	if ($mysql->num_rows($result) > 0) {
		list($stock) = $mysql->fetch_row($result);
		return $stock;
		// if ($quantityUpdated > $stock) {
			// $quantityUpdated = $stock;
		// }
	}
	return 0;
}

function cek_valid_tgl($kodevoucher) {
	global $mysql;
	$tglsekarang = date('Y-m-d');
	$q = $mysql->query("SELECT id FROM voucher WHERE kodevoucher='$kodevoucher' AND '$tglsekarang' BETWEEN tglstart AND tglend");
	if ($mysql->num_rows($q) == 0) {
		return false;
	} else {
		return true;
	}
}
	
function cek_min_belanja($kodevoucher, $total) {
	global $mysql;
	$q = $mysql->query("SELECT id FROM voucher WHERE kodevoucher='$kodevoucher' AND $total >= nominalbelanja");
	if ($mysql->num_rows($q) == 0) {
		return false;
	} else {
		return true;
	}
}

function cek_used_voucher($voucher_id, $member_id) {
global $mysql;
	$q = $mysql->query("SELECT id FROM voucher_detail WHERE voucher_id='$voucher_id' AND member_id='$member_id'");
	if ($mysql->num_rows($q)> 0) {
		return false;
	} else {
		return true;
	}
}

function readMore($string, $length = 1000, $separator = '...') 
{
	$string_post = $string;
	
	if (strlen($string_post) > $length) {
		$string_cut = substr($string, 0, $length);
		$endpoint = strrpos($string_cut, ' ');
		
		$string_post = $endpoint ? substr($string_cut, 0, $endpoint) : substr($string_cut, 0);
		$string_post = $string_post . $separator;
	} 
	
	return $string_post;
}

function base_path($string) {
	global $cfg_app_path;
	return "$cfg_app_path/file/$string";
}
function base_url($string) {
	global $cfg_app_url;
	return "$cfg_app_url/file/$string";
}
function base_template_url($string) {
	global $cfg_app_url, $config_site_templatefolder;
	return "$cfg_app_url/template/$config_site_templatefolder/$string";
}
?>
