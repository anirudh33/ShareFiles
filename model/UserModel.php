<?php 
//include SITE_PATH.'/libraries/DBconnect.php';
class User extends DBConnection {

	public function newRegistration($email, $password, $firstname, $lastname) 
	{
	
		$result = $this->_db->insert('user', array('email' => $email, 'password'=> sha1($password),'first_name' => $firstname,'last_name'=>$lastname));
		mkdir(SITE_PATH . "/uploads/" . $email,0777);
		return 1;
	}
	public function checkemail($email) 
	{
		$bool=0;
		$data['columns']	= array('user.email', 'user.password');
		$data['tables']		= 'user';
		$data['conditions'] = array(array("email ='".$email."'"),true);
		$emailExists = $this->_db->select($data);
		//$emailExists=$this->_db->select('user', array('email' => $email));	
		while($row = $emailExists->fetch(PDO::FETCH_ASSOC)) {
			$bool=1;
		}
		if($bool==1) {
		return "true";
		}
		else {
		return "false";
		
		}
	
	}

}  