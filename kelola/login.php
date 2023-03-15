<?php

$checkform = $_POST['checkform'];
$username = fiestolaundry($mysql->real_escape_string($_POST['username']), 200);
$password = fiestolaundry($mysql->real_escape_string($_POST['password']), 100);
$thisfile = $_SERVER['PHP_SELF'];
if (!isset($checkform)) {
    $membercontent .= '
        <div class="layout">
            <div class="container">
                <form class="form-signin" name="login" method="POST" action="' . $thisfile . '">
                  <input type="hidden" name="checkform" value="1">
                  <h3 class="form-signin-heading">' . _LOGINTITLE . '</h3>
                    <div class="controls input-icon">
                        <i class=" icon-user-md"></i>
                        <input type="text" class="input-block-level" placeholder="Username" name="username" value="' . $username . '" maxlength="200"/>
                    </div>
                    <div class="controls input-icon">
                        <i class=" icon-key"></i>
                        <input type="password" class="input-block-level" placeholder="Password" name="password" value="" maxlength="100" />
                    </div>
                    <input class="btn btn-danger btn-block" type="submit" name="submit" value="Login" />
                </form>
            </div>
        </div>
	';
    $thefile = implode("", file("lang/$lang/viewlogin.html"));
    $thefile = addslashes($thefile);
    $thefile = "\$r_file=\"" . $thefile . "\";";
    eval($thefile);
    print $r_file;
    exit();
}

if ($checkform == "1") {
    if (!$username || !$password) {
        $membercontent .= '
        <div class="layout">
            <div class="container">
                <form class="form-signin" name="login" method="POST" action="' . $thisfile . '">
                  <input type="hidden" name="checkform" value="1">
                  <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon-exclamation-sign"></i><strong>' . _TITLEUSERPASSEMPTY . '!</strong>
                        ' . _USERPASSEMPTY . '
                  </div>
                  <h3 class="form-signin-heading">' . _LOGINTITLE . '</h3>
                    <div class="controls input-icon">
                        <i class=" icon-user-md"></i>
                        <input type="text" class="input-block-level" placeholder="Username" name="username" value="' . $username . '" maxlength="200"/>
                    </div>
                    <div class="controls input-icon">
                        <i class=" icon-key"></i>
                        <input type="password" class="input-block-level" placeholder="Password" name="password" value="" maxlength="100" />
                    </div>
                    <input class="btn btn-danger btn-block" type="submit" name="submit" value="Login" />
                </form>
            </div>
        </div>
	';
        $thefile = implode("", file("lang/$lang/viewlogin.html"));
        $thefile = addslashes($thefile);
        $thefile = "\$r_file=\"" . $thefile . "\";";
        eval($thefile);
        print $r_file;
        exit();
    } else {
        $password = fiestopass($password);
        $sql = "SELECT userid, level, fullname FROM users WHERE username='$username' AND password='$password'";
		
        $result = $mysql->query($sql);
        if ($mysql->num_rows($result) > 0) {
            list($s_userid, $s_level, $s_fullname) = $result->fetch_row();
            $_SESSION['s_userid'] = $s_userid;
            $_SESSION['s_level'] = $s_level;
            $_SESSION['s_fullname'] = $s_fullname;
            // Start Delete Counter Lebih dari 2 bulan
            delcounter();
            // End Delete Counter Lebih dari 2 bulan
        } else {
				$membercontent .= '
				<div class="layout">
					<div class="container">
						<form class="form-signin" name="login" method="POST" action="' . $thisfile . '">
						  <input type="hidden" name="checkform" value="1">
						  <div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<i class="icon-exclamation-sign"></i><strong>' . _TITLEWRONGUSERPASS . '!</strong>
								' . _WRONGUSERPASS . '
						  </div>
						  <h3 class="form-signin-heading">' . _LOGINTITLE . '</h3>
							<div class="controls input-icon">
								<i class=" icon-user-md"></i>
								<input type="text" class="input-block-level" placeholder="Username" name="username" value="' . $username . '" maxlength="200"/>
							</div>
							<div class="controls input-icon">
								<i class=" icon-key"></i>
								<input type="password" class="input-block-level" placeholder="Password" name="password" value="" maxlength="100" />
							</div>
							<input class="btn btn-danger btn-block" type="submit" name="submit" value="Login" />
						</form>
					</div>
				</div>
				';
				$thefile = implode("", file("lang/$lang/viewlogin.html"));
				$thefile = addslashes($thefile);
				$thefile = "\$r_file=\"" . $thefile . "\";";
				eval($thefile);
				print $r_file;
				exit();
			
        }
    }
}
?>
