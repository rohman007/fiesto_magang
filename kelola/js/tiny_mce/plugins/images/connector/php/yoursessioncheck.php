<?php
/**
 * User session check, for registered users
 * 
 * If you don't care about access,
 * please remove or comment following code
 * 
 */

if(!isset( $_SESSION['xwdshop_member_userid'] )) {
	echo 'Access denied, check file '.basename(__FILE__);
	exit();
}

?>