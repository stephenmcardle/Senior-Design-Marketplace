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

$MM_restrictGoTo = "topframe.html";
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

$colname_getProfilepic = "null";
if (isset($_SESSION['MM_Username'])) {
  $colname_getProfilepic = $_SESSION['MM_Username'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getProfilepic = sprintf("SELECT user_profile.userid, user_profile.role, user_profile.firstname, user_profile.lastname, user_profile.image FROM user_profile WHERE user_profile.email LIKE %s", GetSQLValueString($colname_getProfilepic, "text"));
$getProfilepic = mysql_query($query_getProfilepic, $sdmarket) or die(mysql_error());
$row_getProfilepic = mysql_fetch_assoc($getProfilepic);
$totalRows_getProfilepic = mysql_num_rows($getProfilepic);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace</title>
<style type="text/css">
a {
	color: #FFF;
	font-size: medium;
}
body {
	margin-top: 0px;
	margin-left: 0px;
}
body table tr td p {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-style: normal;
	font-weight: bold;
	color: #FFF;
}
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
	color: #D6D6D6;
}
a:active {
	text-decoration: none;
}
</style>
</head>

<body bgcolor="#B10537">
<table width="100%">
  <tr>
    <td width="692" height="75"><a href="index.html" target="_top"><img src="images/SeniorDMarketplaceLogo.png" width="400" height="75" align="middle" /></a></td>
    <td align="right" valign="middle"><a href="viewprofile.php?userid=<?php echo $row_getProfilepic['userid']; ?>" target="mainFrame"><img src="images/profileupload/<?php echo $row_getProfilepic['image']; ?>" alt="" width="50" height="50" /></a></td>
    <td width="10" align="right" valign="middle">&nbsp;</td>
    <td width="200" height="75" align="left" valign="middle"><p><a href="viewprofile.php?userid=<?php echo $row_getProfilepic['userid']; ?>" target="mainFrame"><u><?php echo $row_getProfilepic['firstname']; ?> <?php echo $row_getProfilepic['lastname']; ?></u><br />
    </a><a href="https://spreadsheets.google.com/spreadsheet/viewform?formkey=dDZlWDR0d3pkUWlrTjN0T3UtVzV4bFE6MQ" target="_new">Report a Bug</a><br />
    <a href="SeniorD_UserGuide.pdf" target="_new">Download User Guide</a></p></td>
<td width="200" align="center">
    <?php if ($row_getProfilepic['role'] == 2)
	{
		?><p><a href="adminmenu.html" target="_top"><img src="images/AdminMenubutton.jpg" name="imgAdminMenu" width="200" height="35" onmouseover="document.imgAdminMenu.src='images/AdminMenubutton-Gray.jpg'" onmouseout="document.imgAdminMenu.src='images/AdminMenubutton.jpg'" /></a></p>
            <?php
	};
	?></td>

    <td width="204" height="75" align="right"><p><a href="myprojects.html" target="_top"><img src="images/MyProjectsbutton.jpg" name="imgMyprojects" width="200" height="35" onmouseover="document.imgMyprojects.src='images/MyProjectsbutton-Gray.jpg'" onmouseout="document.imgMyprojects.src='images/MyProjectsbutton.jpg'" /></a><br />
    <a href="logout.php" target="_top"><img src="images/LogoutButton.jpg" name="imgLogout" width="200" height="35" onmouseover="document.imgLogout.src='images/LogoutButton-Gray.jpg'" onmouseout="document.imgLogout.src='images/LogoutButton.jpg'" /></a></p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($getProfilepic);
?>
