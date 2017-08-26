<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class user {

	function myDetail($uid,$what="*"){
		global $db;
		$conDetail=array();
		$cdetail = $db->getRow("select $what from ".SITE_USER." where id=? ",array($uid));
		
		if(is_array($cdetail) && count($cdetail)>0){
			$conDetail=$cdetail;
		}
	
		return $conDetail;
	}

}
