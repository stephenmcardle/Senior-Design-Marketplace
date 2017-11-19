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

$colname_getViewProfile = "-1";
if (isset($_GET['userid'])) {
  $colname_getViewProfile = $_GET['userid'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getViewProfile = sprintf("SELECT userid, `role`, department, firstname, middle, lastname, email, major, gradyear, skill1, skill2, skill3, skill4, skill5, resume, image FROM user_profile WHERE userid = %s", GetSQLValueString($colname_getViewProfile, "int"));
$getViewProfile = mysql_query($query_getViewProfile, $sdmarket) or die(mysql_error());
$row_getViewProfile = mysql_fetch_assoc($getViewProfile);
$totalRows_getViewProfile = mysql_num_rows($getViewProfile);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getCreated = sprintf("SELECT * from projects JOIN projectstatus ON projects.status = projectstatus.status WHERE createdby = %s", GetSQLValueString($row_getViewProfile['email'], "text"));
$getCreated = mysql_query($query_getCreated, $sdmarket) or die(mysql_error());
$row_getCreated = mysql_fetch_assoc($getCreated);
$totalRows_getCreated = mysql_num_rows($getCreated);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getCurrent = sprintf("SELECT * from projectrelation JOIN projects ON projectrelation.idprojects = projects.idprojects JOIN projectstatus ON projects.status = projectstatus.status WHERE projectrelation.userid = %s AND (projectrelation.role = 1 OR projectrelation.role = 2)", GetSQLValueString($row_getViewProfile['userid'], "text"));
$getCurrent = mysql_query($query_getCurrent, $sdmarket) or die(mysql_error());
$row_getCurrent = mysql_fetch_assoc($getCurrent);
$totalRows_getCurrent = mysql_num_rows($getCurrent);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
#apDiv1 {
	position:absolute;
	left:422px;
	top:41px;
	width:530px;
	height:701px;
	z-index:1;
}
#apDiv2 {
	position:absolute;
	left: 970px;
	top: 41px;
	width:530px;
	height:701px;
	z-index:2;


}
</style>
</head>
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
h1 {
	font-family: Verdana, Geneva, sans-serif;
	color: #900;
}
</style>






<body>
<div id="apDiv1">
  <h1>Created Projects</h1>

<?php if (mysql_num_rows($getCreated) == 0) { echo 'This person has not created any projects.';
}
else{  ?> 
  
<?php do { 
mysql_select_db($database_sdmarket, $sdmarket);
$query_getCreatedLikes = sprintf("SELECT COUNT(idprojects) AS likes FROM project_likes WHERE idprojects = %s", 
GetSQLValueString($row_getCreated['idprojects'] , "int"));

$getCreatedLikes = mysql_query($query_getCreatedLikes, $sdmarket) or die(mysql_error());
$row_getCreatedLikes = mysql_fetch_assoc($getCreatedLikes);
$totalRows_getCreatedLikes = mysql_num_rows($getCreatedLikes);
?>

  <table width="530" border="1" rules="none" frame="box" align="left" cellpadding="5">
  <tr >  
    <td colspan="3" align="center" valign="top">
      <strong>
      <pt></pt></strong> <strong><?php echo $row_getCreated['title']; ?></strong>
      <hr /></td>
  </tr>
  <tr >
    <td width="100" rowspan="2" align="center" valign="top"><img src="images/<?php echo $row_getCreated['image']; ?>" alt="" width="100" height="40"  />
      <p><img src="images/like.png" alt="" width="23" height="23" /> <?php echo $row_getCreatedLikes['likes']; ?></p>
      <p>Views: <?php echo $row_getCreated['views']; ?></p></td>
    <td width="200" align="center" valign="top">
      <a href="viewproject.php?idprojects=<?php echo $row_getCreated['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getCreated['photo']?>" height="150" /></a>
    </td>
    <td valign="top">
      <p><strong>Requested Major(s):</strong></p>
      <?php if ($row_getCreated['BMEqty'] > 0) echo "Biomedical Engineering <br />"; 
		 	if ($row_getCreated['CEqty'] > 0) echo "Civil Engineering <br />"; 
			if ($row_getCreated['ChemEqty'] > 0) echo "Chemical Engineering <br />"; 
		    if ($row_getCreated['CPEqty'] > 0) echo "Computer Engineering <br />"; 
			if ($row_getCreated['EEqty'] > 0) echo "Electrical Engineering <br />"; 
			if ($row_getCreated['EMqty'] > 0) echo "Engineering Management <br />"; 
			if ($row_getCreated['EnvEqty'] > 0) echo "Environmental Engineering <br />"; 
			if ($row_getCreated['ISqty'] > 0) echo "Information Systems <br />"; 
			if ($row_getCreated['MEqty'] > 0) echo "Mechanical Engineering <br />"; 
			if ($row_getCreated['NEqty'] > 0) echo "Naval Engineering <br />"; 
			if ($row_getCreated['SYSqty'] > 0) echo "Systems Engineering <br />";?>
    </td>
  </tr>
  <tr >
    <td colspan="2" valign="top"><?php echo $row_getCreated['summary']; ?>...</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php } while($row_getCreated = mysql_fetch_assoc($getCreated)); ?>
<?php } ?>

</div>







<div id="apDiv2">
  <h1>Currently Working On...</h1>

<?php if (mysql_num_rows($getCurrent) == 0) { echo 'This person is not currently working on any projects.';
}
else{  ?> 
  
<?php do { 
mysql_select_db($database_sdmarket, $sdmarket);
$query_getCurrentLikes = sprintf("SELECT COUNT(idprojects) AS likes FROM project_likes WHERE idprojects = %s", 
GetSQLValueString($row_getCurrent['idprojects'] , "int"));

$getCurrentLikes = mysql_query($query_getCurrentLikes, $sdmarket) or die(mysql_error());
$row_getCurrentLikes = mysql_fetch_assoc($getCurrentLikes);
$totalRows_getCurrentLikes = mysql_num_rows($getCurrentLikes);
?>

  <table width="530" border="1" rules="none" frame="box" align="left" cellpadding="5">
  <tr >  
    <td colspan="3" align="center" valign="top">
      <strong>
      <pt></pt></strong> <strong><?php echo $row_getCurrent['title']; ?></strong>
      <hr /></td>
  </tr>
  <tr >
    <td width="100" rowspan="2" align="center" valign="top"><img src="images/<?php echo $row_getCurrent['image']; ?>" alt="" width="100" height="40"  />
      <p><img src="images/like.png" alt="" width="23" height="23" /> <?php echo $row_getCurrentLikes['likes']; ?></p>
      <p>Views: <?php echo $row_getCurrent['views']; ?></p></td>
    <td width="200" align="center" valign="top">
      <a href="viewproject.php?idprojects=<?php echo $row_getCurrent['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getCurrent['photo']?>" height="150" /></a>
    </td>
    <td valign="top">
      <p><strong>Requested Major(s):</strong></p>
      <?php if ($row_getCurrent['BMEqty'] > 0) echo "Biomedical Engineering <br />"; 
		 	if ($row_getCurrent['CEqty'] > 0) echo "Civil Engineering <br />"; 
			if ($row_getCurrent['ChemEqty'] > 0) echo "Chemical Engineering <br />"; 
		    if ($row_getCurrent['CPEqty'] > 0) echo "Computer Engineering <br />"; 
			if ($row_getCurrent['EEqty'] > 0) echo "Electrical Engineering <br />"; 
			if ($row_getCurrent['EMqty'] > 0) echo "Engineering Management <br />"; 
			if ($row_getCurrent['EnvEqty'] > 0) echo "Environmental Engineering <br />"; 
			if ($row_getCurrent['ISqty'] > 0) echo "Information Systems <br />"; 
			if ($row_getCurrent['MEqty'] > 0) echo "Mechanical Engineering <br />"; 
			if ($row_getCurrent['NEqty'] > 0) echo "Naval Engineering <br />"; 
			if ($row_getCurrent['SYSqty'] > 0) echo "Systems Engineering <br />";?>
    </td>
  </tr>
  <tr >
    <td colspan="2" valign="top"><?php echo $row_getCurrent['summary']; ?>...</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php } while($row_getCurrent = mysql_fetch_assoc($getCurrent)); ?>
<?php } ?>

</div>







<table width="800">
  <tr>
    <td width="130" align="center" valign="middle"><img src="images/profileupload/<?php echo $row_getViewProfile['image']; ?>" alt="" width="128" height="128" border="1" /></td>
    <td width="16" align="left">&nbsp;</td>
    <td width="624" colspan="2" align="left">  <h2><?php echo $row_getViewProfile['firstname']; ?> <?php echo $row_getViewProfile['lastname']; ?></h2></td>
  </tr>
</table>
<br />
<table width="400">
  <tr>
    <td width="130" align="right"><strong>E-mail Address:</strong></td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['email']; ?></td>
  </tr>
  <tr>
    <td width="130" align="right">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="130" align="right"><strong>Major:</strong></td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['major']; ?></td>
  </tr>
  <tr>
    <td width="130" align="right"><strong>Graduation Year:</strong></td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['gradyear']; ?></td>
  </tr>
  <tr>
    <td width="130" align="right">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="130" align="right"><strong>Skills:</strong></td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['skill1']; ?></td>
  </tr>
  <tr>
    <td width="130" align="right">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['skill2']; ?></td>
  </tr>
  <tr>
    <td width="130" align="right">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['skill3']; ?></td>
  </tr>
  <tr>
    <td width="130" align="right">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['skill4']; ?></td>
  </tr>
  <tr>
    <td width="130" align="right">&nbsp;</td>
    <td width="16">&nbsp;</td>
    <td><?php echo $row_getViewProfile['skill5']; ?></td>
  </tr>
  <?php if ($row_getViewProfile['resume'] == ''){
  }
  else { ?>
	<tr>
    <td width="130" align="right"><strong>Resume:</strong></td>
    <td width="16">&nbsp;</td>
    <td><a href="resumes/<?php echo $row_getViewProfile['resume']; ?>">Click to download</a></td>
  </tr>
  <?php } ?>
</table>
<?php if ($_SESSION['MM_Username'] == $row_getViewProfile['email']) {
	?>
<p>
<form action="editprofile.php" method="POST" name="form_edit" target="mainFrame" id="form_edit">
  <input type="submit" name="submit" id="submit" value="Edit Profile" />
  <input type="hidden" name="email" id="email" value="<?php echo $_SESSION['MM_Username']; ?>" />
</form>
</p>
<?php } ?>
</body>
</html>
<?php
mysql_free_result($getViewProfile);

mysql_free_result($getCreated);
?>
