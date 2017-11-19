<?php require_once('Connections/sdmarket.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('Connections/sdmarket.php'); ?>
<?php

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="alreadyexist.php";
  $loginUsername = $_POST['email'];
  $LoginRS__query = sprintf("SELECT email FROM user_profile WHERE email=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_sdmarket, $sdmarket);
  $LoginRS=mysql_query($LoginRS__query, $sdmarket) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$editFormAction = $_SERVER['PATH_INFO'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

mysql_select_db($database_sdmarket, $sdmarket);
$query_getMajors = "SELECT major FROM majors ORDER BY major ASC";
$getMajors = mysql_query($query_getMajors, $sdmarket) or die(mysql_error());
$row_getMajors = mysql_fetch_assoc($getMajors);
$totalRows_getMajors = mysql_num_rows($getMajors);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getGradyear = "SELECT gradyear FROM gradyear ORDER BY gradyear ASC";
$getGradyear = mysql_query($query_getGradyear, $sdmarket) or die(mysql_error());
$row_getGradyear = mysql_fetch_assoc($getGradyear);
$totalRows_getGradyear = mysql_num_rows($getGradyear);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getProfile = sprintf("SELECT userid, firstname, middle, lastname, email, major, gradyear, skill1, skill2, skill3, skill4, skill5, resume, image, resume FROM user_profile WHERE email = %s", GetSQLValueString($_POST['email'], "text"));
$getProfile = mysql_query($query_getProfile, $sdmarket) or die(mysql_error());
$row_getProfile = mysql_fetch_assoc($getProfile);
$totalRows_getProfile = mysql_num_rows($getProfile);


//Determine whether the user has uploaded a new profile pic.
if ($_FILES['image']['name'] == '') {
$imagename = $row_getProfile['image'];
}
else {
$imagename = $_FILES['image']['name'];
}


//Determine whether the user has uploaded a new resume.
if ($_FILES['resume']['name'] == '') {
$resumename = $row_getProfile['resume'];
}
else {
$resumename = $_FILES['resume']['name'];
}


//Begin code for if the user HAS NOT changed their password:
if ($_POST['password'] == 'xxxxxxxxxx') {
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_update")) {
  $updateSQL = sprintf("UPDATE user_profile SET firstname=%s, middle=%s, lastname=%s, email=%s, major=%s, gradyear=%s, skill1=%s, skill2=%s, skill3=%s, skill4=%s, skill5=%s, image=%s, resume=%s WHERE userid=%s",
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['middle'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['major'], "text"),
                       GetSQLValueString($_POST['gradyear'], "int"),
                       GetSQLValueString($_POST['skill1'], "text"),
                       GetSQLValueString($_POST['skill2'], "text"),
                       GetSQLValueString($_POST['skill3'], "text"),
                       GetSQLValueString($_POST['skill4'], "text"),
                       GetSQLValueString($_POST['skill5'], "text"),
					   GetSQLValueString($imagename, "text"),
					   GetSQLValueString($resumename, "text"),
                       GetSQLValueString($_POST['userid'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($updateSQL, $sdmarket) or die(mysql_error());

  $updateGoTo = "profilesuccess.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
 }
}
//End code for if the user HAS NOT changed their password


//Begin code for if the user HAS INDEED changed their password:
if ($_POST['password'] != 'xxxxxxxxxx') {
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_update")) {
  $updateSQL = sprintf("UPDATE user_profile SET password='".md5($_POST['password'])."', firstname=%s, middle=%s, lastname=%s, email=%s, major=%s, gradyear=%s, skill1=%s, skill2=%s, skill3=%s, skill4=%s, skill5=%s, image=%s, resume=%s WHERE userid=%s",
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['middle'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['major'], "text"),
                       GetSQLValueString($_POST['gradyear'], "int"),
                       GetSQLValueString($_POST['skill1'], "text"),
                       GetSQLValueString($_POST['skill2'], "text"),
                       GetSQLValueString($_POST['skill3'], "text"),
                       GetSQLValueString($_POST['skill4'], "text"),
                       GetSQLValueString($_POST['skill5'], "text"),
					   GetSQLValueString($imagename, "text"),
					   GetSQLValueString($resumename, "text"),
                       GetSQLValueString($_POST['userid'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($updateSQL, $sdmarket) or die(mysql_error());

  $updateGoTo = "profilesuccess.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
 }
}
//End code for if the user HAS INDEED changed their password



//Added for picture uploads//	
 $target = "images/profileupload/";  //This is the directory where images will be saved//
 $target = $target . basename($_FILES['image']['name']);
//Writes the photo to the server 
move_uploaded_file($_FILES['image']['tmp_name'], $target);
 

//Added for resume uploads//
 $target = "resumes/"; //This is the directory where resumes will be saved//
 $target = $target . basename($_FILES['resume']['name']);
//Writes the resume to the server
move_uploaded_file($_FILES['resume']['tmp_name'], $target);




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Edit Profile</title>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/register.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
h1 {
	font-family: Verdana, Geneva, sans-serif;
	color: #900;
}
h2 {
	font-family: Verdana, Geneva, sans-serif;
	color: #900;
}
</style>
</head>

<body>
<div>
  <h1>Edit Your Profile</h1>
</div>
<div id="divRegister">
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form_update" id="form_update" onsubmit="return validateEmail();">
    <p><img src="images/profileupload/<?php echo $row_getProfile['image']; ?>" /> <label for="image"><br />
      Change your Profile Pic:</label>
    <input name="image" type="file" id="image" /></p>
    <p>
      <label for="firstname">First Name:</label>
      <input name="firstname" type="text" id="firstname" tabindex="1" value="<?php echo $row_getProfile['firstname']; ?>" />
    </p>
  <p>
    <label for="middle">Middle Initial:</label>
    <input name="middle" type="text" id="middle" tabindex="2" size="1" maxlength="1" value="<?php echo $row_getProfile['middle']; ?>"/>
  </p>
  <p>
    <label for="lastname">Last Name:</label>
    <input name="lastname" type="text" id="lastname" tabindex="3" value="<?php echo $row_getProfile['lastname']; ?>" />
  </p>
  <p>
    <label for="email">Stevens E-mail Address:</label>
    <input name="email" type="text" id="email" tabindex="4" value="<?php echo $row_getProfile['email']; ?>" />
  </p>
  <p><span id="sprypassword1">
    <label for="password4">Password:</label>
    <input name="password" type="password" id="password4" tabindex="5" value="xxxxxxxxxx" />
  <span class="passwordRequiredMsg">A value is required.</span></span></p>
  <p><span id="spryconfirm1">
    <label for="password5">Re-type your Password:</label>
    <input name="password2" type="password" id="password5" tabindex="6" value="xxxxxxxxxx" />
    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></p>
  <p>
    <label for="major">Major:</label>
    <select name="major" id="major" tabindex="7">
      <option value="" <?php if (!(strcmp("", $row_getProfile['major']))) {echo "selected=\"selected\"";} ?>></option>
      <?php
do {  
?>
      <option value="<?php echo $row_getMajors['major']?>"<?php if (!(strcmp($row_getMajors['major'], $row_getProfile['major']))) {echo "selected=\"selected\"";} ?>><?php echo $row_getMajors['major']?></option>
      <?php
} while ($row_getMajors = mysql_fetch_assoc($getMajors));
  $rows = mysql_num_rows($getMajors);
  if($rows > 0) {
      mysql_data_seek($getMajors, 0);
	  $row_getMajors = mysql_fetch_assoc($getMajors);
  }
?>
    </select>
  </p>
  <p>
    <label for="gradyear">Graduation Year:</label>
    <select name="gradyear" id="gradyear" tabindex="8">
      <option value="" <?php if (!(strcmp("", $row_getProfile['gradyear']))) {echo "selected=\"selected\"";} ?>></option>
      <?php
do {  
?>
      <option value="<?php echo $row_getGradyear['gradyear']?>"<?php if (!(strcmp($row_getGradyear['gradyear'], $row_getProfile['gradyear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_getGradyear['gradyear']?></option>
      <?php
} while ($row_getGradyear = mysql_fetch_assoc($getGradyear));
  $rows = mysql_num_rows($getGradyear);
  if($rows > 0) {
      mysql_data_seek($getGradyear, 0);
	  $row_getGradyear = mysql_fetch_assoc($getGradyear);
  }
?>
    </select>
  </p>
  <p>
    <label for="skill1">Skill 1:</label>
    <input name="skill1" type="text" id="skill1" tabindex="9" value="<?php echo $row_getProfile['skill1']; ?>" />
  </p>
  <p>
    <label for="skill2">Skill 2:</label>
    <input name="skill2" type="text" id="skill2" tabindex="10" value="<?php echo $row_getProfile['skill2']; ?>" />
  </p>
  <p>
    <label for="skill3">Skill 3:</label>
    <input name="skill3" type="text" id="skill3" value="<?php echo $row_getProfile['skill3']; ?>" />
  </p>
  <p>
    <label for="skill4">Skill 4:</label>
    <input name="skill4" type="text" id="skill4" value="<?php echo $row_getProfile['skill4']; ?>" />
  </p>
  <p>
    <label for="skill5">Skill 5:</label>
    <input name="skill5" type="text" id="skill5" value="<?php echo $row_getProfile['skill5']; ?>" />
  </p>
<p><a href="resumes/<?php echo $row_getProfile['resume']; ?>"><?php echo $row_getProfile['resume']; ?></a><br />
    <label for="resume">Upload a New Resume:</label>
    <input name="resume" type="file" id="resume" /></p>
  <p>
    <input name="role" type="hidden" id="role" tabindex="50" value="1" />
</p>
  <p>
    <input name="button_submit" type="submit" id="button_submit" tabindex="11" value="Submit" />
    <input type="reset" name="button_reset" id="button_reset" value="Reset" tabindex="12" />
  </p>
  <input type="hidden" name="userid" value="<?php echo $row_getProfile['userid'];?>" />
  <input type="hidden" name="MM_update" value="form_update" />
  </form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password4", {validateOn:["blur"]});

function validateEmail()
{
var x=document.forms["form1"]["email"].value;
var atpos=x.indexOf("@");
var dotpos=x.lastIndexOf(".");
var domain=x.indexOf("stevens.edu");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length || domain == -1 || domain == null)
  {
  alert("Not a valid e-mail address");
  return false;
  }
}

</script>
</body>
</html>
<?php
mysql_free_result($getMajors);

mysql_free_result($getGradyear);

mysql_free_result($getProfile);
?>
