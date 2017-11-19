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

mysql_select_db($database_sdmarket, $sdmarket);
$query_getPopular = "SELECT projects.idprojects, projects.title, projects.summary, projects.status, projects.likes, projects.views, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status ORDER BY projects.likes DESC, projects.views DESC LIMIT 5";
$getPopular = mysql_query($query_getPopular, $sdmarket) or die(mysql_error());
$row_getPopular = mysql_fetch_assoc($getPopular);
$totalRows_getPopular = mysql_num_rows($getPopular);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getRecent = "SELECT projects.idprojects, projects.title, projects.summary, projects.likes, projects.views, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status ORDER BY projects.created DESC LIMIT 5";
$getRecent = mysql_query($query_getRecent, $sdmarket) or die(mysql_error());
$row_getRecent = mysql_fetch_assoc($getRecent);
$totalRows_getRecent = mysql_num_rows($getRecent);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Home</title>
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
</head>

<body>
<h1>Most Popular</h1>
<table cellpadding="0" cellspacing="0">
    <tr>
    <?php do { 
mysql_select_db($database_sdmarket, $sdmarket);
$query_getPopLikes = sprintf("SELECT COUNT(idprojects) AS likes FROM project_likes WHERE idprojects = %s", 
GetSQLValueString($row_getPopular['idprojects'] , "int"));

$getPopLikes = mysql_query($query_getPopLikes, $sdmarket) or die(mysql_error());
$row_getPopLikes = mysql_fetch_assoc($getPopLikes);
$totalRows_getPopLikes = mysql_num_rows($getPopLikes);
?>
      <td height="30" align="center" background="images/indexruletop.jpg"><img src="images/like.png" width="18" height="18" /> <?php echo $row_getPopLikes['likes']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_getPopular['views']; ?> Views</td>
      <td> </td>
    <?php } while ($row_getPopular = mysql_fetch_assoc($getPopular)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getPopular,0); $row_getPopular = mysql_fetch_array($getPopular); do { ?> 
      	<td width="250" align="center" background="images/indexrulemid.jpg"><h3><?php echo $row_getPopular['title']; ?></h3></td>
        <td> </td>
	  <?php } while ($row_getPopular = mysql_fetch_assoc($getPopular)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getPopular,0); $row_getPopular = mysql_fetch_array($getPopular); do { ?> 
      	<td align="center" background="images/indexrulemid.jpg"><p><a href="viewproject.php?idprojects=<?php echo $row_getPopular['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getPopular['photo']; ?>" width="200" /></a></p></td>
        <td> </td>
	  <?php } while ($row_getPopular = mysql_fetch_assoc($getPopular)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getPopular,0); $row_getPopular = mysql_fetch_array($getPopular); do { ?> 
   	  <td width="250" height="50" align="center" background="images/indexrulemid.jpg"><?php echo substr($row_getPopular['summary'],0,100); ?> ...</td>
        <td height="50" width="25"> </td>
	  <?php } while ($row_getPopular = mysql_fetch_assoc($getPopular)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getPopular,0); $row_getPopular = mysql_fetch_array($getPopular); do { ?> 
      	<td height="40" align="center" background="images/indexrulebot.jpg"><img title="<?php if ($row_getPopular['status'] == 1) echo "This project is just an IDEA."; elseif ($row_getPopular['status'] == 2) echo "This project is in the SIGN-UP phase!"; elseif ($row_getPopular['status'] == 3) echo "This project team is FULL!";?>" src="images/<?php echo $row_getPopular['image']; ?>" width="99" height="38"  /></td>
        <td> </td>
	  <?php } while ($row_getPopular = mysql_fetch_assoc($getPopular)); ?>
    </tr>
</table>
<h1>What's New</h1>
  <table cellpadding="0" cellspacing="0">
    <tr>
    <?php do { 
mysql_select_db($database_sdmarket, $sdmarket);
$query_getRecLikes = sprintf("SELECT COUNT(idprojects) AS likes FROM project_likes WHERE idprojects = %s", 
GetSQLValueString($row_getRecent['idprojects'] , "int"));

$getRecLikes = mysql_query($query_getRecLikes, $sdmarket) or die(mysql_error());
$row_getRecLikes = mysql_fetch_assoc($getRecLikes);
$totalRows_getRecLikes = mysql_num_rows($getRecLikes);
?>
      <td height="30" align="center" background="images/indexruletop.jpg"><img src="images/like.png" width="18" height="18" /> <?php echo $row_getRecLikes['likes']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_getRecent['views']; ?> Views</td>
      <td> </td>
    <?php } while ($row_getRecent = mysql_fetch_assoc($getRecent)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getRecent,0); $row_getRecent = mysql_fetch_array($getRecent); do { ?> 
      	<td width="250" align="center" background="images/indexrulemid.jpg"><h3><?php echo $row_getRecent['title']; ?></h3></td>
        <td> </td>
	  <?php } while ($row_getRecent = mysql_fetch_assoc($getRecent)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getRecent,0); $row_getRecent = mysql_fetch_array($getRecent); do { ?> 
      	<td align="center" background="images/indexrulemid.jpg"><p><a href="viewproject.php?idprojects=<?php echo $row_getRecent['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getRecent['photo']; ?>" width="200"  /></a></p></td>
        <td> </td>
	  <?php } while ($row_getRecent = mysql_fetch_assoc($getRecent)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getRecent,0); $row_getRecent = mysql_fetch_array($getRecent); do { ?> 
   	  <td width="250" height="50" align="center" background="images/indexrulemid.jpg"><?php echo substr($row_getRecent['summary'],0,100); ?> ...</td>
        <td height="50" width="25"> </td>
	  <?php } while ($row_getRecent = mysql_fetch_assoc($getRecent)); ?>
    </tr>
    <tr>
      <?php mysql_data_seek($getRecent,0); $row_getRecent = mysql_fetch_array($getRecent); do { ?> 
      	<td height="40" align="center" background="images/indexrulebot.jpg"><img src="images/<?php echo $row_getRecent['image']; ?>" width="99" height="38"  /></td>
        <td> </td>
	  <?php } while ($row_getRecent = mysql_fetch_assoc($getRecent)); ?>
    </tr>
</table>
</body>
</html>
<?php
mysql_free_result($getPopular);

mysql_free_result($getRecent);
?>
