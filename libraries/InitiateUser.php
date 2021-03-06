<?php
/*
 * *************************** Creation Log ******************************* 
 * File Name - InitiateUser.php 
 * Description - Class file for initiating login of users 
 * Version - 1.0 
 * Created by - Anirudh Pandita
 * Created on - June 22, 2013 
 * *************************************************************************** 
 */

class InitiateUser extends DBConnection {
    
	/**
	 * @var unknown
	 */
	private $_password;

	/**
	 * @var unknown
	 */
	private $_userID;
	
	/**
	 * @var unknown
	 */
	
	private $_emailID;
	
	/**
	 * @return unknown
	 */
	private function getPassword() {
		return $this->_password;
		
	}
	
	/**
	 * @param unknown $password
	 */
	private function setPassword($password) {
		$this->_password = $password;
	}
	
	
	/**
	 * @return unknown
	 */
	private function getUserID() {
		return $this->_userID;
	}
	
	/**
	 * @param unknown $userID
	 */
	private function setUserID($userID) {
		$this->_userID = $userID;
	}
	
	/**
	 * @return unknown
	 */
	private function getEmailID() {
		return $this->_emailID;
	}
	
	/**
	 * @param unknown $emailID
	 */
	private function setEmailID($emailID) {
		$this->_emailID = $emailID;
	}	
	
	/**
	 * Called from login method when user credentials in the system 
	 * have been checked
	 * 
	 * Usage: Sets userid and email id of user in session
	 * for further usage
	 */
	private function setSession() {
		$_SESSION ["userID"] = $this->getUserID ();
		
		
		$_SESSION ["emailID"] = $this->getEmailID ();
	}
	
	
	/**
	 * @author anirudh pandita
	 * @param Email id of user trying to log in: $fieldEmail
	 * @param Password of user trying to log in: $fieldPassword
	 * Called by: initiateLogin in Controller
	 * @return number 1 if entry exists by calling exists function
	 * Usage: Checks for valid login information
	 */
	public function login($fieldEmail, $fieldPassword) {
			$this->setEmailID ( $fieldEmail );
			$this->setPassword ( $fieldPassword );
			if ($this->exists ( $this->getEmailID (), $this->encryptPassword ( $this->getPassword () ) ) == 1) {                                           
				$this->setSession ();
				return 1;
			} else {
				$msg = "Login Failed username or password does not exist";				
				header ( "Location:" . SITE_URL . "/index.php?msg=$msg" );				
			}		 
	}
		
	/**
	 * @param $email entered by user
	 * @param $password entered by use after encryption
	 * @return 1 if user exists in system, 0 if doesn't
	 * Usage: Checks for user exists or not in system
	 */
	private function exists($email, $password) {
	    
		if ($this->fetchUser ( $email, $password ) == true) {
			return 1;
		} else {
			return 0;
		}
	}
	
	/**
	 * @param $email of user logging in
	 * @param $password encrypted of user logging in as 
	 * we have stored encrypted versions while registration
	 * @return number 1 if user exists else 0
	 * Usage: fetches the user if exists who is logging in
	 */
	private function fetchUser($email, $password) {
		$bool=0;
		$data['columns']	= array('user.email', 'user.password');
		$data['tables']		= 'user';
		$data['conditions'] = array(array("email ='".$email."' AND password='".$password."'"),true);
		$result = $this->_db->select($data);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			
			$bool=1;
				}
		return $bool;
	}	
	
	/**
	 * @param $table to fetch the status from like studentdetails if student logs in
	 * @param $uid User id of user loggin in
	 * @return True if status active False otherwise
	 * Usage: Fetches the status of user activated or deleted from database
	 */
	private function fetchStatus($table, $uid) {
		$this->db->Fields ( array (
				"status" 
		) );
		$this->db->Where ( array (
				"user_id" => $uid,
				"status" => "1" 
		) );
		$this->db->From ( $table );
		$bool = $this->db->select ();
		$result = $this->db->resultArray ();
		
		if (empty ( $result [0] ["status"] )) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Called from within login($fieldEmail, $fieldPassword)
	 * @param $password Received from user logging in
	 * @return encrypted password
	 * Usage: Converts the password to encrypted one
	 */
	private function encryptPassword($password) {	    
		return sha1($password);		
	}	
	
	/**
	 * @param $value: Language selected by user
	 * Usage: Sets the language as received
	 */
	public function setLanguage($languageChosen) {
		$_SESSION ["lang"] = $languageChosen;
	}	
}
?>