<?php

class page {

    function mymenu($type) {
        global $db;
        $menuAry = array();
        $menu = $db->getRows("CALL getCMS('" . $type . "','" . $_SESSION["lang"] . "')");
        //echo $db->lq();
        $menuAry["count"] = count($menu);
        if (is_array($menu) && count($menu) > 0) {
            $i = 0;
            foreach ($menu as $m) {
                $i++;
                $menuAry["menu"][$i] = $m;
                $menuAry["menu"][$i]["url"] = href("pages.php", "page_id=" . $m["id"]);
                $menuAry["menu"][$i]["detail"] = $this->pageDetail($m["linkname"]);
                $submenu = $db->getRows("CALL getSubCMS(" . $m["id"] . ",'" . $_SESSION["lang"] . "')");
                $menuAry["menu"][$i]["count"] = count($submenu);
                if (is_array($submenu) && count($submenu) > 0) {
                    $j = 0;
                    foreach ($submenu as $sm) {
                        $j++;
                        $menuAry["menu"][$i]["menu"][$j] = $sm;
                        $menuAry["menu"][$i]["menu"][$j]["url"] = href("pages.php", "page_id=" . $sm["id"]);
                        $menuAry["menu"][$i]["menu"][$j]["detail"] = $this->pageDetail($sm["linkname"]);
                        $subsubmenu = $db->getRows("CALL getSubCMS(" . $sm["id"] . ",'" . $_SESSION["lang"] . "')");
                        $menuAry["menu"][$i]["menu"][$j]["count"] = count($subsubmenu);
                        if (is_array($subsubmenu) && count($subsubmenu) > 0) {
                            $k = 0;
                            foreach ($subsubmenu as $ssm) {
                                $k++;
                                $menuAry["menu"][$i]["menu"][$j]["menu"][$k] = $ssm;
                                $menuAry["menu"][$i]["menu"][$j]["menu"][$k]["url"] = href("pages.php", "page_id=" . $ssm["id"]);
                                $menuAry["menu"][$i]["menu"][$j]["detail"][$k]["url"] = $this->pageDetail($ssm["linkname"]);
                            }
                        }
                    }
                }
            }
        }
        return $menuAry;
    }

    function pageDetail($pname) {
        global $db;
        $pagename = str_replace("-", " ", str_replace("~", "/", str_replace("and", "&", $pname)));
        $detail = $db->getRow("CALL pageDetail('" . $pname . "','" . $_SESSION["lang"] . "')");
        if (trim($detail["external"]) != "")
            $detail["external"] = "extra/" . $detail["external"];
        $detail["pbody"] = unPOST($detail["pbody"]);
        $detail["nonhtml"] = strip_tags(unPOST($detail["pbody"]));
        $detail["short_description"] = unPOST($detail["short_description"]);
        return $detail;
    }

    function contentHead($position) {
        global $db;
        $detailArray = array();
        $i = 0;
        $getdetail = $db->getRows("CALL getContentHead('" . $position . "','" . $_SESSION["lang"] . "')");
        if (is_array($getdetail) && count($getdetail) > 0) {
            foreach ($getdetail as $detail) {
                $i++;
                $detailArray[$i]["heading"] = unPOST($detail["heading"]);
                $detailArray[$i]["image"] = $detail["image"];
                $detailArray[$i]["name"] = $detail["name"];
                $detailArray[$i]["id"] = $detail["id"];
            }
        }
        return $detailArray;
    }

    function content($type) {
        global $db;
        $detailArray = array();
        $i = 0;
        $getdetail = $db->getRows("CALL getContent('" . $type . "','" . $_SESSION["lang"] . "')");
        if (is_array($getdetail) && count($getdetail) > 0) {
            foreach ($getdetail as $detail) {
                $i++;
                $detailArray[$i] = $detail;
                $detailArray[$i]["content"] = unPOST($detail["content"]);
                $detailArray[$i]["extra"] = $detail["extra"];
            }
        }
        return $detailArray;
    }

    function contentDetail($pid) {
        global $db;
        $detail = $db->getRow("CALL getcontentDetail('" . $pid . "','" . $_SESSION["lang"] . "')");
        $detail["content"] = unPOST($detail["content"]);
        $detail["extra"] = json_encode($detail["extra"], true);
        return $detail;
    }

}

?>