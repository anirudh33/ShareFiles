<?php
/*
 * Creation Log File Name - index.php 
 * Description - ShareFiles index file
 * Version - 1.0 
 * Created by - Anirudh Pandita 
 * Created on - June 22, 2013 
 * *************************************************
 */
/* Setting display errors on */

ini_set("display_errors","1");
/* Starting session  */
session_start();

/* Including all constants to be used */
require_once getcwd().'/libraries/constants.php';

/* Requiring all essential files */
require_once  SITE_PATH.'/Controller.php';
require_once  SITE_PATH.'/libraries/InitiateUser.php';
/* Method calls from views handled here */
if (isset ( $_REQUEST ['controller'] )) {
		
		if (isset ( $_REQUEST ["method"] )) {
		
			// Creating object of controller to initiate the process
			$object = new $_REQUEST ["controller"] ();
			//print $_REQUEST ["method"];die;
			if (method_exists ( $object, $_REQUEST ["method"] )) {
			
				$object->$_REQUEST ["method"] ();
				if($_REQUEST ["method"]=='fetch')
				{
					echo $_REQUEST ["method"];
				}
			}

	}
}elseif (isset($_SESSION['username'])) {
	$object = new Controller ();
	$object->showUserPanel();
}
else{
/* Showing the main view */
$object = new Controller ();

$object->showMainView();
}

?>
