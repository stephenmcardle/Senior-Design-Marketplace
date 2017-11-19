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

$colfirstname_getProfiles = "%";
if (isset($_POST['firstname'])) {
  $colfirstname_getProfiles = $_POST['firstname'];
}
$collastname_getProfiles = "%";
if (isset($_POST['lastname'])) {
  $collastname_getProfiles = $_POST['lastname'];
}
$colemail_getProfiles = "%";
if (isset($_POST['email'])) {
  $colemail_getProfiles = $_POST['email'];
}
$colmajor_getProfiles = "%";
if ($_POST['major'] != " ") {
  $colmajor_getProfiles = $_POST['major'];
}
$colgradyear_getProfiles = "%";
if ($_POST['gradyear'] != " ") {
  $colgradyear_getProfiles = $_POST['gradyear'];
}

$colskill1_getProfiles = "-1";
$colskill2_getProfiles = "-1";
$colskill3_getProfiles = "-1";
$colskill4_getProfiles = "-1";
$colskill5_getProfiles = "-1";

if (($_POST['skill1'] == "") && ($_POST['skill2'] == "") && ($_POST['skill3'] == "") && ($_POST['skill4'] == "") && ($_POST['skill5'] == "")) {
$colskill1_getProfiles = "%";
$colskill2_getProfiles = "%";
$colskill3_getProfiles = "%";
$colskill4_getProfiles = "%";
$colskill5_getProfiles = "%";
}

if ($_POST['skill1'] != "") {
  $colskill1_getProfiles = $_POST['skill1'];
}

if ($_POST['skill2'] != "") {
  $colskill2_getProfiles = $_POST['skill2'];
}

if ($_POST['skill3'] != "") {
  $colskill3_getProfiles = $_POST['skill3'];
}

if ($_POST['skill4'] != "") {
  $colskill4_getProfiles = $_POST['skill4'];
}

if ($_POST['skill5'] != "") {
  $colskill5_getProfiles = $_POST['skill5'];
}

mysql_select_db($database_sdmarket, $sdmarket);
$query_getProfiles = "SELECT user_profile.userid, roles.desc_role, user_profile.firstname, user_profile.middle, user_profile.lastname, user_profile.email, user_profile.major, user_profile.role, user_profile.gradyear, user_profile.skill1, user_profile.skill2, user_profile.skill3, user_profile.skill4, user_profile.skill5, user_profile.image 
FROM user_profile JOIN roles ON user_profile.role = roles.idroles
WHERE firstname LIKE '%$colfirstname_getProfiles%' 
AND lastname LIKE '%$collastname_getProfiles%'
AND email LIKE '%$colemail_getProfiles%'
AND major LIKE '%$colmajor_getProfiles%'
AND (gradyear LIKE '%$colgradyear_getProfiles%' OR gradyear IS NULL)
AND (skill1 LIKE '%$colskill1_getProfiles%'
OR skill1 LIKE '%$colskill2_getProfiles%'
OR skill1 LIKE '%$colskill3_getProfiles%'
OR skill1 LIKE '%$colskill4_getProfiles%'
OR skill1 LIKE '%$colskill5_getProfiles%'
OR skill2 LIKE '%$colskill1_getProfiles%'
OR skill2 LIKE '%$colskill2_getProfiles%'
OR skill2 LIKE '%$colskill3_getProfiles%'
OR skill2 LIKE '%$colskill4_getProfiles%'
OR skill2 LIKE '%$colskill5_getProfiles%'
OR skill3 LIKE '%$colskill1_getProfiles%'
OR skill3 LIKE '%$colskill2_getProfiles%'
OR skill3 LIKE '%$colskill3_getProfiles%'
OR skill3 LIKE '%$colskill4_getProfiles%'
OR skill3 LIKE '%$colskill5_getProfiles%'
OR skill4 LIKE '%$colskill1_getProfiles%'
OR skill4 LIKE '%$colskill2_getProfiles%'
OR skill4 LIKE '%$colskill3_getProfiles%'
OR skill4 LIKE '%$colskill4_getProfiles%'
OR skill4 LIKE '%$colskill5_getProfiles%'
OR skill5 LIKE '%$colskill1_getProfiles%'
OR skill5 LIKE '%$colskill2_getProfiles%'
OR skill5 LIKE '%$colskill3_getProfiles%'
OR skill5 LIKE '%$colskill4_getProfiles%'
OR skill5 LIKE '%$colskill5_getProfiles%')
ORDER BY lastname ASC";

$getProfiles = mysql_query($query_getProfiles, $sdmarket) or die(mysql_error());
$row_getProfiles = mysql_fetch_assoc($getProfiles);
$totalRows_getProfiles = mysql_num_rows($getProfiles);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Profiles</title>
</head>
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
</style>

<body>


<!--//-------------------------BEGIN "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->
<?php 
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getProfiles)==0) {echo 'Your search has not reurned any results. <a href="searchprofile.php">Please try your search again. </a>';}

//else, begin the script to display the results in an array....
else {
	
$count_profiles = mysql_num_rows($getProfiles);?>  
<?php
$i = 1;
do {
?>

<table <?php if ($row_getProfiles['role'] == '2') {?> bgcolor="#FFBFBF" <?php }?> width="600" border="1" rules="none" frame="box" cellpadding="5" align="left">
  <tr>
    <td width="132" height="150" rowspan="5" valign="top"><a href="viewprofile.php?userid=<?php echo $row_getProfiles['userid'] ?>"><img src="images/profileupload/<?php echo $row_getProfiles['image']; ?>" width=128 height=128 border="0" /></a></td>
    <td width="225" valign="top"><strong><?php echo $row_getProfiles['firstname']; ?> <?php echo $row_getProfiles['middle']; ?> <?php echo $row_getProfiles['lastname']; ?></strong></td>
    <td width="38" rowspan="5" valign="top"><strong>Skills:</strong></td>
    <td rowspan="5" valign="top"><?php echo $row_getProfiles['skill1']; ?><br /><?php echo $row_getProfiles['skill2']; ?><br /><?php echo $row_getProfiles['skill3']; ?><br /><?php echo $row_getProfiles['skill4']; ?><br /><?php echo $row_getProfiles['skill5']; ?></td>
  </tr>
  <tr>
    <td width="225"><strong>Major:</strong> <?php echo $row_getProfiles['major']; ?></td>
  </tr>
  <tr>
    <td width="225"><strong>Grad Year:</strong> <?php echo $row_getProfiles['gradyear']; ?></td>
  </tr>
  <tr>
    <td width="225"><strong>Email: </strong> <?php echo $row_getProfiles['email']; ?></td>
  </tr>
  <tr>
    <td width="225"><strong>Role:</strong> <?php echo $row_getProfiles['desc_role']; ?></td>
  </tr>
</table>

<?php 
if ($i%2) { ?>

<table width="3" align="left">
  <tr height="50">  
    <td>
    </td>
  </tr>
  <tr height="50">
    <td width="3">
    </td>
    <td width="3">
    </td>
    <td>
    </td>
  </tr>
  <tr height="50">
    <td>
    </td>
  </tr>
</table>

<?php ;
} else { 
	if ($i >= $count_profiles) { 
	} else { 
		?> <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
<p>&nbsp;</p> <?php ;
			} 
	} 
$i++; 

} while($row_getProfiles = mysql_fetch_assoc($getProfiles)); 

}?>


<!--//-------------------------END "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->

</body>
</html>
<?php
mysql_free_result($getProfiles);
?>
