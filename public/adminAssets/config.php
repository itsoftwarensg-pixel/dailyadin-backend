<?php
global $dbConn;
include_once ('connection.php');
/*Basic Settings*/
$sInitialBN="দৈনিক এদিন";
$sInitialEN="Daily Adin";
$sSiteName="dailyadin.com";
$sSiteTitle="Daily Adin";

$sPath=$_SERVER["DOCUMENT_ROOT"];
// $sSiteURL="http://localhost/dailyadin.com/";
// $sCurrURL="http://localhost/dailyadin.com/".$_SERVER["REQUEST_URI"];
$sSiteURL="https://dailyadin.com/";//Web
$sCurrURL="https://dailyadin.com".$_SERVER["REQUEST_URI"];

$sPath=$_SERVER["DOCUMENT_ROOT"];
// $sProjDir="/dailyadin.com/";
$sProjDir="/";//Web

$sAdminTitle="Admin Control Panel 1.0";
$sAdminDir="bonGopaNel/";
$sAdminPath=$sPath.$sProjDir."bonGopaNel/";
$sAdminURL=$sSiteURL.$sAdminDir;
$userFilepath = $sAdminURL.'common/dist/img/usersPic/';

$iMaxImgSizeContentSM=100; //KiloByte
$iMaxImgSizeContentBG=2500; //KiloByte
$iMaxImgSizeGallery=2500; //KiloByte
$iMaxPdfSize=1024*10; //MegaBytes

//If the database and MonthlyImageFolder information is missing
// the following value will be initialize
if(!isset($sImgDir)){$sImgDir="";}
if(!isset($sImgGallery)){$sImgGallery="";}
//Local & Web
$UploadImageAll=$sPath.$sProjDir."media/imgAll/".$sImgDir."/";
$UploadImageAllSM=$sPath.$sProjDir."media/imgAll/".$sImgDir."/SM/";
$UploadCategory=$sPath.$sProjDir."media/category/";
$UploadUserImg=$sPath.$sProjDir."administrator/common/dist/img/usersPic/";
// Webstory image path
$UploadWebStoryImg=$sPath.$sProjDir."media/webStory/";
//Image Gallery
$UploadPhotoAlbum=$sPath.$sProjDir."media/PhotoAlbum/".$sImgGallery."/";
$UploadPhotoGallery=$sPath.$sProjDir."media/PhotoGallery/".$sImgGallery."/";
//Web Radio
$UploadWebRadio=$sPath.$sProjDir."media/mp3/";
//HTML files
$UploadXHTML=$sPath.$sProjDir."views/";
$UploadXHTML_Menu=$sPath.$sProjDir."views/menu/";
//RS
$UploadHTML_RSS=$sPath.$sProjDir."rss/";
$UploadSitemap=$sPath.$sProjDir."sitemaps/";
// PDF 
$UploadPdfPath=$sPath.$sProjDir."media/pdf/";
// Epaper 
$UploaEpaperPath=$sPath.$sProjDir."media/epaper/";

$dtTimeDifference=6*60*60;
$dtDateTime=gmdate("Y-m-d G:i:s", time()+$dtTimeDifference);//Local


function fEnToBnNumber($number){
switch ($number) {
  case "1":
    $bnNum="১";
    break;
  case "2":
    $bnNum="২";
    break;
  case "3":
    $bnNum="৩";
    break;
  case "4":
    $bnNum="৪";
    break;
  case "5":
    $bnNum="৫";
    break;
  case "6":
    $bnNum="৬";
    break;
  case "7":
    $bnNum="৭";
    break;
  case "8":
    $bnNum="৮";
    break;
  case "9":
    $bnNum="৯";
    break;
  case "10":
    $bnNum="১০";
    break;
  case "11":
    $bnNum="১১";
    break;
  case "12":
    $bnNum="১২";
    break;
  case "13":
    $bnNum="১৩";
    break;
  case "14":
    $bnNum="১৪";
    break;
  case "15":
    $bnNum="১৫";
    break;
  }
	return $bnNum;
}



//For Resize Image to Small Image Size
function resizeImage($resourceType,$image_width,$image_height) {
    $resizeWidth = 400;
    $resizeHeight = 240;
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
}


if(! function_exists('fFormatString')){
    function fFormatString($s){
        global $dbConn;
        $str = $dbConn->real_escape_string(trim($s));//Escapes special characters in a string for use in an SQL statement
        $str=strip_tags($str);//Strip HTML and PHP tags from a string
        $str=str_replace("'", "‍‍`", $str);
        return $str;
    }
}
//function fFormatStringHTML($s){
//	global $connEMM;
//	//Passes HTML Code in the texts
//	if(function_exists("mysqli_real_escape_string")){
//		$sStr=mysqli_real_escape_string($connEMM, trim($s));//Escapes special characters in a string for use in an SQL statement
//		$sStr=str_replace("'", "`", $sStr);
//	}else{
//		$sStr=mysqli_real_escape_string($connEMM, trim($s));//Escapes special characters in a string for use in an SQL statement
//		$sStr=str_replace("'", "`", $sStr);
//	}
//	return $sStr;
//}

function formatToMySQLDate($sDate){
	//Converts a date to MySQL data value YYYY-MM-DD
	//Workable for DatePicker or jQuery UI DatePicker
	$sDate=str_replace('/', '-', $sDate);
	$sDate=date("Y-m-d", strtotime($sDate));
	return $sDate;
}

if (! function_exists('fFormatStringHTML')) {
    function fFormatStringHTML($s){
        global $dbConn;
        $str = $dbConn->real_escape_string(trim($s));//Escapes special characters in a string for use in an SQL statement
        $str=str_replace("'", "‍‍`", $str);
        return $str;
    }
}
if(! function_exists('fImgNameFormatting')){
    function fImgNameFormatting($s){
        global $dbConn;
        $arrWords=array(":","‘","’","/","'","`","?","%","'","_",",",".");
//        $iStr=trim($str);
        $str = $dbConn->real_escape_string(trim($s));//Escapes special characters in a string for use in an SQL statement
        $str=strip_tags($str);//Strip HTML and PHP tags from a string
        $str=str_replace($arrWords, "", $str);
        $str=str_replace(" ", "-", $str);
        return $str;
    }
}

function fAuTrail($s){
	//Converts a query into text
	$s=trim(str_replace(",", " -- ", $s));
	$s=trim(str_replace(")", " ", $s));
	$s=trim(str_replace("(", " ", $s));
	$s=trim(str_replace("INSERT", " ", $s));
	$s=trim(str_replace("UPDATE", " ", $s));
	$s=trim(str_replace("DELETE", " ", $s));
	$s=trim(str_replace("SET", " ", $s));
	$s=trim(str_replace("INTO", " ", $s));
	$changeQoute=trim(str_replace("'", " ", $s));
	return $changeQoute;
}

function fFormatHead($s){
	global $connEMM;
	//Excludes HTML tags from a text
	$arrWords=array("&ldquo;", "&rdquo;", "&acute;", "<br>", "<br />", "<p>", "</p>", "</font>", "<blink>", "</blink>", "<font size=5>", "<font size=+5>", "<font size=4>", "<font size=+4>", "<font size=3>", "<font size=+3>", "<font color=black size=2>", "<strong>", "</strong>", "\r", "\n", "\r\n", "&nbsp;", "&rsquo;", "&lsquo;", "<iframe src=", "http://www.youtube.com/embed/", "</iframe>", "frameborder=", "width=", "height=", "color: #ff0000;", "<ul>", "</ul>", "<li>", "</li>", "<a href=", "</a>", "<span style=", "</span>", "color: #888888;", "<em>", "</em>", '0', '429', '276', ">", '\">', '\"');
	$sStr=trim($s);
	$sStr=mysqli_real_escape_string($connEMM, trim($s));//Escapes special characters in a string for use in an SQL statement
	$sStr=strip_tags($sStr);//Strip HTML and PHP tags from a string
	$sStr=str_replace("'", "`", $sStr);
	$sStr=str_replace($arrWords, " ", $sStr);
	$sStr=html_entity_decode($sStr);
	return $sStr;
}

function fFormatDate($sDate){
	//Converts a date to MySQL data value
	//Workable for DatePicker or jQuery UI DatePicker
	$sDate=date("Y-m-d", strtotime($sDate));
	return $sDate;
}
function fFormatDateR($sDate){
	//Reverse function of fFormatDate($sDate)
	$sDate=date("m/d/Y", strtotime($sDate));
	return $sDate;
}
function fFormatDateEn2Bn($BDDate){
	//Convert a English date to Bangla date
	$en=array("AM","PM","am","pm","Saturday","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","January","February","March","April","May","June","July","August","September","October","November","December","0","1","2","3","4","5","6","7","8","9");
	$bn=array("এএম","পিএম","এএম","পিএম","শনিবার","রবিবার","সোমবার","মঙ্গলবার","বুধবার","বৃহস্পতিবার","শুক্রবার","জানুয়ারি","ফেব্রুয়ারি","মার্চ","এপ্রিল","মে","জুন","জুলাই","আগস্ট","সেপ্টেম্বর","অক্টোবর","নভেম্বর","ডিসেম্বর","০","১","২","৩","৪","৫","৬","৭","৮","৯");
	$BDDate=str_replace($en,$bn,$BDDate);
	return $BDDate;
}
function fFormatTime($sTime){
	//Converts a time to MySQL data value
	$sTime=date("H:i:00", strtotime($sTime));
	return $sTime;
}
function fFormatTimeR($sTime){
	//Converts a time to MySQL data value
	$sTime=date("h:i A", strtotime($sTime));
	return $sTime;
}

function fFormatMonth2Number($sMonth){
	//Converts a month to number
	$en=array("January","February","March","April","May","June","July","August","September","October","November","December");
	$bn=array("01","02","03","04","05","06","07","08","09","10","11","12");
	$sMonth=str_replace($en,$bn,$sMonth);
	return $sMonth;
}

function fFormatNumber2Month($sMonth){
	//Converts a number to month
	$bn=array("01","02","03","04","05","06","07","08","09","10","11","12");
	$en=array("January","February","March","April","May","June","July","August","September","October","November","December");
	$sMonth=str_replace($bn,$en,$sMonth);
	return $sMonth;
}

function fShowYearMonth($sYearMonth){
	//Converts com_monthlyimagefoldername table to Year - Month
	$sYearMonth=str_replace('/', '-', $sYearMonth);
	$sYearMonth=date("Y - F", strtotime($sYearMonth));
	return $sYearMonth;
}

function fShowYearMonthNo($sYearMonth){
	//Converts com_monthlyimagefoldername table to Year - Month in number
	$sYearMonth=str_replace('/', '-', $sYearMonth);
	$sYearMonth=date("Y-m", strtotime($sYearMonth));
	return $sYearMonth;
}

function fShowDateNo($sYearMonth){
	//Converts date to Date in number
	$sYearMonth=str_replace('/', '-', $sYearMonth);
	$sYearMonth=date("Y-m", strtotime($sYearMonth));
	return $sYearMonth;
}

function fShowMonthNo($sYearMonth){
	//Converts date to Month in number
	$sYearMonth=str_replace('/', '-', $sYearMonth);
	$sYearMonth=date("m", strtotime($sYearMonth));
	return $sYearMonth;
}

function fShowYearNo($sYearMonth){
	//Converts date to Year in number
	$sYearMonth=str_replace('/', '-', $sYearMonth);
	$sYearMonth=date("Y", strtotime($sYearMonth));
	return $sYearMonth;
}

function fGetWords($sBrief){
	//Get first 10 words from a lot of words
	$sBrief=implode(' ', array_slice(explode(' ', $sBrief), 0, 10));
	return $sBrief;
}
function fGetWordsCount($sBrief, $iWordsNo){
	//Get first 10 words from a lot of words
	$sBrief=implode(' ', array_slice(explode(' ', $sBrief), 0, $iWordsNo));
	return $sBrief;
}
function fFormatURL($s){
	global $dbConn;
	//Excludes HTML tags from a text
	$arrWords=array(":","‘","’","/","'","`","?","&","#","$","@","%","!","'","_",",");
	$sStr=trim($s);
	$sStr=strtolower($sStr);
	$sStr=mysqli_real_escape_string($dbConn, trim($sStr));//Escapes special characters in a string for use in an SQL statement
	$sStr=strip_tags($sStr);//Strip HTML and PHP tags from a string
	$sStr=str_replace($arrWords, "", $sStr);
	$sStr=str_replace(" ", "-", $sStr);
	//$sStr=html_entity_decode($sStr);
	return $sStr;
}


if ( ! function_exists('dd'))
{
    function dd($data = array() , $clean = false){

        if ($clean) {
            echo "<pre style='background-color:#ffffff; border:3px solid #ecf0f1; color:#df5000;'>";
            die(var_dump($data));
            echo "</pre>";
            die();
        }else{
            echo "<pre style='background-color:#ffffff; border:3px solid #ecf0f1; color:#df5000;'>";
            print_r($data);
            echo "</pre>";
            die();
        }
    }
}

/**
 * Redirect to given URL
 */
//Start redirect
if (!function_exists('redirect')) {
    function redirect($url){
        header("Location: $url");
        exit;
    }
}
//End redirect


// goPrevious function
if ( ! function_exists('goPrevious'))
{
    function goPrevious() {
        echo "javascript:window.history.go(-1);";
    }
}
// end goPrevious function

function dateFormattingEn2Bn($bd_date){
    //Convert a English date to Bangla date
    $en=array("AM","PM","am","pm","Saturday","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","January","February","March","April","May","June","July","August","September","October","November","December","0","1","2","3","4","5","6","7","8","9");
    $bn=array("এএম","পিএম","এএম","পিএম","শনিবার","রবিবার","সোমবার","মঙ্গলবার","বুধবার","বৃহস্পতিবার","শুক্রবার","জানুয়ারি","ফেব্রুয়ারি","মার্চ","এপ্রিল","মে","জুন","জুলাই","আগস্ট","সেপ্টেম্বর","অক্টোবর","নভেম্বর","ডিসেম্বর","০","১","২","৩","৪","৫","৬","৭","৮","৯");
    $bd_date=str_replace($en,$bn,$bd_date);
    return $bd_date;
}


// $sMsgInsert="<div align='center' class='DMsgSuccess'>Inserted into the list successfully.</div>";
// $sMsgInsertFail="<div align='center' class='DMsgFail'>Error :: Your information was not Inserted.<br />Please ask your web master for help.</div>".mysqli_errno($connEMM).": ".mysqli_error($connEMM);
// $sMsgUpdate="<div align='center' class='DMsgSuccess'>Updated the list successfully.</div>";
// $sMsgUpdateFail="<div align='center' class='DMsgFail'>Error :: Your information was not Updated.<br />Please ask your web master for help.</div>".mysqli_errno($connEMM).": ".mysqli_error($connEMM);
// $sMsgDelete="<div align='center' class='DMsgSuccess'>Deleted from list successfully.</div>";
// $sMsgDeleteFail="<div align='center' class='DMsgFail'>Error :: Your information was not Deleted from the list.<br />Please ask your web master for help.</div>".mysqli_errno($connEMM).": ".mysqli_error($connEMM);
// $sMsgActivate="<div align='center' class='DMsgSuccess'>Information Activate successfully.</div>";
// $sMsgActivateFail="<div align='center' class='DMsgFail'>Error :: Your information was not Activate from the list.<br />Please ask your web master for help.</div>".mysqli_errno($connEMM).": ".mysqli_error($connEMM);
//
// $sMsgUpload="<div align='center' class='DMsgSuccess'>File Uploaded successfully.</div>";
// $sMsgUploadFail="<div align='center' class='DMsgFail'>Error :: Your File was not Uploaded successfully.<br />Please ask your web master for help.</div>".mysqli_errno($connEMM).": ".mysqli_error($connEMM);
// $sMsgAuTrailInsert="<div align='center' class='DMsgFail'>Insert AuditTrail Error: </div>".mysqli_errno($connEMM).": ".mysqli_error($connEMM);
// $sMsgRequired="<span class='SpnRequired'>*</span>";
// $sMsgNumber="<span class='SpnNumber'>(Type Number)</span>";
//
// $sMsgImgUpldSizeMaxSM="<div class='DMsgFail'>You cannot upload image file more than $iMaxImgSizeContentSM KB. Please decrease the image file size and try again.</div>";
// $sMsgImgUpldSizeMaxBG="<div class='DMsgFail'>You cannot upload image file more than $iMaxImgSizeContentBG KB. Please decrease the image file size and try again.</div>";
// $sMsgImgUpldSizeMaxGal="<div class='DMsgFail'>You cannot upload image file more than $iMaxImgSizeGallery KB. Please decrease the image file size and try again.</div>";
//
// $sMsgImgHm="<div class='DInfo'>Dimention Home Page: 240px X 160px<br />(image must be smaller than $iMaxImgSizeContentSM)</div>";
// $sMsgImgSlide="<div class='DInfo'>Dimention Slide: 480px X 320px<br />(image must be smaller than $iMaxImgSizeContentSM)</div>";
// $sMsgImgInner="<div class='DInfo'>Dimention Details Page: maximum width 720px<br />(image must be smaller than $iMaxImgSizeContentBG))</div>";
// $sMsgImgAll="<div class='DInfo'>media/imgAll/".$sImgDir."/</div>";
// $sMsgImgAlbum="<div class='DInfo'>Dimention 300px X 200px<br />(image must be smaller than $iMaxImgSizeGallery)</div>";
// $sMsgImgGallery="<div class='DInfo'>width/height max 720px<br />(image must be smaller than $iMaxImgSizeGallery)</div>";
// $sMsgImgCatIcon="<div class='DInfo'>Dimention: 0px X 0px<br />(image must be smaller than $iMaxImgSizeContentSM)</div>";
// $sMsgImgCatMenu="<div class='DInfo'>Dimention: 56px X 28px<br />(image must be smaller than $iMaxImgSizeContentSM)</div>";
// $sMsgImgCatCoverHome="<div class='DInfo'>Dimention: 230px X 28px<br />(image must be smaller than $iMaxImgSizeContentSM)</div>";
// $sMsgImgCatCoverInner="<div class='DInfo'>Dimention: 980px X 80px<br />(image must be smaller than $iMaxImgSizeContentBG)</div>";
?>
