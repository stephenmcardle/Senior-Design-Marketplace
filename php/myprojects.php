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
  $MM_referrer = $_SERVER['PATH_INFO'];
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

$colname_get_Role = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_get_Role = $_SESSION['MM_Username'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_get_Role = sprintf("SELECT userid, role FROM user_profile WHERE email = %s", GetSQLValueString($colname_get_Role, "text"));
$get_Role = mysql_query($query_get_Role, $sdmarket) or die(mysql_error());
$row_get_Role = mysql_fetch_assoc($get_Role);
$totalRows_get_Role = mysql_num_rows($get_Role);


//Current projects
mysql_select_db($database_sdmarket, $sdmarket);
$query_get_Current = sprintf("SELECT projectrelation.idprojects, projectrelation.userid, projectrelation.role, projects.status, projects.title, projects.summary, projects.photo, projectstatus.image FROM projectrelation JOIN projects ON projectrelation.idprojects=projects.idprojects JOIN projectstatus ON projectstatus.status=projects.status WHERE projectrelation.role = %s AND projectrelation.userid = %s", GetSQLValueString($row_get_Role['role'], "int"), GetSQLValueString($row_get_Role['userid'], "int"));
$get_Current = mysql_query($query_get_Current, $sdmarket) or die(mysql_error());
$row_get_Current = mysql_fetch_assoc($get_Current);
$totalRows_get_Current = mysql_num_rows($get_Current);

//Projects created
mysql_select_db($database_sdmarket, $sdmarket);
$query_get_Created = sprintf("SELECT projects.idprojects, projects.status, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projectstatus.status=projects.status WHERE projects.createdby = %s", GetSQLValueString($colname_get_Role, "text"));
$get_Created = mysql_query($query_get_Created, $sdmarket) or die(mysql_error());
$row_get_Created = mysql_fetch_assoc($get_Created);
$totalRows_get_Created = mysql_num_rows($get_Created);

//Project applications
mysql_select_db($database_sdmarket, $sdmarket);
$query_get_Applications = sprintf("SELECT projectrelation.idprojects, projectrelation.userid, projectrelation.role, projects.status, projects.title, projects.summary, projects.photo, projectstatus.image FROM projectrelation JOIN projects ON projectrelation.idprojects=projects.idprojects JOIN projectstatus ON projectstatus.status=projects.status WHERE projectrelation.role = 0 AND projectrelation.userid = %s", GetSQLValueString($row_get_Role['userid'], "int"));
$get_Applications = mysql_query($query_get_Applications, $sdmarket) or die(mysql_error());
$row_get_Applications = mysql_fetch_assoc($get_Applications);
$totalRows_get_Applications = mysql_num_rows($get_Applications);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | My Projects</title>
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
h2 {
	font-family: Verdana, Geneva, sans-serif;
	color: #900;
}
</style>


<body>
<h1>My Projects</h1>
<h2>Current Projects</h2>
<?php if (mysql_num_rows($get_Current) == 0) { echo 'You are not currently on any projects.';
}
else{?>
  <table width="1000" border="0">
    <tr>
      <td width="75">&nbsp;</td>
      <td width="155">&nbsp;</td>
      <td width="200"><div align="left"><strong>Project Title</strong></div></td>
      <td width="300"><div align="left"><strong>Project Summary</strong></div></td>
    </tr>
<?php do { ?>
    <tr>
      <td height="10"><img src="images/<?php echo $row_get_Current['image']; ?>" width="75" />
      <?php if ($row_get_Current['status'] != '3' && $row_get_Role['role'] == '2') {?>
      <form action="editproject.php" method="POST" name="EditProject" target="_self">
		<input type="hidden" name="idprojects" id="idprojects" value="<?php echo $row_get_Current['idprojects']?>"/>    
        <input type="submit" name="button" id="button" value="Edit Project" />
       </form>
   <?php } ?>
  	  </td>
      <td><a href="viewproject.php?idprojects=<?php echo $row_get_Current['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_get_Current['photo']; ?>" width="150" /></a></td>
      <td><div align="left"><strong><?php echo $row_get_Current['title']; ?></strong></div></td>
      <td><?php echo $row_get_Current['summary']; ?></td>
    </tr>
    <tr>
      <td height="11" colspan="4"><hr /></td>
    </tr>
  <?php } while ($row_get_Current = mysql_fetch_assoc($get_Current)); ?>
  </table>
<?php } ?>
<h2>Projects Created</h2>
<?php if (mysql_num_rows($get_Created) == 0) { echo 'You have not created any projects.';
}
else{?>
  <table width="1000" border="0">
    <tr>
      <td width="75">&nbsp;</td>
      <td width="155">&nbsp;</td>
      <td width="200"><div align="left"><strong>Project Title</strong></div></td>
      <td width="300"><div align="left"><strong>Project Summary</strong></div></td>
    </tr>
<?php do { ?>
    <tr>
      <td><p><img src="images/<?php echo $row_get_Created['image']; ?>" width="75" /></p>
      <p>
   <?php if ($row_get_Created['status'] == '1') {?>
      <form action="editproject.php" method="POST" name="EditProject" target="_self">
		<input type="hidden" name="idprojects" id="idprojects" value="<?php echo $row_get_Created['idprojects']?>"/>    
        <input type="submit" name="button" id="button" value="Edit Project" />
       </form>
   <?php } ?>
      </p></td>
      <td><a href="viewproject.php?idprojects=<?php echo $row_get_Created['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_get_Created['photo']; ?>" width="150" /></a></td>
      <td><div align="left"><strong><?php echo $row_get_Created['title']; ?></strong></div></td>
      <td><?php echo $row_get_Created['summary']; ?></td>
    </tr>
    <tr>
      <td colspan="4"><hr /></td>
    </tr>
  <?php } while ($row_get_Created = mysql_fetch_assoc($get_Created)); ?>    
  </table>
<?php } ?>
<h2>Project Applications</h2>
<?php if (mysql_num_rows($get_Applications) == 0) { echo 'You have no pending project applications.';
}
else{?>
<table width="1000" border="0">
    <tr>
      <td width="75">&nbsp;</td>
      <td width="155">&nbsp;</td>
      <td width="200"><div align="left"><strong>Project Title</strong></div></td>
      <td width="300"><div align="left"><strong>Project Summary</strong></div></td>
    </tr>
<?php do { ?>
  <tr>
    <td><img src="images/<?php echo $row_get_Applications['image']; ?>" width="75" /></td>
      <td><a href="viewproject.php?idprojects=<?php echo $row_get_Applications['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_get_Applications['photo']; ?>" width="150" /></a></td>
    <td><div align="left"><strong><?php echo $row_get_Applications['title']; ?></strong></div></td>
    <td><?php echo $row_get_Applications['summary']; ?></td>
    </tr>
  <tr>
    <td colspan="4"><hr /></td>
    </tr>
<?php } while ($row_get_Applications = mysql_fetch_assoc($get_Applications)); ?> 
</table>
<?php } ?>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($get_Current);

mysql_free_result($get_Created);

mysql_free_result($get_Role);
?>
