<?php
function post_to_url($url, $data){
	$fields = '';
	foreach($data as $key => $value) { 
	  $fields .= $key . '=' . $value . '&'; 
	}
	rtrim($fields, '&');
	$post = curl_init();
	curl_setopt($post, CURLOPT_URL, $url);
	curl_setopt($post, CURLOPT_POST, count($data));
	curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($post);
	curl_close($post);
	return $result;
}
function fcmNotification($body,$icon,$url){
	global $db,$LinksDetails;
	$iList=array();

		$all=$db->getRows("select regId from ".PUSH_NOTIFICATION." where status=1");
		//print_r($all);exit;
		if(is_array($all) && count($all)>0){

			foreach($all as $a)

				$iList[]=$a["regId"];

		}

		$url = 'https://fcm.googleapis.com/fcm/send';

		$fields = array (

				'registration_ids' => $iList,

				'data' => array (

						"title" => "Whispersinthecorridors News",

						"body" => $body,

						"message" => $body,

						"icon" => $icon,

						"click_action" => 	$url

				)

		);

		$fields = json_encode ( $fields );

		$headers = array (

				'Authorization: key=' . "AAAATEq4VaE:APA91bHs5F1sHTJkRBjZ3FbOQyhblopII4es3F4XPpXKu_ARr3gRsjVxLBobMZPZLvnrh2DcqlJHR_9sHa7oYAXBgCXNPGwZG5KHXs_xSlIZ9GkPFBL328Qj_3uy9TnZ9O2bx4IC3uxd",

				'Content-Type: application/json'

		);



		$ch = curl_init ();

		curl_setopt ( $ch, CURLOPT_URL, $url );

		curl_setopt ( $ch, CURLOPT_POST, true );

		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );

		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		$result = curl_exec ( $ch );

		curl_close ( $ch );
		
		return count($iList)."---".$result;
}
function viewVisit($no,$cont){
	$myn=array();$unom=array();
	$no = sprintf("%0".$cont."d", $no);
	$unom=str_split($no);
	foreach($unom as $n)
		$myn[]="<span class='num num".$n."'>".$n."</span>";
	//print_r($unom);
	echo implode("",$myn);
}
function showAds($file,$type,$link,$action,$id,$align="center",$width="600",$height="80"){
	if(file_exists("uploads/ads/".$file)){
		$data="";
		$marign="";
		if($align=="center"){
			$marign="margin: 5px auto;";
		}
		$link=URL_ROOT."medium.php?url=".base64_encode($link)."&id=".$id;
		if($type==1){
			$data='<a href="'.$link.'" target="_blank"><img class="img-responsive" src="'.URL_ROOT.'uploads/ads/'.$file.'" border="0" style="'.$marign.'"></a>';

		}else{
			$data='<object width="'.$width.'" style="display:block; margin:0 auto;" height="'.$height.'" data="uploads/ads/'.$file.'"></object>';
		}
		return $data;
	}
	
}

function timer_function(){
$refresh=1000; // Refresh rate in milli seconds
$mytime=setTimeout('AjaxFunction();',refresh);
}



function replaceURL($url) {
    $web = str_replace("www.", "", $_SERVER['HTTP_HOST']);
    $url = str_replace($_SERVER['HTTP_HOST'], $web, $url);
    return $url;
}

function addFile($file) {
    global $mss, $LinksDetails;
    include_once(PATH_ROOT . DS . 'templates' . DS . $mss->theme() . DS . $file);
}

function redirect($url = NULL) {
    if (is_null($url))
        $url = curPageURL();
    if (headers_sent()) {
        echo "<script>window.location='" . $url . "'</script>";
    } else {
        header("Location:" . $url);
    }
    exit;
}

function chkHeader() {
    if (strpos($_SERVER['HTTP_REFERER'], URL_ROOT) == 0)
        return true;
    return false;
}

function loc($page) {
    header("location:$page");
}

function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] === "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function getQueryString($aryQueryStr) {
    $aryMatch = array();
    foreach ($aryQueryStr as $opt => $val) {
        $aryMatch[] = $opt . '=' . urlencode($val);
    }
    return '?' . implode('&', $aryMatch);
}

function selected($needle, $haystack) {
    if (is_array($haystack) && in_array($needle, $haystack)) {
        return 'selected="selected"';
    } elseif (!is_array($haystack) && $needle === $haystack) {
        return 'selected="selected"';
    } else {
        return '';
    }
}

function checked($needle, $haystack) {
    if (is_array($haystack) && in_array($needle, $haystack)) {
        return 'checked="checked"';
    } elseif (!is_array($haystack) && $needle === $haystack) {
        return 'checked="checked"';
    } else {
        return '';
    }
}

function isValidDate($val) {
    if (preg_match(REGX_DATE, $val)) {
        list($year, $month, $date) = explode("-", $val);
        if (checkdate($month, $date, $year))
            return true;
    }
    return false;
}

function getFileSize($path) {
    if (is_array($path) && count($path) > 0) {
        //if(!file_exists($path)) return 0;
        //if(is_file($path)) return filesize($path);
        $ret = 0;
        foreach ($path as $file)
            $ret += getFileSize($file);
        return $ret;
    } else {
        if (!file_exists($path))
            return 0;
        if (is_file($path))
            return filesize($path);
    }
}

function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {//check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {//to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function fetchSetting() {
    $aryReturn = array();
    $strSetting = '';
    global $db;
    $arySetData = $db->getRows("select * from " . SETTINGS);
    if (is_array($arySetData) && count($arySetData) > 0) {
        foreach ($arySetData as $iSetData) {
            $aryReturn[$iSetData['field']] = unPOST($iSetData['en']);
        }
    }
    return $aryReturn;
}

function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . DS . $file))
                @unlink($dirname . DS . $file);
            else
                delete_directory($dirname . DS . $file);
        }
    }
    closedir($dir_handle);
    @rmdir($dirname);
    return true;
}

function listDirs($where) {
    $directoryarr = array();
    $itemHandler = opendir($where);
    $i = 0;
    while (($item = readdir($itemHandler)) !== false) {
        if ($item == "." || $item == "..") {

        } else {
            $directoryarr[] = $item;
        }
    }
    return($directoryarr);
}

function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function randomFix($length) {
    $random = "";

    srand((double) microtime() * 1000000);

    $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
    $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
    $data .= "0FGH45OP89";

    for ($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
}

function href($page, $param = "") {
    global $db;
    $sef = "1";
    if ($sef == "1") {
        $x = explode("&", $param);
        $var = array();
        if (is_array($x) && count($x) > 0) {
            foreach ($x as $k1 => $v1) {
                $x2 = explode("=", $v1);
                if (is_array($x2) && count($x2) > 0) {
                    $subpar = $x2[0];
                    $var[$subpar] = $x2[1];
                }
            }
        }
        switch ($page) {
            case 'page.php' : {
                    if (isset($var['page_id'])) {
                        $name = $db->getVal("SELECT linkname FROM " . CMS . " WHERE id='" . $var['page_id'] . "'");
                        $pagename = str_replace(" ", "-", str_replace("/", "~", str_replace("&", "and", str_replace("?","^",$name))));
                        return URL_ROOT . $pagename . ".html";
                    }
                    break;
                }


            case 'feedback.php' : {
                  if (isset($var['id'])) {
                        $name = $db->getVal("SELECT name FROM " . CONTENT . " WHERE id='" . $var['id'] . "'");
						$rep_from=array("(",")"," ","/","&","?");
						$rep_to=array("+","+","+","~","and","ques");
                        $pagename = str_replace($rep_from,$rep_to, unPOST($name));
                        return URL_ROOT ."Feedback/". $var['id']."-".$pagename . ".html";
                    }
                    break;
                }
				
		   /* case 'mediacontent.php' : {
                  if (isset($var['id'])) {
                        $linkname = $db->getVal("SELECT linkname FROM " . CMS . " WHERE id='" . $var['id'] . "'");
						$linkname_sec = $db->getVal("SELECT linkname_sec FROM " . CMS . " WHERE id='" . $var['id'] . "'");
						$rep_from=array("(",")"," ","/","&","?");
						$rep_to=array("+","+","+","~","and","ques");
						
                        $linkname = str_replace($rep_from,$rep_to, unPOST($linkname));
                        return URL_ROOT ."linkname/". $var['id']."-".$linkname . ".html";
						$linkname_sec = str_replace($rep_from,$rep_to, unPOST($linkname_sec));
                        return URL_ROOT ."linkname_sec/". $var['id']."-".$linkname_sec . ".html";
                    }
                    break;
                }		*/
			
            case 'image.php' : {
                    //return IMG.URL_ROOT."uploads/".$var['path']."/".$var['file_name']."&w=".$var["w"]."&h=".$var["h"];
                    if ($var["w"] != "" && $var["h"] != "")
                        return URL_ROOT . "uploads/" . $var['path'] . "/" . $var["w"] . "/" . $var["h"] . "/" . $var['file_name'];
                    elseif ($var["w"] == "" && $var["h"] != "")
                        return URL_ROOT . "uploads/" . $var['path'] . "/h/" . $var["h"] . "/" . $var['file_name'];
                    elseif ($var["h"] == "" && $var["w"] != "")
                        return URL_ROOT . "uploads/" . $var['path'] . "/w/" . $var["w"] . "/" . $var['file_name'];
                    else
                        return URL_ROOT . "uploads/" . $var['path'] . "/" . $var['file_name'];
                }
            case 'signup.php' : {
                    return URL_ROOT . "signup.html";
                    break;
                }

            default: {
                    if ($param == "") {
                        return URL_ROOT . $page;
                    } else {
                        return URL_ROOT . $page . '?' . $param;
                    }
                }
        }
    } else {
        if ($param == "") {
            return URL_ROOT . $page;
        } else {
            return URL_ROOT . '/' . $page . '?' . $param;
        }
    }
}

function addvisit() {
    global $db;
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $findme = 'ms-administration';
    //$pos = ($url, $findme);
    if (strpos($url, $findme) == false) {
        $qry = 'CREATE TABLE IF NOT EXISTS `' . NO_VISIT . '` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `ndate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `ip` varchar(150) NOT NULL,
		  `page` varchar(150) NOT NULL,
		  `no` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;';
        $db->query($qry);


        $v_id = $db->getVal("select no from " . NO_VISIT . " where ip='" . $_SERVER['REMOTE_ADDR'] . "' and page='" . $url . "'");
        if (strlen($url) < 100) {
            if ($v_id == '') {
                $VisitData = array("ip" => $_SERVER['REMOTE_ADDR'],
                    "page" => $url,
                    "no" => 1
                );
                $flgVisi = $db->insertAry(NO_VISIT, $VisitData);
                //print_r($VisitData);
            } else {
                $VisitData = array("no" => ($v_id + 1));
                $flgVisi = $db->updateAry(NO_VISIT, $VisitData, "where ip='" . $_SERVER['REMOTE_ADDR'] . "' and page='" . $url . "'");
            }
        }
    }
}

function countVisits() {
    global $db;
    $cnt = $db->getRows("select no from " . NO_VISIT);
    $all = 0;
    if (is_array($cnt) && count($cnt) > 0) {
        foreach ($cnt as $ct) {
            $all = $all + $ct["no"];
        }
    }
    return $all;
}

function mysms($m, $msg, $type = "0", $page = "") {
    if ($page == "")
        $page = curPageURL();
    global $db, $LinksDetails;
    if ((strlen($m) == 10 || strlen($m) == 12) && is_numeric($m)) {
        $PhNo = $m;
        $Text = urlencode($msg);
        /* $url = "http://msg.cyberinformatic.in/api/sendhttp.php?authkey=83218ASGWY7cT56e7f4c3&mobiles=$PhNo&message=$Text&sender=JSSGIW&route=4&country=0";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $ret = curl_exec($ch);
          curl_close($ch);
         */
        $db->insertAry(SMS_HISTORY, array("smsto" => $PhNo, "sms_detail" => $Text, "smsfrom" => "letter", "status" => $ret));
        return $ret . "<br />" . $url . " " . $db->em();
    } else
        return "invalid mobile no";
}

function mymail($from, $to, $subject, $body, $type = "Common", $ary = array(), $attach = "0") {
    global $db, $LinksDetails, $lang;
    $Stat = "0";
    $sendFrom = "";
    $st = $db->getVal("select status from " . UNSUBSCRIBE . " where mto='" . $to . "' and type='" . $type . "'");
    if ($st == 1) {
        return "You are unsubscribe for " . $type . " section";
    } else {
        $UNSUBSCRIBE = URL_ROOT . 'verification.php?to=' . base64_encode($to) . '&unsubscribe=yes&type=' . $type;
        $msgs = $db->getRow("select * from " . MAILMSG . " where msg_for='" . $type . "'");
        if ($msgs['subject'] != "")
            $subject = $msgs['subject'];
        if (is_array($ary) && count($ary) > 0) {
            foreach ($ary as $key => $val) {
                $arr_tpl_vars[] = $key;
                $arr_tpl_data[] = $val;
            }
        } else {
            $arr_tpl_vars = array('[MESSAGE]');
            $arr_tpl_data = array($body);
        }
        $FormTemp = $db->getRows("select * from " . SETTINGS . " where field in('admin_email','site_name','logo','twitter_url', 'facebook_url', 'google_url', 'linked_url', 'pinterest_url', 'instagram_url','site_name')");
        if (!is_null($FormTemp) && is_array($FormTemp) && count($FormTemp) > 0) {
            foreach ($FormTemp as $iFormTemp) {
                $arr_tpl_vars[] = '[' . $iFormTemp['field'] . ']';
                $arr_tpl_data[] = unPOST($iFormTemp[$lang]);
            }
        }
        $allfollow = '<a href="' . $LinksDetails["twitter_url"] . '"><img src="https://cdn.eflyermaker.com/Framework/microsites/eflyermaker/images/follow/twitter.png"></a><a href="' . $LinksDetails["facebook_url"] . '"><img src="https://cdn.eflyermaker.com/Framework/microsites/eflyermaker/images/follow/facebook.png"></a><a href="' . $LinksDetails["google_url"] . '"><img src="https://cdn.eflyermaker.com/Framework/microsites/eflyermaker/images/follow/googleplus.png"></a><a href="' . $LinksDetails["instagram_url"] . '"><img src="https://cdn.eflyermaker.com/Framework/microsites/eflyermaker/images/follow/instagram.png"></a><a href="' . $LinksDetails["pinterest_url"] . '"><img src="https://cdn.eflyermaker.com/Framework/microsites/eflyermaker/images/follow/pinterest.png"></a>';
        array_push($arr_tpl_vars, '[LOGIN]', '[SITE]', '[DATE]', '[SUBJECT]', '[YEAR]', '[UNSUBSCRIBE]', '[ALL_FOLLOW]');

        array_push($arr_tpl_data, URL_ROOT . "login.html", URL_ROOT, date('d/m/Y'), $subject, date("Y"), $UNSUBSCRIBE, $allfollow);

        $e_msg = str_replace($arr_tpl_vars, $arr_tpl_data, unPOST($msgs["msg"]));
        $e_sub = str_replace($arr_tpl_vars, $arr_tpl_data, $subject);

        if ($LinksDetails["email_from"] == "server") {

            $mail = new PHPMailer();
            $mail->IsSMTP();
            //$mail->SMTPDebug = 1;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = MAIL_SMTPSECURE;
            $mail->Host = MAIL_HOST;
            $mail->Port = MAIL_PORT; // we changed this from 486
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            // Build the message
            $mail->Subject = $e_sub;
            $mail->AltBody = 'This is a plain-text message body';
            if ($attach != "0") {
                $filename = basename($attach);
                $mail->addAttachment(PATH_ROOT . $attach, $filename);
            }
            //Set the from/to
            $mail->setFrom(MAIL_SENDER_EMAIL, MAIL_SENDER_NAME);
            $mail->addAddress($to, $to);
            $mail->Body = unPOST($e_msg);
            //send the message, check for errors
            $Stat = $mail->Send();
            $sendFrom = "server";
        } else {
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <test@fixndeal.com>' . "\r\n";
            /* $headers  = 'MIME-Version: 1.0' . "\r\n";
              $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
              $headers .= 'From: '.MAIL_SENDER_NAME.'<'.MAIL_SENDER_EMAIL.'>' . "\r\n"; */
            if (@mail($to, $e_sub, $e_msg, $headers))
                $Stat = "1";
            $sendFrom = "local";
        }
        $added_by = $_SESSION["user"]["uid"];
        if ($added_by == "")
            $added_by = $_SESSION['admin']['uid'];
        $db->insertAry(MAIL_HISTORY, array("ufrom" => MAIL_SENDER_EMAIL, "uto" => $to, "usubject" => $e_sub, "ubody" => $e_msg, "utype" => $sendFrom, "status" => $Stat, "added_by" => $added_by, "page" => curPageURL()));

        return $lang . "-" . $Stat . "-" . $sendFrom . "<hr/>" . MAIL_SENDER_NAME . "-" . MAIL_SENDER_EMAIL . "<br/>" . $to . "<br/>" . $e_sub . "<br/>" . $e_msg . "<br/>" . $db->em() . "<br />Error=>" . $mail->ErrorInfo;
    }
}

function notification($notice, $type, $to, $url, $uid = '', $status = '1') {
    global $db;
    if ($uid == "")
        $uid = $_SESSION["admin"]["uid"];
    $incData = array("ndate" => date("Y-m-d H:i:s"),
        "notice" => $notice,
        "type" => $type,
        "from_id" => $uid,
        "to_id" => $to,
        "status" => $status,
        "url" => $url
    );
    $res = $db->insertAry(NOTIFICATION, $incData);
    return $res;
}

function getDirectorySize($path) {
    $totalsize = 0;
    $totalcount = 0;
    $dircount = 0;
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            $nextpath = $path . '/' . $file;
            if ($file != '.' && $file != '..' && !is_link($nextpath)) {
                if (is_dir($nextpath)) {
                    $dircount++;
                    $result = getDirectorySize($nextpath);
                    $totalsize += $result['size'];
                    $totalcount += $result['count'];
                    $dircount += $result['dircount'];
                } elseif (is_file($nextpath)) {
                    $totalsize += filesize($nextpath);
                    $totalcount++;
                }
            }
        }
    }
    closedir($handle);
    $total['size'] = $totalsize;
    $total['count'] = $totalcount;
    $total['dircount'] = $dircount;
    return $total;
}

function sizeFormat($size) {
    $size = round($size / (1024 * 1024), 1);
    return $size;
}

//to find the time in diff format eg. 5 min ago
function ago($ptime, $tdate = "") {
    if ($tdate == "")
        $tdate = time();
    else
        $tdate = strtotime($tdate);
    $etime = $tdate - strtotime($ptime);
    if ($etime < 1) {
        return 'Just now';
    }
    $a = array(12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}

function diff($ptime, $tdate = "") {
    if ($tdate == "")
        $tdate = time();
    else
        $tdate = strtotime($tdate);
    $etime = $tdate - strtotime($ptime);
    if ($etime < 1) {
        return 'Instent';
    }
    $a = array(12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ';
        }
    }
}

function website($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function checkWebsite($url, $id) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        return href("page.php", "page_id=" . $id);
    } else {
        return $url;
    }
}

function randid() {
    md5(microtime());
}

function get_currency($from_Currency, $to_Currency, $amount) {
    $amount = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";
    $ch = curl_init();
    $timeout = 0;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('bld>', $rawdata);
    $data = explode($to_Currency, $data[1]);
    return round($data[0], 2);
}

if (!function_exists('POST')) {

    function POST($i, $trim = false) {
        if (isset($_POST[$i]))
            $i = $_POST[$i];
        if ($trim)
            $i = trim($i);
        if (!get_magic_quotes_gpc())
            $i = addslashes($i);
        $i = rtrim($i);
        $look = array('&', '#', '<', '>', '"', '\'', '(', ')', '%');
        $safe = array('&amp;', '&#35;', '&lt;', '&gt;', '&quot;', '&#39;', '&#40;', '&#41;', '&#37;');
        $i = str_replace($look, $safe, $i);
        //$i = htmlentities($i);
        return $i;
    }

    function unPOST($i) {
        global $db;
        $look = array('&', '#', '<', '>', '"', '\'', '(', ')', '%');
        $safe = array('&amp;', '&#35;', '&lt;', '&gt;', '&quot;', '&#39;', '&#40;', '&#41;', '&#37;');
        $i = str_replace($safe, $look, $i);
        $msg = $i; /* $codesToConvert=array();
          $arr_tpl_vars=array();$arr_tpl_data=array();
          $shortcodes=$db->getRows("select code,image from ".SMILEYS." where status=1");
          if(is_array($shortcodes) && count($shortcodes)>0){
          foreach($shortcodes as $code){
          if (strpos($i, $code["code"]) != false) {
          $arr_tpl_vars[]=$code["code"];
          $arr_tpl_data[]="<img src='".URL_ROOT."uploads/smileys/".$code["image"]."' height='32px' />";
          }
          }
          $msg = str_replace($arr_tpl_vars, $arr_tpl_data, $i);
          } */
        return stripslashes($msg); //.$db->getErMsg();
    }

}

function checkPermission($pagename, $roll) {
    global $db;
    $cando = "n";
    $sqlRowArray = array(strtolower($pagename), $roll);
    $per = $db->getRow("select * from " . PRIVILAGES . " where page_name=? and roll_id=?", $sqlRowArray);
    if (isset($per["a"]) && $per["a"] == 1) {
        $cando = "a";
    } elseif (isset($per["r"]) && $per["r"] == 1) {
        $cando = "r";
    } elseif (isset($per["w"]) && $per["w"] == 1) {
        $cando = "w";
    } elseif (isset($per["v"]) && $per["v"] == 1) {
        $cando = "v";
    } elseif (isset($per["n"]) && $per["n"] == 1) {
        $cando = "n";
    }
    return $cando;
}

function getPagination($count) {
    $paginationCount = floor($count / PAGE_PER_NO);
    $paginationModCount = $count % PAGE_PER_NO;
    if (!empty($paginationModCount)) {
        $paginationCount++;
    }
    return $paginationCount;
}

function latlng($url) {
    $prepAddr = str_replace(' ', '+', $url);

    $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');

    $output = json_decode($geocode);

    $myLat = $output->results[0]->geometry->location->lat;
    $myLng = $output->results[0]->geometry->location->lng;

    $address = $output->results[0]->address_components[1]->long_name . ", " . $output->results[0]->address_components[2]->long_name . ", " . $output->results[0]->address_components[3]->long_name;

    return(array("lat" => $myLat, "lng" => $myLng, "address" => $address));
}

function geoCheckIP($ip) {
    //check, if the provided ip is valid
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        throw new InvalidArgumentException("IP is not valid");
    }
    $response = @file_get_contents('http://www.netip.de/search?query=' . $ip);
    if (empty($response)) {
        throw new InvalidArgumentException("Error contacting Geo-IP-Server");
    }

    //Array containing all regex-patterns necessary to extract ip-geoinfo from page

    $patterns = array();
    $patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
    $patterns["country"] = '#Country: (.*?)&nbsp;#i';
    $patterns["state"] = '#State/Region: (.*?)<br#i';
    $patterns["town"] = '#City: (.*?)<br#i';
    //Array where results will be stored
    $ipInfo = array();
    //check response from ipserver for above patterns
    foreach ($patterns as $key => $pattern) {
        //store the result in array
        $ipInfo[$key] = preg_match($pattern, $response, $value) && !empty($value[1]) ? $value[1] : 'not found';
    }
    /* I've included the substr function for Country to exclude the abbreviation (UK, US, etc..)
      To use the country abbreviation, simply modify the substr statement to:
      substr($ipInfo["country"], 0, 3)
     */
    $ipdata = $ipInfo["town"] . ", " . $ipInfo["state"] . ", " . substr($ipInfo["country"], 4);
    return $ipdata;
}

function myDetail($what) {
    global $db;
    $st = $db->getVal("select $what from " . SITE_USER . " where id='" . $_SESSION["user"]["uid"] . "'");
    return $st;
}

function myFriendDetail($id, $what) {
    global $db;
    $st = $db->getVal("select $what from " . SITE_USER . " where id='" . $id . "'");
    return $st; //$db->getLastQuery();
}

function update_info($detail) {
    global $db;
    $Savedate = array(
        "update_by" => $_SESSION["user"]["uname"],
        "Detail" => $detail,
        "status" => 0,
        "userid" => $_SESSION["user"]["uid"]
    );
    $db->insertAry(UPDATE_INFO, $Savedate);
}

function findage($birthDate) {
    //$birthDate = "12/17/1983";
    //explode the date to get month, day and year
    $birthDate = explode("/", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
    echo $age;
}

?>