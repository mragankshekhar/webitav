<?php

class login_signup extends secure {

    function login($array) {
        global $db;
        $userid = $array["username"];
        $password = $this->password($array["pass"]);
        if ($userid == "") {
            $status = array("status" => "error", "msg" => "Please enter Username ");
        }/* elseif (!filter_var($userid, FILTER_VALIDATE_EMAIL)) {
          $status=array("status"=>"error","msg"=>"Please enter valid email id eg. example@domainname.com");
          } */ elseif ($password == "") {
            $status = array("status" => "error", "msg" => $language['please_enter'] . "&nbsp;" . $language['pass']);
        } else {
            $sqlArray = array($userid, $userid, $userid, $password);
            $logdata = $db->getRow("select * from " . SITE_USER . " where (email=? or username=? or mobile=?) and pass=?", $sqlArray);
            if (is_array($logdata) && count($logdata) > 0) {
                if ($logdata["status"] == "1" || $logdata["status"] == "0") {
                    $_SESSION["user"]["uid"] = $logdata["id"];
                    $_SESSION["user"]["uname"] = $logdata["username"];
                    $_SESSION["user"]["email"] = $logdata["email"];
                    $_SESSION["user"]["atype"] = $logdata["atype"];
					$_SESSION["user"]["name"] = $logdata["fullname"];
					$_SESSION["user"]["mobile"] = $logdata["mobile"];
                    if (isset($array["rememberme"]) && $array["rememberme"] == 1) {
                        $time = time() + (24 * 3600 * 365);
                        setcookie("login", $userid, $time);
                        setcookie("password", $password, $time);
                    } else {
                        $date_of_expiry = time() - 60;
                        setcookie("login", $userid, $date_of_expiry);
                        setcookie("password", $password, $date_of_expiry);
                    }
                    $sqlArray = array($_SESSION["user"]["uid"]);
                    $db->updateAry(SITE_USER, array("is_online" => 1), "id=?", $sqlArray);
                    $url = URL_ROOT;
                    if (trim($array["redirect"]) != "") {
                        $url = URL_ROOT . "dashboard.php";
                    }
                    $status = array("status" => "success", "msg" => "Login successfully", "url" => $url, "type" => "url");
                } elseif ($logdata["status"] == "2") {
                    $status = array("status" => "error", "msg" => "You are disapproved by admin");
                } else {
                    $status = array("status" => "error", "msg" => "some error " . $db->em() . " status - " . $logdata["status"]);
                }
            } else {
                $status = array("status" => "error", "msg" => "Invalid Userid or password ");
            }
        }
        return $status;
    }

    function signup($POST) {
        global $db, $language;
        $checkEmail = $db->getVal("select id from " . SITE_USER . " where email='" . $POST["email"] . "'");
        $checkMobile = $db->getVal("select id from " . SITE_USER . " where mobile='" . $POST["mobile"] . "'");
        //$checkUsername = $db->getVal("select id from " . SITE_USER . " where username='" . $POST["username"] . "'");

        if ($checkEmail != "") {
            $status = array("status" => "error", "msg" => $language['email_already_exist']);
        } elseif ($checkMobile != "") {
            $status = array("status" => "error", "msg" => $language['mobile_already_exist']);
        } elseif (!isset($POST["password"]) || trim($POST["password"]) == "") {
            $status = array("status" => "error", "msg" => $language['please_enter'] . "&nbsp;" . $language['pass']);
        } elseif ($POST["password"] != $POST["cpassword"]) {
            $status = array("status" => "error", "msg" => $language['pass_mis_metch']);
        } else {
            // return array("status"=>"error","msg"=>$POST["name"]);exit;
            $vCode = md5(microtime());
            $role = $db->getVal("select id from " . ROLL . " where status=1 limit 0,1");
            $username = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(" ", "", $POST["name"]));
            if (isset($POST["username"]) && $POST["username"] != "") {
                $username = $POST["username"];
            }
            $userArray = array($username);
            $dbCheckUser = $db->getVal("select id from " . SITE_USER . " where username=?", $userArray);
            if ($dbCheckUser > 0) {
                $username = $username . ($dbCheckUser + 1);
            }
            /* $latlang=latlng($POST['zip']); */
            $avatar = "man.png";
            //if($POST["gender"]=="Female")$avatar="woman.png";
            $aryData = array(
                "username" => $username,
                "fullname" => $POST["name"],
                "mobile" => $POST["mobile"],
                "pass" => $this->password($POST["password"]),
                "email" => $POST["email"],
                "age" => $POST["age"],
                "vcode" => $vCode,
                "role" => $role,
                "avatar" => $avatar,
                "is_online" => 1,
                "status" => 0
            );
            if ($_COOKIE['regId'] != "") {
                $aryData["regId"] = $_COOKIE['regId'];
            }
            if (isset($POST["calltype"]) && $POST["calltype"] != "") {
                $typeOfSocial = $POST["calltype"];
                $aryData[$typeOfSocial] = $POST[$typeOfSocial];
            }
            //return $aryData;
            $ins = $db->insertAry(SITE_USER, $aryData);

            //	return array("status"=>"error","msg"=>$db->em());exit;

            if ($ins != "") {
                // notification("New User has been Register", "users", $ins);
                $sms = "Dear " . $POST["fname"] . " you have successfully registered with " . $LinksDetails["site_name"] . ". Your Login Detail are: ID:" . $POST["email"] . " and Pass: " . $password;
                //mysms($POST["mobile"], $sms, $Stat);
                $aryEmail = array("[NAME]" => $POST["name"],
                    "[SITENAME]" => $LinksDetails["site_name"],
                    "[LOGIN]" => $POST["email"],
                    "[PASSWORD]" => ($POST["password"]),
                    "[LINK]" => $url,
                    "[FOOTER]" => $footer,
                    "[VERIFYLINK]" => $verifyurl);
                //mymail($LinksDetails['mail_sender_email'], $POST["email"], $subject, $body, "REGISTRATION", $aryEmail);
                $_SESSION["user"]["uid"] = $ins;
                $_SESSION["user"]["uname"] = $POST["username"];
                $_SESSION["user"]["email"] = $POST["email"];
                $_SESSION["user"]["role"] = $role;
                // $ua = getBrowser();
                $dataArray = array(1, 1);
                $defaultPlan = $db->getVal("select id from " . MEMBERSHIPS . " where is_default=? and status=?", $dataArray);
                $pac = $this->savePackage($defaultPlan, $ins);
                //return $pac;
                $url = URL_ROOT;
                if (trim($array["redirect"]) != "") {
                    $url = base64_decode($array["redirect"]);
                }
                $yourbrowser = $ua['name'] . " " . $ua['version'];
                $historyData = array('login_ip' => $_SERVER['REMOTE_ADDR'],
                    'login_browser' => $yourbrowser,
                    'userid' => $ins,
                    'uname' => $username,
                    'email' => $POST["email"],
                    'ldate' => date("Y-m-d H:i:s"));
                $db->insertAry(LOGIN_HISTORY, $historyData);
                $status = array("status" => "success", "msg" => "Thank you for registration ...", "url" => $url, "type" => "url");
            } else {
                $status = array("status" => "error", "msg" => "dshfkjhsd");
            }
        }
        return $status;
    }

    function savePackage($PackageId, $id) {
        global $db;
        $dataArray = array($PackageId);
        $st = $db->getRow("select * from " . MEMBERSHIPS . " where id=?", $dataArray);
        $IncAry = array("uid" => $id,
            "mid" => $PackageId,
            "price" => $st["price"],
            "day" => $st["day"],
            "status" => 1,
            "text_message" => $st['text_message'],
            "video_message" => $st['video_message'],
            "pdf_message" => $st['pdf_message'],
            "image_message" => $st['image_message'],
            "high_security_message" => $st['high_security_message'],
            "hard_copy_message" => $st['hard_copy_message'],
            "love_gift_message" => $st['love_gift_message'],
        );
        $db->insertAry(USERMEMBERSHIP, $IncAry);
        return $db->em();
    }

    function forgot($email) {
        global $db;
        if (trim($email) == "") {
            $status = array("status" => "error", "msg" => $language['please_enter'] . "&nbsp;" . $language['email']);
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $status = array("status" => "error", "msg" => $language['please_enter'] . "&nbsp;" . $language['valid'] . "&nbsp;" . $language['email'] . "- example@domainname.com");
        } else {
            $logdata = $db->getRow("select * from " . SITE_USER . " where email='" . $email . "'");
            if (is_array($logdata) && count($logdata) > 0) {
                $vCode = md5(microtime());
                $db->updateAry(SITE_USER, array("vcode" => $vCode), "where id=" . $logdata["id"]);
                $body = "Dear " . $logdata["username"] . "<br />
				Sorry you have forgotten your password at " . $LinksDetails['site_name'] . ". <br />
				 Your Login Id is: " . $_POST["email"] . "       <br />
				To Reset your password, please '<a href='" . URL_ROOT . "resetPassword.php?code=" . $vCode . "'>click here</a><br />
				<br />
				 If you are unable to click on the link above, please paste this link in your browser window: <br />
				<a href='" . URL_ROOT . "resetPassword.php?code=" . $vCode . "'>" . URL_ROOT . "resetPassword.php?code=" . $vCode . "</a> <br />
				";
                mymail($LinksDetails["mail_sender_email"], $email, "Notification ! Sorry " . $logdata["username"] . " for  losing your password ", $body, "REGISTRATION");
                $status = array("status" => "success", "msg" => "Reset Password link has been sent to your email id");
            } else {
                $status = array("status" => "error", "msg" => "Invalid email Please try again");
            }
        }
        return $status;
    }

    function resetPassword($POST) {
        global $db;
        //$status=array("status"=>"error","msg"=>"Your reset Password link has been expired");exit;
        if (!isset($POST["pass"]) || trim($POST["pass"]) == "") {
            $status = array("status" => "error", "msg" => $language['please_enter'] . "&nbsp;" . $language['pass']);
        } elseif (!preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $POST["pass"])) {
            $status = array("status" => "error", "msg" => $language['pass_error']);
        } elseif (!isset($POST["cpass"]) || trim($POST["cpass"]) == "") {
            $status = array("status" => "error", "msg" => $language['please_enter'] . "&nbsp;" . $language['conform'] . "&nbsp;" . $language['pass']);
        } elseif ($POST["cpass"] != $POST["pass"]) {
            $status = array("status" => "error", "msg" => "Confirm password must equeal to New Password");
        } else {
            $id = $db->getRow("select id,username,email from " . SITE_USER . " where vcode='" . $POST["code"] . "'");
            if (is_array($id) && count($id) > 0) {
                $logdata = $db->updateAry(SITE_USER, array("pass" => md5($POST["pass"]), "vcode" => ""), "where id=" . $id["id"]);
                $body = "
					Dear " . $id["username"] . "<br />
					<br />
					Are You Forgot password on favorchat.<br />
					<br />
					Your can Login to our site with  Login ID: <strong>" . $id["email"] . "</strong><br />
					<br />
					<br />
					<br />
					Your Password has been successfully reset<br />
					<br />
					<br />
					<br />
					";
                mymail($LinksDetails["mail_sender_email"], $id["email"], "Notification ! password  has been reset successfully on " . $LinksDetails["site_name"], $body, "REGISTRATION");
                $status = array("status" => "success", "msg" => "Your Password has been successfully reset", "url" => URL_ROOT);
            } else {
                $status = array("status" => "error", "msg" => "Your reset Password link has been expired"); //.$db->getLastQuery();
            }
        }
        return $status;
    }

    function subscriber($array) {
        global $db;
        $checkEmail = $db->getVal("select email from " . SUBSCRIBER . " where email='" . $array["email"] . "'");
        if ($checkEmail != "") {
            $status = array("status" => "error", "msg" => "This Email Already Exist");
        } elseif ($array['email'] == "") {
            $status = array("status" => "error", "msg" => "Please enter Email");
        } else {
            $url = URL_ROOT;
            $db->insertAry(SUBSCRIBER, array('email' => $array['email'], 'status' => 1));
            $status = array("status" => "success", "url" => $url);
        }
        return $status;
    }

    function getOTP($id) {
        global $db, $LinksDetails;
        $otp = rand(1000, 9999);
        $sms = "OTP for " . $LinksDetails["site_name"] . " is '$otp'. Please do ot share with any one.";
        $data = mysms($id, $sms, 0);
        $_SESSION["otp"] = $otp;
        return $status = array("status" => "success", "msg" => "OTP for " . $LinksDetails["site_name"] . " is sent to you number please check", "datas" => $data);
    }

    function checkOTP($otp) {
        if ($_SESSION["otp"] == $otp) {
            return $status = array("status" => "success", "msg" => "Processing Please wait...");
        } else {
            return $status = array("status" => "error", "msg" => "please check you mobile again for valid OTP " . $_SESSION["otp"]);
        }
    }

    function checkExist($POST) {
        global $db;
        //return $POST;
        $data = array();
        $dataArray = array($POST["val"]);
        $checkid = $db->getVal("select id from " . SITE_USER . " where " . $POST["field"] . "=?", $dataArray);
        //echo $db->lq() . "--" . $checkid;
        if ($checkid > 0) {
            $data["val"] = 0;
            $data["msg"] = ucwords($POST["field"]) . "already exist please try again";
        } else {
            $data["val"] = 1;
            $data["msg"] = "Congo! " . ucwords($POST["field"]) . " not exist.";
        }
        return $data;
    }

}

?>