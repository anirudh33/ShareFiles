<?php
include SITE_PATH.'/model/UserModel.php';
class Controller 
{	
	private $_shareObj;
	
	 /* Any messages to be shown to user */
	private $_message = '';
	
	/*
	 * If user login information validated set to 1 otherwise 0
	 */
	private $_authenticationStatus = 0;
	
	 /* Gets the value of variable private $_authenticationStatus */
	public function getAuthenticationStatus() {
		return $this->_authenticationStatus;
	}
	
	 /* Sets the value of variable private $_authenticationStatus */
	public function setauthenticationStatus($authenticationStatus) {
		$this->_authenticationStatus = $authenticationStatus;
	}
	
	 /* Gets the value of variable private private $_message */
	public function getMessage() {
		return $this->_message;
	}
	
	 /* Sets the value of variable private private $_message */
	public function setMessage($message) {
		$this->_message = $message;
		$this->setCustomMessage ( "ErrorMessage", $_message );
	}
	
	 /* Shows Home page */
	public function showMainView() {		
		require_once SITE_PATH."/views/MainView.php";
	}
	
	 /* Starts login procedure by fetching username, password from POST */
	public function initiateLogin() {
		$authObject = new Authenticate ();
		
		/* Validate username password */
		$authObject->validateLogin ();
		
		/* Getting rid of sql injection */
		$fieldEmail = mysql_real_escape_string ( $_POST ["fieldEmail"] );
		$fieldPassword = mysql_real_escape_string ( $_POST ["fieldPassword"] );		
		$objInitiateUser = new InitiateUser ();		
		$this->setAuthenticationStatus ( 
				$objInitiateUser->login ( $fieldEmail, $fieldPassword ) );
		
		if ($this->getAuthenticationStatus () == 1) {			
			$this->showUserPanel ();
		}
	}
	
	/*
	 * Shows respective User Panel (Admin/Teacher/Student) depending on user type logged in
	 */
	public function showUserPanel() 
	{
		$this->_shareObj=new ShareFiles();
		$fileList=$this->_shareObj->downloadContent($_SESSION["emailID"]);
		require_once SITE_PATH."/views/HomeView.php";
	}	

	/* Called when user submits the registration form */
	public function registerUser() 
	{
		//$authObject = new Authenticate ();
		//$authObject->validateRegistration ();
		$obj =new User();
		$email = $_POST ["email"];
		$userExists=$obj->checkemail($email);
		if($userExists =="true") {
			echo "user With same email already exists";
			exit;
		}
		$password = $_POST ["password"];
		$firstname = $_POST ["firstname"];
		$lastname = $_POST ["lastname"];
		
		$obj->newRegistration ($email, $password, $firstname, $lastname);
		$msg = "Registration successfull";
		header ( "Location:" . SITE_URL . "/index.php?msg=$msg" );
		return 1;
		
	}
	public function showUploadView()
	{
		require_once SITE_PATH."/views/UploadView.php";
	}
	
	public function uploadClick()
	{
		$this->_shareObj=new ShareFiles();
		$this->_shareObj->uploadContent();
	}
	/* Logs out the user by destroying session */
	public function logout() 
	{		
		session_destroy ();
		header ( "Location:" . SITE_URL . "/index.php" );
	}
	
	
}
?>