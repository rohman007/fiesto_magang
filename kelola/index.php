<?php
session_start();
header("Cache-control: private");
include ("urasi.php");
include ("fungsi.php");
include ("lang/$lang/definisi.php");
include ("../modul/member/urasi.php");
include ("../modul/member/lang/$lang/definisi.php");


if (!$_SESSION['s_userid']) { 
	include ("login.php"); 
}

$userid = $_SESSION['s_userid'];
$fullname = $_SESSION['s_fullname'];
$level = $_SESSION['s_level'];
$admin = 1;
$random = getrandomnumber();
$urlfunc = new PrettyURL();

$fullpath = "";


$parsedurl = $_GET['p'];
$parsedaction = $_GET['action'];
if ($parsedurl == "widget") {
    $posisi1 = "A";
    $posisi2 = "B";
    $posisi3 = "Z";
} else if ($parsedurl == "home") {
    $posisi1 = "A";
    $posisi2 = "X";
    $posisi3 = "Y";
}

$menu = rootmenubuilder($idpaketwebsite, $parsedurl, $parsedaction, $level);

if ($_GET['p'] == '') 
{
	if(!$config_site_hidesimplestep)
	{
//	$simpleadminmenu = simpleadminmenu($idpaketwebsite);
	}
$dashboarditem =  dasboarditem($idpaketwebsite);
}
if ($_GET['p'] == '' or $_GET['step']!='') 
{
	if(!$config_site_hidesimplestep)
	{
	//$simpleadminmenu = simpleadminmenu($idpaketwebsite);
	}
	
}


$adminwelcome .= _WELCOME . " $fullname";

if ($_GET['p'] != '') {
    $namamodul = $_GET['p'];
    $sql = "SELECT judul, searchable FROM module WHERE nama='$namamodul'";
    $result = $mysql->query($sql);
    if ($mysql->num_rows($result) > 0) {
        list($judulmodul, $searchable) = $mysql->fetch_row($result);
        $adminh1 = $judulmodul;
        if ($searchable)
            $adminsearch = searchbox($namamodul);
        if (file_exists("../modul/$namamodul/lang/$lang/definisi.php"))
            include ("../modul/$namamodul/lang/$lang/definisi.php");
        if (file_exists("../modul/$namamodul/urasi.php"))
            include ("../modul/$namamodul/urasi.php");
        if (file_exists("../modul/$namamodul/fungsi.php"))
				include ("../modul/$namamodul/fungsi.php");
		$ispermissionok = verifyaccess($level, $namamodul);
        if (!$ispermissionok) {
			pesan(_ERROR, _NORIGHT);
		} else {
			
			$isloadfromindex = TRUE;
			if (file_exists("../modul/$namamodul/istrasi.php"))
				include ("../modul/$namamodul/istrasi.php");
		}
            
    } else {
        pesan(_ERROR, _NOMODULE);
    }
} else {
    //if ($serverlocation!='off') 
    //{
  //  if (!$admincontent = @file_get_contents($iwd_news_url)) $admincontent = '';
    //}
 
	

    $script_js['home'] = <<<END
        <script type="text/javascript">
            
            /**=========================
             LEFT NAV ICON ANIMATION 
             ==============================**/
            $(function() {
                $(".left-primary-nav a").hover(function() {
                    $(this).stop().animate({
                        fontSize: "30px"
                    }, 200);
                }, function() {
                    $(this).stop().animate({
                        fontSize: "24px"
                    }, 100);
                });
            });
        </script>
        
END;
}
// if($config_site_hidetips==0)
	// {
	// $admincontent.=showtips();
	// }
/*start video*/
//videoelp();

/*
if($_GET['p']=='' AND ($_COOKIE["disable_video_awal_$domainid"]==0 OR $_COOKIE["disable_video_awal_$domainid"]=='') and $_SESSION["disable_video_awal_$domainid"]!=1)
{
$_SESSION["disable_video_awal_$domainid"]=1;
$script_js['load_first']=<<<END
<script>
function video_awal_close()
{
	check=$("#novideoawal").prop("checked");
	$.ajax(
	{
		type: "POST",
		url: "ajax.php?action=video_awal&check="+check
	});
	
}
	jQuery(document).ready(function() {
	$.fancybox("<iframe scrolling='auto' frameborder='0' height='460px' width='580px' allowfullscreen='' mozallowfullscreen='' webkitallowfullscreen='' hspace='0' vspace='0'  src='http://www.youtube.com/embed/ZgCQhYYNIzw?autoplay=1&rel=0'></iframe><p class='video_awal_option'><input type='checkbox' id='novideoawal' onclick='video_awal_close()'/> Jangan tampilkan lagi</p>",
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
}
*/
/*end video*/
	
$style_css['urasi']="
<script>
var cfg_app_url='$cfg_app_url';
var cfg_template_url='$cfg_app_url/template/$config_site_templatefolder';
</script>
";
if (count($script_js) > 0 and is_array($script_js)) {
    $script_js = join("\n", $script_js) . "\n";
}
if (count($style_css) > 0 and is_array($style_css)) {
    $style_css = join("\n", $style_css) . "\n";
}
if (count($script_js) > 0 and is_array($script_js)) {
    $script_js = join("\n", $script_js) . "\n";
}
if (count($style_css) > 0 and is_array($style_css)) {
    $style_css = join("\n", $style_css) . "\n";
}


$thefile = implode("", file("lang/$lang/viewadmincart.html"));
// if ($pakaicart) {
    // $thefile = implode("", file("lang/$lang/viewadmincart.html"));
// } else {
    // $thefile = implode("", file("lang/$lang/viewadmin.html"));
// }

$thefile = addslashes($thefile);
$thefile = "\$r_file=\"" . $thefile . "\";";
eval($thefile);
print $r_file;
exit();
?>
