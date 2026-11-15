<?php
// define('DBHOSTNAME', 'localhost');
// define('DBUSER', 'root');
// define('DBPASSWORD', '');
// define('DBNAME', 'dailyadin.com');

define('DBHOSTNAME', 'localhost');
define('DBUSER', 'bongo_db_usr');
define('DBPASSWORD', '1£w736Hb2f]');
define('DBNAME', 'adin_bongo_db');

// Create connection
$dbConn = new mysqli(DBHOSTNAME, DBUSER, DBPASSWORD, DBNAME);

// Check connection
if ($dbConn->connect_error) {
	die("Connection failed: " . $dbConn->connect_error);
}
// Change character set to utf8
$dbConn->set_charset("utf8");
// mysqli_set_charset($dbConn,"utf8");

//Collecting the last iamge folder name
$folderSql = "SELECT * FROM com_monthly_image_folder ORDER BY FolderID DESC LIMIT 1";
$resultImgFolder=$dbConn->query($folderSql);
$rsImgFolder=$resultImgFolder->fetch_assoc();
$sImgDir=$rsImgFolder["FolderName"];
$sImgGallery=$rsImgFolder["FolderName"];
$resultImgFolder->free();

?>
