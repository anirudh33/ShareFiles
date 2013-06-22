<body background="<?php echo SITE_URL?>/assets/images/share1.jpg">
<a href="index.php?controller=Controller&method=logout">log out</a>
<hr>
<br>
Your files
<br>
<?php 
foreach ($fileList as $key=>$value) {

echo $value."<br>";

}
?>
<br>
<a href="index.php?controller=Controller&method=showUploadView">Upload your files</a>