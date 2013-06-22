<?php
include SITE_PATH.'/model/UserModel.php';
class Controller 
{	
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
		require_once SITE_PATH."/views/HomeView.php";
	}
	
	 /* Change language called on clicking the desired language on mainview */
	public function setLanguageClick() 
	{
		$objInitiateUser = new InitiateUser ();
		
		$objInitiateUser->setLanguage ( $_REQUEST ["language"] );
	}
	
	 /* Method called on clicking the register button on homepage slider */
	public function registerClick() 
	{
		$this->showRegisterView ();
	}
	
	 /* Shows new user registration page */
	public function showRegisterView() 
	{
		$authObject = new Authenticate ();
		$authObject->addRegistrationCount ( 10000 );
		require_once "./views/RegistrationView.php";
	}	
	
	/**
	 * ************************************ Contact Us Mailing Function ************************************************
	 */	
	public function sendmail() 
	{
		$authObject = new Authenticate ();
		$authObject->validatecontactme ();
		if (isset ( $_POST ["message"] )) {
			$message = $_POST ["message"];
			$from = $_POST ["email"];
			$email = "ujjwal.rawlley@osscube.com";
			$name = $_POST ["name"];
			
			$mail = new PHPMailer ();
			$mail->IsSMTP (); // enable SMTP
			$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 465;
			$mail->Username = "ulearnoss@gmail.com";
			$mail->Password = "root@osscube.com";
			$mail->Subject = "Contact Me" . $from;
			$mail->Body = "My Name is =>" . $name . "<br>Message=>" . $message;
			$mail->AddAddress ( $email );
			
			if (! $mail->Send ()) {				
				$this->setCustomMessage ( "ErrorMessage", "Mail Not Sent" );
				unset ( $_POST ["message"] );
				unset ( $_POST ["email"] );
				unset ( $_POST ["name"] );
				header ( "Location:" . $_SESSION ["DOMAIN_PATH"] . "/index.php" );
			} else {
				$this->setCustomMessage ( "SuccessMessage", 
										  "Mail sent, it  will take some time" );
				header ( "Location:" . $_SESSION ["DOMAIN_PATH"] . "/index.php" );
			}
		} else {
			$this->setCustomMessage ( "SuccessMessage", 
									  "Mail sent, it  will take some time" );
			
			header ( "Location:" . $_SESSION ["DOMAIN_PATH"] . "/index.php" );			
		}
	}

	/* Checks if emailid exists in database*/
	public function ajaxEmailExists() 
	{
		if (isset ( $_POST ['email'] )) {
			$email = $_POST ['email'];
			$obj1 = new Registration ();
			$verify = $obj1->verifyEmail ( $email );
			if ($verify) {
				echo "Email already Exists";				
			}
		}
	}
	
	/* Sets toast messages to be displayed*/
	public function setCustomMessage($messageType, $message) 
	{
		if (isset ( $_SESSION ["$messageType"] )) {
			$_SESSION ["$messageType"] .= $message . "<br>";
		} else {
			$_SESSION ["$messageType"] = $message . "<br>";
		}
	}
	/*confirms activation*/
	public function confirm ()
	{
		$email = $_GET['email'];
		$pass = $_GET['passkey'];
		$obj = new Registration();
		$obj->confirmEmail($email, $pass);
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
		return 1;
		
	}
	
	/* Logs out the user by destroying session */
	public function logout() 
	{
		if (file_exists ( $_SESSION ["DOMAIN_PATH"] . "/assets/images/Views/profilepics/adminprofile" . $_SESSION ['userID'] . ".jpeg" ) or file_exists ( $_SESSION ["DOMAIN_PATH"] . "/assets/images/Views/profilepics/studentprofile" . $_SESSION ['userID'] . ".jpeg" ) or file_exists ( $_SESSION ["DOMAIN_PATH"] . "/assets/images/Views/profilepics/teacherprofile" . $_SESSION ['userID'] . ".jpeg" )) {
			unlink ( $_SESSION ["DOMAIN_PATH"] . "/assets/images/Views/profilepics/adminprofile" . $_SESSION ['userID'] . ".jpeg" );
			unlink ( $_SESSION ["DOMAIN_PATH"] . "/assets/images/Views/profilepics/studentprofile" . $_SESSION ['userID'] . ".jpeg" );
			unlink ( $_SESSION ["DOMAIN_PATH"] . "/assets/images/Views/profilepics/teacherprofile" . $_SESSION ['userID'] . ".jpeg" );
		}
		session_destroy ();
		header ( "Location:" . $_SESSION ["DOMAIN_PATH"] . "/index.php" );
	}
	/* Messages session variables unset */
	public function unsetMessages() 
	{
		unset ( $_SESSION ["SuccessMessage"] );
		unset ( $_SESSION ["ErrorMessage"] );
		unset ( $_SESSION ["NoticeMessage"] );
		echo '1';
	}
}
?>