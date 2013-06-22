<body background="<?php echo SITE_URL?>/assets/images/share1.jpg">

<?php 
	public function downloadContent($email)
	{
		$files = array ();
		$path = SITE_PATH . "/uploads/" . $email ;
		if ($handle = opendir ( $path )) {
			while ( false !== ($file = readdir ( $handle )) ) {
				if ($file != "." && $file != "..") {
					$files [] = "<a href='uploads/'" . $email . "/" . $file.">".$file."</a>";
				}
			}
			closedir ( $handle );
		}
		return $files;
	}


?>