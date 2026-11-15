<?php require_once('../../../../adminAssets/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Example: File Upload</title>
</head>
<body>
<?php
// Required: anonymous function reference number as explained above.
$funcNum = $_GET['CKEditorFuncNum'] ;
// Optional: instance name (might be used to load a specific configuration file or anything else).
$CKEditor = $_GET['CKEditor'] ;
// Optional: might be used to provide localized messages.
$langCode = $_GET['langCode'] ;
// Optional: compare it with the value of `ckCsrfToken` sent in a cookie to protect your server side uploader against CSRF.
// Available since CKEditor 4.5.6.
$token = $_POST['ckCsrfToken'] ;

// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
if($_FILES['upload']['tmp_name']) {
    $img_new_name="";
    $img_tmp_name = $_FILES['upload']['tmp_name'];
    $img_full_name= $_FILES['upload']['name'];
    $img_info=pathinfo($img_full_name);
    $sDateTime=date("YmdHis");
    $img_new_name=$img_info["filename"].'-'.$sDateTime.".".$img_info["extension"];
    $BGImageNewName=$ImgDir.'/'.$newImgName;
    $doc_root = $_SERVER['DOCUMENT_ROOT'];
    // $img_name=$_FILES['upload']['name'];
    $img_destination = $doc_root . "/sonalinews.com/uploaded/" . $_FILES['upload']['name'];
    move_uploaded_file($img_tmp_name, $img_destination);
    $url = "http://localhost/sonalinews.com/uploaded/" . $_FILES['upload']['name'];
// $url = 'http://localhost/sonalinews.com/multimedia/images/common/01.jpg';
// Usually you will only assign something here if the file could not be uploaded.
// $message = 'The uploaded file has been renamed';
}
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url');</script>";
?>
</body>
</html>
